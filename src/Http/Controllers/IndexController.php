<?php

namespace Morethingsdigital\StatamicNextjs\Http\Controllers;

use Statamic\Facades\User;
use Statamic\Http\Controllers\CP\CpController;

class IndexController extends CpController
{

    public function __construct()
    {
    }

    public function index()
    {
        abort_unless(User::current()->can('view nextjs'), 403);

        // $personioLogs = User::current()->can('view logs personio') ? $this->logService->find() : [];

        $title = 'Statamic x Next.js';

        // $routes = [
        //     'import' => [
        //         'create' => cp_route(Personio::NAMESPACE . '.import.create')
        //     ],
        //     'logs' => [
        //         'destroy' => cp_route(Personio::NAMESPACE . '.logs.destroy')
        //     ]
        // ];

        return view('nextjs::index', compact('title'));
    }
}
