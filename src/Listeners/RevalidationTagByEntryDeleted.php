<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Morethingsdigital\StatamicNextjs\Services\InvalidationEntryService;
use Statamic\Events\EntryDeleted;

class RevalidationTagByEntryDeleted
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
    public function handle(EntryDeleted $event): void
    {
        $this->invalidationEntryService->invalidate(
            $event->entry
        );
    }
}
