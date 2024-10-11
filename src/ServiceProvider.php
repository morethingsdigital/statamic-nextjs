<?php

namespace Morethingsdigital\StatamicNextjs;

use Illuminate\Support\Facades\Blade;
use Morethingsdigital\StatamicNextjs\Listeners\RevalidationTagByAssetDeleted;
use Morethingsdigital\StatamicNextjs\Listeners\RevalidationTagByAssetSaved;
use Morethingsdigital\StatamicNextjs\Listeners\RevalidationTagByEntryCreated;
use Morethingsdigital\StatamicNextjs\Listeners\RevalidationTagByEntryDeleted;
use Morethingsdigital\StatamicNextjs\Listeners\RevalidationTagByEntrySaved;
use Morethingsdigital\StatamicNextjs\Listeners\RevalidationTagByGlobalSetSaved;
use Morethingsdigital\StatamicNextjs\Listeners\RevalidationTagByNavTreeSaved;
use Statamic\Events\AssetDeleted;
use Statamic\Events\AssetSaved;
use Statamic\Events\EntryCreated;
use Statamic\Events\EntryDeleted;
use Statamic\Events\EntrySaved;
use Statamic\Events\GlobalSetSaved;
use Statamic\Events\NavTreeSaved;
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

    protected $viewNamespace = 'nextjs';

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
        // GlobalSetSaved::class => [
        //     RevalidationTagByGlobalSetSaved::class
        // ],
        // NavTreeSaved::class => [
        //     RevalidationTagByNavTreeSaved::class
        // ],
        // AssetSaved::class => [
        //     RevalidationTagByAssetSaved::class
        // ],
        // AssetDeleted::class => [
        //     RevalidationTagByAssetDeleted::class
        // ],
    ];

    public function bootAddon()
    {
        $this->registerAddonConfig();
        $this->bootPermissions();
        $this->bootNavigation();
        $this->registerBladeComponents();
        $this->bootCommands();
    }

    protected function bootNavigation()
    {
        Nav::extend(function ($nav) {
            $icon = File::disk()->get(__DIR__ . '/../resources/svg/nextjs.svg') ?? '';

            $nav
                ->create('Next.js')
                ->route('nextjs.index')
                ->section('Tools')
                ->icon($icon);
        });
    }

    protected function bootPermissions()
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
    }

    protected function bootCommands()
    {
        $this->commands([]);

        return $this;
    }

    protected function registerBladeComponents()
    {
        Blade::componentNamespace('Morethingsdigital\\StatamicNextjs\\View\\Components', 'nextjs');
    }

    protected function registerAddonConfig()
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/nextjs.php', 'statamic.nextjs');

        $this->publishes([
            __DIR__ . '/../config/nextjs.php' => config_path('statamic/nextjs.php'),
        ], 'statamic-nextjs-config');
    }
}
