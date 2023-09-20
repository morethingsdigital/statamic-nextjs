<?php

return [
  'enabled' => env('STATAMIC_NEXTJS_ENABLED', true),
  'revalidation_url' => env('STATAMIC_NEXTJS_REVALIDATION_URL', 'http://localhost:3000/api/revalidate'),
  'revalidation_secret' => env('STATAMIC_NEXTJS_REVALIDATION_SECRET', 'secret'),
  'revalidation_type' => env('STATAMOC_NEXTJS_REVALIDATION_TYPE', 'tag'), // tag or path
  'custom_path_patterns' => [
    'collection' => null,
    'globals' => null,
    'navigation' => null,
    'taxonomies' => null,
    'all' =>  null
  ],
  'custom_tag_revalidation' => [
    'collection' => null,
    'globals' => null,
    'navigation' => null,
    'taxonomies' => null,
    'all' =>  null 
  ]
];


// Default Path Revalidations
// [
//   'collection' => [
//     'pages' => ['/:handle'],
//     '*' => ['/*/:handle']
//   ],
// ]

// Default Tag Revalidations
// [
//   'collection' => [
//     'pages' => 'pages',
//     '*' => '*'
//   ],
//   'globals' => 'globals',
//   'navigation' => 'navigation',
//   'all' => [
//     'pages',
//     '*',
//     'globals',
//     'navigation'
//   ]
// ]