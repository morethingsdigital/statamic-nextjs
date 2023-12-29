<?php

namespace Morethingsdigital\StatamicNextjs\Services;

use Illuminate\Support\Facades\Log;

class TagService
{

  public function __construct()
  {
  }

  private function getCustomTags()
  {
    return config('statamic.nextjs.custom_tag_revalidation');
  }

  private function getCollectionsHandle()
  {
    return 'collections';
  }

  private function getGlobalsHandle()
  {
    return 'globals';
  }

  private function getNavigationHandle()
  {
    return 'navigation';
  }

  private function getTaxonomiesHandle()
  {
    return 'taxonomies';
  }

  public function all()
  {
    return array_merge(
      $this->getCollectionTags(),
      $this->getTaxonomyTags(),
      array($this->getGlobalsTag()),
      array($this->getNavigationTag()),
    );
  }

  public function findCollectionTagByHandle(string $collection): ?string
  {
    $customTags = $this->getCustomTags();
    $handle = $this->getCollectionsHandle();

    if (empty($customTags)) return null;

    if (!isset($customTags[$handle])) return null;

    if (!is_array($customTags[$handle])) return null;

    if (!array_key_exists($collection, $customTags[$handle])) return null;

    return $customTags[$handle][$collection];
  }

  public function findTaxonomyTagByHandle(string $taxonomie): ?string
  {
    $customTags = $this->getCustomTags();
    $handle = $this->getCollectionsHandle()();

    if (empty($customTags)) return null;

    if (!isset($customTags[$handle])) return null;

    if (!is_array($customTags[$handle])) return null;

    if (!array_key_exists($taxonomie, $customTags[$handle])) return null;

    return $customTags[$handle][$taxonomie];
  }

  public function getCollectionTags(): ?array
  {
    $customTags = $this->getCustomTags();
    $handle = $this->getCollectionsHandle();

    if (empty($customTags)) return [];

    if (!isset($customTags[$handle])) return [];

    if (!is_array($customTags[$handle])) return [];

    return array_values($customTags[$handle]);
  }

  public function getTaxonomyTags(): ?array
  {
    $customTags = $this->getCustomTags();
    $handle = $this->getTaxonomiesHandle();

    if (empty($customTags)) return [];

    if (!isset($customTags[$handle])) return [];

    if (!is_array($customTags[$handle])) return [];

    return array_values($customTags[$handle]);
  }

  public function getGlobalsTag(): ?string
  {
    $customTags = $this->getCustomTags();
    $handle = $this->getGlobalsHandle();

    if (empty($customTags)) return null;

    if (!isset($customTags[$handle])) return null;

    return $customTags[$handle];
  }

  public function getNavigationTag(): ?string
  {
    $customTags = $this->getCustomTags();
    $handle = $this->getNavigationHandle();

    if (empty($customTags)) return null;

    if (!isset($customTags[$handle])) return null;

    return $customTags[$handle];
  }
}
