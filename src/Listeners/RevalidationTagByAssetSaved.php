<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Morethingsdigital\StatamicNextjs\Services\InvalidationAssetService;
use Statamic\Events\AssetSaved;

class RevalidationTagByAssetSaved
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
    public function handle(AssetSaved $event): void
    {
        $this->invalidationAssetService->invalidate(
            $event->asset
        );
    }
}
