@extends('statamic::layout')
@section('title', 'Next.js')

@section('content')

    <header class="sn-w-full sn-h-auto sn-flex sn-flex-row sn-flex-nowrap sn-gap-8 sn-mb-6">
        <h1 class="sn-w-full sn-text-2xl sn-text-black sn-font-black">{{ $title }}</h1>
    </header>

    <main class="sn-flex sn-flex-col sn-gap-10">
        @if (count($collections) > 0)
            <div class="card sn-p-0 sn-flex sn-flex-col sn-rounded sn-overflow-hidden">
                <h2 class="sn-text-xl sn-font-bold sn-text-black sn-px-4 sn-py-2 sn-bg-slate-100">{{ __('Collections') }}
                </h2>
                <div class="sn-flex sn-flex-col">
                    @foreach ($collections as $collection)
                        <div
                            class="sn-grid sn-grid-cols-12 sn-gap-4 sn-bg-white sn-px-4 sn-py-2 sn-items-center border-b sn-border-slate-300 last:sn-border-none">
                            <div class="sn-col-span-full md:sn-col-span-9">{{ $collection->title }}</div>
                            <div class="sn-col-span-full md:sn-col-span-3">
                                <form class="sn-w-full sn-h-auto sn-flex sn-justify-end" method="post"
                                    action="{{ route('statamic.cp.nextjs.invalidate') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="collections">
                                    <input type="hidden" name="handle" value="{{ $collection->handle }}">
                                    <button type="submit" class="btn btn-flat">Invalidate</a>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- <hr class="sn-border-black"> --}}
        @if (count($navigations) > 0)
            <div class="card sn-p-0 sn-flex sn-flex-col sn-rounded sn-overflow-hidden">
                <h2 class="sn-text-xl sn-font-bold sn-text-black sn-px-4 sn-py-2 sn-bg-slate-100">{{ __('Navigation') }}
                </h2>
                <div class="sn-flex sn-flex-col">
                    @foreach ($navigations as $navigation)
                        <div
                            class="sn-grid sn-grid-cols-12 sn-gap-4 sn-bg-white sn-px-4 sn-py-2 sn-items-center border-b sn-border-slate-300 last:sn-border-none">
                            <div class="sn-col-span-full md:sn-col-span-9">{{ $navigation->title() }}</div>
                            <div class="sn-col-span-full md:sn-col-span-3">
                                <form class="sn-w-full sn-h-auto sn-flex sn-justify-end" method="post"
                                    action="{{ route('statamic.cp.nextjs.invalidate') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="navigation">
                                    <button type="submit" class="btn btn-flat">Invalidate</a>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @if (count($globals) > 0)
            <div class="card sn-p-0 sn-flex sn-flex-col sn-rounded sn-overflow-hidden">
                <h2 class="sn-text-xl sn-font-bold sn-text-black sn-px-4 sn-py-2 sn-bg-slate-100">{{ __('Globals') }}</h2>
                <div class="sn-flex sn-flex-col">
                    @foreach ($globals as $global)
                        <div
                            class="sn-grid sn-grid-cols-12 sn-gap-4 sn-bg-white sn-px-4 sn-py-2 sn-items-center border-b sn-border-slate-300 last:sn-border-none">
                            <div class="sn-col-span-full md:sn-col-span-9">{{ $global->title() }}</div>
                            <div class="sn-col-span-full md:sn-col-span-3">
                                <form class="sn-w-full sn-h-auto sn-flex sn-justify-end" method="post"
                                    action="{{ route('statamic.cp.nextjs.invalidate') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="globals">
                                    <button type="submit" class="btn btn-flat">Invalidate</a>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @if (count($taxonomies) > 0)
            <div class="card sn-p-0 sn-flex sn-flex-col sn-rounded sn-overflow-hidden">
                <h2 class="sn-text-xl sn-font-bold sn-text-black sn-px-4 sn-py-2 sn-bg-slate-100">{{ __('Taxonomies') }}
                </h2>
                <div class="sn-flex sn-flex-col">
                    @foreach ($taxonomies as $taxonomy)
                        <div
                            class="sn-grid sn-grid-cols-12 sn-gap-4 sn-bg-white sn-px-4 sn-py-2 sn-items-center border-b sn-border-slate-300 last:sn-border-none">
                            <div class="sn-col-span-full md:sn-col-span-9">{{ $taxonomy->title() }}</div>
                            <di <form class="sn-w-full sn-h-auto sn-flex sn-justify-end" method="post"
                                action="{{ route('statamic.cp.nextjs.invalidate') }}">
                                @csrf
                                <input type="hidden" name="type" value="taxonomies">
                                <input type="hidden" name="handle" value="{{ $taxonomy->handle }}">
                                <button type="submit" class="btn btn-flat">Invalidate</a>
                                    </form>
                        </div>
                </div>
        @endforeach
        </div>
        </div>
        @endif
    </main>



    {{-- @include('statamic::partials.docs-callout', [
        'topic' => 'Statamic x Next.js',
        'url' => 'https://statamic-nextjs.morethings.digital/docs',
    ]) --}}

@endsection
