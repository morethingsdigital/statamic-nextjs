@extends('statamic::layout')
@section('title', 'Next.js')

@section('content')

    <header class="w-full h-auto flex flex-row flex-nowrap gap-8 mb-6">
        <h1 class="w-full">{{ $title }}</h1>

        {{-- <div class="flex flex-row gap-4 ml-auto mr-0">
            {{ $buttons }}
        </div> --}}
    </header>

    {{-- <x-vercel-statamic::navigation /> --}}

    <main>
        Hallo
    </main>



    @include('statamic::partials.docs-callout', [
        'topic' => 'Statamic x Next.js',
        'url' => 'https://statamic-nextjs.morethings.digital/docs',
    ])

@endsection
