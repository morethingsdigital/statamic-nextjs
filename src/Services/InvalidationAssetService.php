<?php

namespace Morethingsdigital\StatamicNextjs\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use MoreThingsDigital\StatamicNextJs\Traits\ValidationEnabled;
use Statamic\Assets\Asset;
use Statamic\Facades\CP\Toast;

class InvalidationAssetService
{
    use ValidationEnabled;

    public function __construct(
        private readonly InvalidationService $invalidationService,
        private readonly SiteService $siteService,
        private readonly TagService $tagService,
    ) {}

    public function invalidate(Asset $asset)
    {
        try {
            if (!$this->isEnabled()) return;

            $selectedSite = $this->siteService->currenteSite();
            if (is_null($selectedSite)) throw new Exception('site not defined');

            $assetContainerHandle = $asset->container()->handle();
            if (is_null($assetContainerHandle)) throw new Exception('asset container handle not defined');

            $path = $asset->path();
            if (is_null($path)) throw new Exception('asset container handle not defined');

            $tags = $this->tagService->findAssetTags(
                selectedSite: $selectedSite
            );

            $tagsToRevalidate = collect($tags)->map(function ($tag) use ($assetContainerHandle, $path) {
                $tags = [];
                $encodedPath = base64_encode("{$assetContainerHandle}::{$path}");
                $tags[] = "{$tag}::{$encodedPath}";

                return $tags;
            })->flatten();

            $tagsToRevalidate->each(function ($tag) use ($selectedSite) {
                $this->invalidationService->tag(selectedSite: $selectedSite, tag: $tag);
            });

            Log::info("Next.js Cache invalidated for {$asset->path} ({$asset->container()->title()})");
            Toast::info("Next.js Cache invalidated for {$asset->path} ({$asset->container()->title()})");
        } catch (Exception $exception) {
            Toast::error("Next.js Cache invalidation failedfor {$asset->path} ({$asset->container()->title()}) ");
            Log::error($exception->getTraceAsString());
        }
    }
}
