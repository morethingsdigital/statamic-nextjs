<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Morethingsdigital\StatamicNextjs\Services\InvalidationService;
use Morethingsdigital\StatamicNextjs\Services\TagService;
use Statamic\Events\GlobalSetDeleted;
use Statamic\Facades\CP\Toast;

class RevalidationTagByGlobalSetDeleted
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
    public function handle(GlobalSetDeleted $event): void
    {
        try {
            $tag = $this->tagService->getGlobalsTag();

            if (!$tag) return;

            $this->invalidationService->tag($tag);

            Toast::info('Next.js Cache invalidated');
        } catch (Exception $exception) {
            Toast::error('Next.js Cache invalidation failed');
            Log::error($exception->getTraceAsString());
        }
    }
}
