<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Morethingsdigital\StatamicNextjs\Services\InvalidationService;
use Morethingsdigital\StatamicNextjs\Services\TagService;
use Statamic\Events\NavTreeSaved;
use Statamic\Facades\CP\Toast;

class RevalidationTagByNavTreeSaved
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
    public function handle(NavTreeSaved $event): void
    {
        try {
            $tag = $this->tagService->getNavigationTag();

            if (!$tag) return;

            $this->invalidationService->tag($tag);

            Toast::info('Next.js Cache invalidated');
        } catch (Exception $exception) {
            Toast::error('Next.js Cache invalidation failed');
            Log::error($exception->getTraceAsString());
        }
    }
}
