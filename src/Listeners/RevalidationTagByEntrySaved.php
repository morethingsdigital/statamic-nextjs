<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Morethingsdigital\StatamicNextjs\Services\InvalidationEntryService;
use Statamic\Events\EntrySaved;

class RevalidationTagByEntrySaved
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly InvalidationEntryService $invalidationEntryService,
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EntrySaved $event): void
    {
        $this->invalidationEntryService->invalidate(
            $event->entry
        );
    }
}
