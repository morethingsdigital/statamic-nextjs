<?php

namespace Morethingsdigital\StatamicNextjs\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Statamic\Entries\Entry;
use Statamic\Facades\CP\Toast;

class InvalidationEntryService
{

    public function __construct(
        private readonly InvalidationService $invalidationService,
        private readonly SiteService $siteService,
        private readonly TagService $tagService,
    ) {}

    public function invalidate(Entry $entry, bool $showToast = true)
    {
        try {
            $isPublished = $entry->published();
            if (!$isPublished) return;

            $selectedSite = $entry->locale();
            if (is_null($selectedSite)) throw new Exception('site not defined');

            $collectionHandle = $entry->collection()->handle();
            if (is_null($collectionHandle)) throw new Exception('collection handle not defined');

            $id = $entry->id() ?? null;
            $slug = $entry->slug() ?? null;

            $tags = $this->tagService->findCollectionTagsByHandle(
                selectedSite: $selectedSite,
                collection: $collectionHandle
            );

            $tagsToRevalidate = collect($tags)->map(function ($tag) use ($id, $slug) {
                $tags = [];
                $tags[] = $tag;

                if (!is_null($id)) {
                    $tags[] = "{$tag}::{$id}";
                }

                if (!is_null($slug)) {
                    $tags[] = "{$tag}::{$slug}";
                }

                return $tags;
            })->flatten();

            $tagsToRevalidate->each(function ($tag) use ($selectedSite) {
                $this->invalidationService->tag(selectedSite: $selectedSite, tag: $tag);
            });

            Log::info("Next.js Cache invalidated for {$entry->title} ({$entry->site()->name})");

            if ($showToast) Toast::info("Next.js Cache invalidated for {$entry->title} ({$entry->site()->name})");
        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());

            if ($showToast) {
                Toast::error("Next.js Cache invalidation failedfor {$entry->title} ({$entry->site()->name}) ");
            } else {
                throw new Exception($exception->getMessage(), $exception->getCode());
            }
        }
    }
}
