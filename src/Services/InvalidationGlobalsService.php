<?php

namespace Morethingsdigital\StatamicNextjs\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use MoreThingsDigital\StatamicNextJs\Traits\ValidationEnabled;
use Statamic\Globals\GlobalSet;
use Statamic\Facades\CP\Toast;
use Statamic\Sites\Site;

class InvalidationGlobalsService
{
    use ValidationEnabled;

    public function __construct(
        private readonly InvalidationService $invalidationService,
        private readonly SiteService $siteService,
        private readonly TagService $tagService,
    ) {}

    public function invalidate(Site $selectedSite, GlobalSet $globals, bool $showToast = true)
    {
        try {
            if (!$this->isEnabled()) return;

            $title = $globals->title();
            if (is_null($title)) throw new Exception('global set title not defined');

            $handle = $globals->handle() ?? null;

            $tags = $this->tagService->findGlobalsTags(selectedSite: $selectedSite->handle());

            $tagsToRevalidate = collect($tags)->map(function ($tag) use ($handle) {
                $tags = [];
                $tags[] = $tag;

                if (!is_null($handle)) {
                    $tags[] = "{$tag}::{$handle}";
                }

                return $tags;
            })->flatten();
            +$tagsToRevalidate->each(function ($tag) use ($selectedSite) {
                $this->invalidationService->tag(selectedSite: $selectedSite, tag: $tag);
            });

            Log::info("Next.js Cache invalidated for {$title} ({$selectedSite->name()})");

            if ($showToast) Toast::info("Next.js Cache invalidated for {$title} ({$selectedSite->name()})");
        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());

            if ($showToast) {
                Toast::error("Next.js Cache invalidation failed for  {$title} ({$selectedSite->name()})");
            } else {
                throw new Exception($exception->getMessage(), $exception->getCode());
            }
        }
    }
}
