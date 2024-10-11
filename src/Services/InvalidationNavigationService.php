<?php

namespace Morethingsdigital\StatamicNextjs\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Statamic\Structures\NavTree;
use Statamic\Facades\CP\Toast;
use Statamic\Sites\Site;

class InvalidationNavigationService
{

    public function __construct(
        private readonly InvalidationService $invalidationService,
        private readonly SiteService $siteService,
        private readonly TagService $tagService,
    ) {}

    public function invalidate(NavTree $tree, bool $showToast = true)
    {
        try {
            $currentSite = $tree->locale();
            if (is_null($currentSite)) throw new Exception('site not defined');

            $navigationHandle = $tree->handle();
            if (is_null($navigationHandle)) throw new Exception('navigation handle not defined');

            $tags = $this->tagService->findNavigationTags(selectedSite: $currentSite);

            $tagsToRevalidate = collect($tags)->map(function ($tag) use ($navigationHandle) {
                $tags = [];

                if (!is_null($navigationHandle)) {
                    $tags[] = "{$tag}::{$navigationHandle}";
                }

                return $tags;
            })->flatten();


            $tagsToRevalidate->each(function ($tag) use ($currentSite) {
                $this->invalidationService->tag(selectedSite: $currentSite,  tag: $tag);
            });

            Log::info("Next.js Cache invalidated for {$navigationHandle} ({$currentSite})");

            if ($showToast) Toast::info("Next.js Cache invalidated for {$navigationHandle} ({$currentSite})");
        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());

            if ($showToast) {
                Toast::error("Next.js Cache invalidation failed for {$navigationHandle} ({$currentSite})");
            } else {
                throw new Exception($exception->getMessage(), $exception->getCode());
            }
        }
    }
}
