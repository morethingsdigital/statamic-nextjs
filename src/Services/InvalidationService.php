<?php

namespace Morethingsdigital\StatamicNextjs\Services;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class InvalidationService
{

  private PendingRequest $request;

  public function __construct(private readonly TagService $tagService)
  {
    if (config('statamic.nextjs.enabled')) {
      if (is_null(config('statamic.nextjs.revalidation_url'))) throw new Exception('revalidation url not defined');

      $headers = $this->getHeaders();
      $this->request = Http::withHeaders($headers)->baseUrl(config('statamic.nextjs.revalidation_url'));
    }
  }

  public function all()
  {
  }

  public function tag(string $tag)
  {
    try {
      if (!$this->request) throw new Exception('request not defined');

      $key = config('statamic.nextjs.revalidation_type') ?? 'tag';
      $response = $this->request->get('', [
        $key  => $tag
      ]);

      if (!$response->successful()) throw new Exception($response->clientError());

      return true;
    } catch (Exception $exception) {
      throw new Exception($exception->getMessage());
    }
  }


  private function getHeaders()
  {
    $headers = [];

    $headers['accept'] = 'application/json';
    $headers['Content-Type'] = 'application/json';

    if (!is_null(config('nextjs.revalidation_secret')))
      $headers['Authorization'] = config('statamic.nextjs.revalidation_secret');


    return $headers;
  }
}
