<?php

namespace Morethingsdigital\StatamicNextjs\Services;

class TagService
{

    public function __construct() {}

    private function getCustomTags()
    {
        return config('statamic.nextjs.custom_tag_revalidation');
    }

    public function getCollectionsHandle()
    {
        return 'collections';
    }

    public function getGlobalsHandle()
    {
        return 'globals';
    }

    public function getNavigationHandle()
    {
        return 'navigation';
    }

    public function getTaxonomiesHandle()
    {
        return 'taxonomies';
    }

    public function getAssetsHandle()
    {
        return 'assets';
    }

    public function all(string $selectedSite): array
    {
        return collect(array_merge(
            $this->findCollectionTags(selectedSite: $selectedSite),
            $this->findTaxonomyTags(selectedSite: $selectedSite),
            $this->findGlobalsTags(selectedSite: $selectedSite),
            $this->findNavigationTags(selectedSite: $selectedSite),
        ))->unique()->toArray();
    }

    public function findCollectionTagsByHandle(string $selectedSite, string $collection): array
    {
        $customTags = $this->getCustomTags();
        $handle = $this->getCollectionsHandle();

        return $customTags[$selectedSite][$handle][$collection] ?? [];
    }

    public function findTaxonomyTagsByHandle(string $selectedSite, string $taxonomie): array
    {
        $customTags = $this->getCustomTags();
        $handle = $this->getCollectionsHandle()();

        return $customTags[$selectedSite][$handle][$taxonomie] ?? [];
    }

    public function findCollectionTags(string $selectedSite): array
    {
        $customTags = $this->getCustomTags();
        $handle = $this->getCollectionsHandle();

        return collect($customTags[$selectedSite][$handle] ?? [])->flatten()->unique()->toArray() ?? [];
    }

    public function findTaxonomyTags(string $selectedSite): array
    {
        $customTags = $this->getCustomTags();
        $handle = $this->getTaxonomiesHandle();

        return array_values($customTags[$selectedSite][$handle] ?? []) ?? [];
    }

    public function findGlobalsTags(string $selectedSite): array
    {
        $customTags = $this->getCustomTags();
        $handle = $this->getGlobalsHandle();

        return $customTags[$selectedSite][$handle] ?? [];
    }

    public function findNavigationTags(string $selectedSite): array
    {
        $customTags = $this->getCustomTags();
        $handle = $this->getNavigationHandle();

        return $customTags[$selectedSite][$handle];
    }

    public function findAssetTags(string $selectedSite): array
    {
        $customTags = $this->getCustomTags();
        $handle = $this->getAssetsHandle();

        return $customTags[$selectedSite][$handle];
    }
}
