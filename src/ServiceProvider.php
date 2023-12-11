<?php

namespace Morethingsdigital\StatamicNextjs;

use Illuminate\Support\Facades\Blade;
use Statamic\Facades\File;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $vite = [
        'input' => [
            'resources/js/addon.js',
            'resources/css/addon.css'
        ],
        'publicDirectory' => 'resources/dist',
    ];

    protected $routes = [
        'cp' => __DIR__ . '/../routes/cp.php'
    ];

    protected $listen = [
        // EntryCreated::class => [
        //     RevalidationTagByEntryCreated::class
        // ],
        // EntrySaved::class => [
        //     RevalidationTagByEntrySaved::class
        // ],
        // EntryDeleted::class => [
        //     RevalidationTagByEntryDeleted::class
        // ],
        // NavTreeSaved::class => [
        //     RevalidationTagByNavTreeSaved::class
        // ],
        // NavDeleted::class => [
        //     RevalidationTagByNavDeleted::class
        // ],
        // GlobalSetSaved::class => [
        //     RevalidationTagByGlobalSetSaved::class
        // ],
        // GlobalSetDeleted::class => [
        //     RevalidationTagByGlobalSetDeleted::class
        // ],
        // PurgeCache::class => [
        //     RevalidateAllTags::class
        // ]
    ];

    protected $viewNamespace = 'nextjs';

    public function bootAddon()
    {

        if (!config('nextjs.enabled')) {
            return;
        }

        $this
            ->bootPermissions()
            ->bootNavigation()
            ->bootCommands()
            ->bootPublishables()
            ->registerBladeComponents();
    }

    protected function bootNavigation(): self
    {
        Nav::extend(function ($nav) {
            $icon = File::disk()->get(__DIR__ . '/../resources/svg/nextjs.svg') ?? '';

            $nav
                ->create('Next.js')
                ->route('nextjs.index')
                ->section('Tools')
                ->icon($icon);
        });

        return $this;
    }

    protected function bootPermissions(): self
    {
        Permission::group('nextjs', 'Next.js', function () {
            Permission::register('view nextjs', function ($permission) {
                $permission
                    ->label('View Next.js')
                    ->children([
                        Permission::make('invalidate nextjs')
                            ->label('Invalidierung von Tags')
                            ->children([
                                Permission::make('invalidate tag nextjs')->label('Invalidierung einzelner Tags'),
                                Permission::make('invalidate all nextjs')->label('Invalidierung aller Tags'),
                            ]),
                    ]);
            });
        });

        return $this;
    }

    protected function bootCommands()
    {
        $this->commands([
            // 
        ]);

        return $this;
    }

    protected function registerBladeComponents(): self
    {
        Blade::componentNamespace('Morethingsdigital\\StatamicNextjs\\View\\Components', 'statamic-nextjs');

        return $this;
    }

    protected function bootPublishables(): ServiceProvider
	{
		parent::bootPublishables();

		// $this->publishes([
        //     //
        // ], '');

		return $this;
	}
}
