<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Morethingsdigital\StatamicNextjs\Services\InvalidationNavigationService;
use Statamic\Events\NavTreeSaved;

class RevalidationTagByNavTreeSaved
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly InvalidationNavigationService $invalidationNavigationService
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NavTreeSaved $event): void
    {
        $this->invalidationNavigationService->invalidate(
            $event->tree
        );
    }
}
