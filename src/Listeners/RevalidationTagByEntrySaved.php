<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Morethingsdigital\StatamicNextjs\Services\InvalidationService;
use Morethingsdigital\StatamicNextjs\Services\TagService;
use Statamic\Events\EntrySaved;
use Statamic\Facades\CP\Toast;

class RevalidationTagByEntrySaved
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly TagService $tagService,
        private readonly InvalidationService $invalidationService,
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EntrySaved $event): void
    {
        try {
            Log::info('RevalidationTagByEntrySaved');
            $collectionHandle = $event->entry->collection()->handle();

            if (!$collectionHandle) return;

            $tag = $this->tagService->findCollectionTagByHandle($collectionHandle);

            if (!$tag) return;

            $invalidated = $this->invalidationService->tag($tag);

            if ($invalidated) {
                Toast::info('Next.js Cache invalidated');
            } else {
                Toast::error('Next.js Cache invalidation failed');
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
