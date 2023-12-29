<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Morethingsdigital\StatamicNextjs\Services\InvalidationService;
use Morethingsdigital\StatamicNextjs\Services\TagService;
use Statamic\Events\AssetSaved;
use Statamic\Facades\CP\Toast;

class RevalidationTagByAssetSaved
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
    public function handle(AssetSaved $event): void
    {
        try {
            $invalidated = $this->invalidationService->all();

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
