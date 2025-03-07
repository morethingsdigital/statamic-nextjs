<?php

namespace Morethingsdigital\StatamicNextjs\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use MoreThingsDigital\StatamicNextJs\Traits\ValidationEnabled;

class InvalidationService
{
    use ValidationEnabled;

    public function __construct()
    {
        if (!$this->isEnabled()) return;
    }

    public function tag(string $selectedSite, string $tag)
    {
        try {
            if (is_null(config('statamic.nextjs.revalidation_urls'))) {
                Log::info('No revalidation urls found in config - fallback to default: tag');
            }

            $key = config('statamic.nextjs.revalidation_type') ?? 'tag';

            $url = $this->getRevalidationUrl(selectedSite: $selectedSite);

            Log::info(['url' => $url, 'key' => $key, 'tag' => $tag]);

            if (is_null($url)) throw new Exception('No revalidation url found for selected site');

            $response = Http::withHeaders(
                $this->getHeaders()
            )->withQueryParameters([
                $key  => $tag
            ])->post($url);


            if (!$response->successful()) throw new Exception($response->clientError());

            Log::info($response->json());
            Log::info('---------------------------------');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    private function getHeaders()
    {
        $headers = [];

        $headers['accept'] = 'application/json';
        $headers['Content-Type'] = 'application/json';

        if (!is_null(config('statamic.nextjs.revalidation_secret')))
            $headers['x-api-key'] = config('statamic.nextjs.revalidation_secret');


        return $headers;
    }

    private function getRevalidationUrl(string $selectedSite)
    {
        $revalidationUrls = collect(config('statamic.nextjs.revalidation_urls') ?? [])->map(fn($value, $key) => ['site' => $key, 'url' => $value])->values();

        $revalidationUrlBySite = $revalidationUrls->where('site', $selectedSite)->first();
        return $revalidationUrlBySite['url'] ?? null;
    }
}
