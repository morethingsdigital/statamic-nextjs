<?php

namespace Morethingsdigital\StatamicNextjs\Http\Controllers;

use Morethingsdigital\StatamicNextjs\Services\SiteService;
use Morethingsdigital\StatamicNextjs\Services\TagService;
use Statamic\Facades\Collection;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Nav;
use Statamic\Facades\Taxonomy;
use Statamic\Facades\User;
use Statamic\Http\Controllers\CP\CpController;

class IndexController extends CpController
{

    public function __construct(
        private readonly SiteService $siteService,
        private readonly TagService $tagService
    ) {}

    public function index()
    {
        abort_unless(User::current()->can('view nextjs'), 403);

        $title = 'Statamic x Next.js';

        $selectedSite = $this->siteService->currenteSite();

        $collectionTags = $this->tagService->findCollectionTags(selectedSite: $selectedSite->handle());

        $collections = collect($collectionTags)->map(function ($handle) {
            return Collection::findByHandle($handle);
        }) ?? [];

        $globalTags =  $this->tagService->findGlobalsTags(selectedSite: $selectedSite->handle());

        $globals = $globalTags ? GlobalSet::all() : [];

        $navigationTags = $this->tagService->findNavigationTags(selectedSite: $selectedSite->handle());

        $navigations = $navigationTags ? Nav::all() : [];

        $taxonomyTags = $this->tagService->findTaxonomyTags(selectedSite: $selectedSite->handle());

        $taxonomies = $taxonomyTags ? Taxonomy::all() : [];
        // dd($this->tagService->all(selectedSite: $selectedSite->handle()));



        // $routes = [
        //     'import' => [
        //         'create' => cp_route(Personio::NAMESPACE . '.import.create')
        //     ],
        //     'logs' => [
        //         'destroy' => cp_route(Personio::NAMESPACE . '.logs.destroy')
        //     ]
        // ];

        return view('nextjs::index', compact('title', 'collections', 'globals', 'navigations', 'taxonomies'));
    }
}
