<?php

namespace Morethingsdigital\StatamicNextjs\Listeners;

use Exception;
use Morethingsdigital\StatamicNextjs\Services\InvalidationGlobalsService;
use Morethingsdigital\StatamicNextjs\Services\SiteService;
use Statamic\Events\GlobalSetSaved;


class RevalidationTagByGlobalSetSaved
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly InvalidationGlobalsService $invalidationService,
        private readonly SiteService $siteService,
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GlobalSetSaved $event): void
    {
        $selectedSite = $this->siteService->currenteSite();
        if (is_null($selectedSite)) throw new Exception('site not defined');

        $this->invalidationService->invalidate(
            selectedSite: $selectedSite,
            globals: $event->globals
        );
    }
}
