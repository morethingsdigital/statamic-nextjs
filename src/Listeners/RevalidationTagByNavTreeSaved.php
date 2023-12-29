<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
