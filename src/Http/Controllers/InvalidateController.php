<?php

namespace Morethingsdigital\StatamicNextjs\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Morethingsdigital\StatamicNextjs\Services\InvalidationEntryService;
use Morethingsdigital\StatamicNextjs\Services\InvalidationGlobalsService;
use Morethingsdigital\StatamicNextjs\Services\InvalidationNavigationService;
use Morethingsdigital\StatamicNextjs\Services\SiteService;
use Morethingsdigital\StatamicNextjs\Services\TagService;
use Nette\NotImplementedException;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Nav;
use Statamic\Facades\Collection;
use Statamic\Facades\CP\Toast;
use Statamic\Facades\User;
use Statamic\Http\Controllers\CP\CpController;
use Statamic\Sites\Site;

class InvalidateController extends CpController
{

    public function __construct(
        private readonly SiteService $siteService,
        private readonly TagService $tagService,
        private readonly InvalidationEntryService $invalidationEntryService,
        private readonly InvalidationGlobalsService $invalidationGlobalsService,
        private readonly InvalidationNavigationService $invalidationNavigationService,
    ) {}

    public function invalidate(Request $request)
    {
        abort_unless(User::current()->can('view nextjs'), 403);

        $selectedSite = $this->siteService->currenteSite();
        $type = $request->input('type');
        $handle = $request->input('handle', null) ?? null;
        switch ($type) {
            case $this->tagService->getCollectionsHandle():
                $this->invalidateCollection(selectedSite: $selectedSite, handle: $handle);
                break;
            case $this->tagService->getGlobalsHandle():
                $this->invalidateGlobals(selectedSite: $selectedSite);
                break;
            case $this->tagService->getNavigationHandle():
                $this->invalidateNavigation(selectedSite: $selectedSite);
                break;
            case $this->tagService->getTaxonomiesHandle():
                $this->invalidateTaxonomies(selectedSite: $selectedSite, handle: $handle);
                break;
        }

        return redirect()->route('statamic.cp.nextjs.index');
    }

    private function invalidateCollection(Site $selectedSite, string $handle)
    {
        try {
            $collection = Collection::findByHandle($handle);
            if (is_null($collection)) return;

            $entries =  collect($this->tagService->findCollectionTagsByHandle(selectedSite: $selectedSite, collection: $handle))->map(
                function ($tag) {
                    return Collection::findByHandle($tag)->queryEntries()->get();
                }
            )->flatten()->unique();

            $entries->each(function ($entry) {
                $this->invalidationEntryService->invalidate(entry: $entry, showToast: false);
            });

            Toast::info("Next.js Cache invalidated for {$collection->title} ({$selectedSite->name()})");
        } catch (Exception $exception) {
            Toast::error("Next.js Cache invalidation failed for {$collection->title} ({$selectedSite->name()})");
        }
    }

    private function invalidateGlobals(Site $selectedSite)
    {
        try {
            $globalSets = GlobalSet::all();

            $globalSets->each(function ($globals) use ($selectedSite) {
                $this->invalidationGlobalsService->invalidate(selectedSite: $selectedSite, globals: $globals, showToast: false);
            });

            Toast::info("Next.js Cache invalidated for Globals ({$selectedSite->name()})");
        } catch (Exception $exception) {
            Toast::error("Next.js Cache invalidation failed for Globals ({$selectedSite->name()})");
        }
    }

    private function invalidateNavigation(Site $selectedSite)
    {
        try {

            $navs = Nav::all();

            $navs->each(function ($nav) use ($selectedSite) {
                $navTree = $nav->trees()->only($selectedSite->handle())->first();
                if (is_null($navTree)) return;

                $this->invalidationNavigationService->invalidate(tree: $navTree, showToast: false);
            });

            Toast::info("Next.js Cache invalidated for Navigation ({$selectedSite->name()})");
        } catch (Exception $exception) {
            Toast::error("Next.js Cache invalidation failed for Navigation ({$selectedSite->name()})");
        }
    }

    private function invalidateTaxonomies(Site $selectedSite, string $handle)
    {
        throw new NotImplementedException();
    }
}
