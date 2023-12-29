<?php

return [
  'enabled' => env('STATAMIC_NEXTJS_ENABLED', true),
  'revalidation_urls' => env('STATAMIC_NEXTJS_REVALIDATION_URLS', []),
  'revalidation_secret' => env('STATAMIC_NEXTJS_REVALIDATION_SECRET', 'secret'),
  'revalidation_type' => env('STATAMOC_NEXTJS_REVALIDATION_TYPE', 'tag'), // tag or path
  'custom_tag_revalidation' => [
    'collections' => [
      'page' => 'pages'
      // '<collection>' => '<tag>'
    ],
    'globals' => 'globals',
    'navigation' => 'navigation',
    'taxonomies' => null
  ]
];


// Default Tag Revalidations
// [
//   'collection' => [
//     'pages' => 'pages',
//     '*' => '*'
//   ],
//   'globals' => 'globals',
//   'navigation' => 'navigation'
// ]


// TBD:

// Default Path Revalidations
// [
//   'collection' => [
//     'pages' => ['/:handle'],
//     '*' => ['/*/:handle']
//   ],
// ]