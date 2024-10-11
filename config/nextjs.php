<?php

return [
    'enabled' => env('STATAMIC_NEXTJS_ENABLED', true),
    'revalidation_urls' => [
        'default' => 'http://localhost:3000/api/revalidation',
        // 'site-handle-two' => env('FRONTEND_URL_K16') . "/api/revalidation",
        // ... add more site handle revalidation urls here
    ],
    'revalidation_secret' => env('STATAMIC_NEXTJS_REVALIDATION_SECRET', 'secret'),
    'revalidation_type' => env('STATAMOC_NEXTJS_REVALIDATION_TYPE', 'tag'), // tag or path
    'custom_tag_revalidation' => [
        'default' => [
            'collections' => [
                'pages' => ['pages'],
                // 'news' => ['news'],
                // ... add more collections here
            ],
            'globals' => ['globals'],
            'navigation' => ['navigation'],
            'taxonomies' => [
                // 'categories' => ['categories'],
                // ... add more taxonomies here
            ],
            'assets' => ['assets'],
        ],
        // 'site-handle-two' => [
        //     'collections' => [
        //         'pages' => ['pages'],
        //     ],
        //     'globals' => ['globals'],
        //     'navigation' => ['navigation'],
        //     'taxonomies' => [],
        //     'assets' => ['assets'],
        // ],
        // ... add more site handle tag revalidations here
    ],
];
