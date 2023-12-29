<?php

namespace Morethingsdigital\StatamicNextjs\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvalidationService
{

  private array $requests = [];

  public function __construct(private readonly TagService $tagService)
  {
    if (config('statamic.nextjs.enabled')) {
      $invalidationUrls = $this->invalidationUrls();

      Log::info($invalidationUrls);
      $headers = $this->getHeaders();

      foreach ($invalidationUrls as $revalidationUrrl) {
        $this->requests[] = Http::withHeaders($headers)->baseUrl($revalidationUrrl);
      }
    }
  }

  public function all()
  {
    Log::info('all');
    
    $tags = $this->tagService->all();

    foreach ($tags as $tag) {
      $this->tag($tag);
    }
  }

  public function tag(string $tag)
  {
    try {
      Log::info(count($this->requests));

      if (count($this->requests) === 0) throw new Exception('request not defined');

      Log::info(count($this->requests));

      foreach ($this->requests as $request) {
        $key = config('statamic.nextjs.revalidation_type') ?? 'tag';
        $response = $request->withQueryParameters([
          $key  => $tag
        ])->post('');

        if (!$response->successful()) throw new Exception($response->clientError());

        Log::info($response->json());
      }
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
      $headers['Authorization'] = config('statamic.nextjs.revalidation_secret');


    return $headers;
  }

  private function invalidationUrls()
  {
    if (is_null(config('statamic.nextjs.revalidation_urls')))
      throw new Exception('revalidation urls not defined');

    if (is_array(config('statamic.nextjs.revalidation_urls')))
      return config('statamic.nextjs.revalidation_urls');

    if (is_string(config('statamic.nextjs.revalidation_urls')))
      return Str::of(config('statamic.nextjs.revalidation_urls'))->explode(',')->toArray();

    return [];
  }
}
