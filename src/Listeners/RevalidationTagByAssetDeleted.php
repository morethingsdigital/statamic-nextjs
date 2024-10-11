<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Morethingsdigital\StatamicNextjs\Services\InvalidationAssetService;
use Statamic\Events\AssetDeleted;

class RevalidationTagByAssetDeleted
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly InvalidationAssetService $invalidationAssetService,
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AssetDeleted $event): void
    {
        $this->invalidationAssetService->invalidate(
            $event->asset
        );
    }
}
