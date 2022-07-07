<x-guest-layout>

    <div class="min-h-screen bg-base-200 text-base-content py-6 flex flex-col sm:py-16">

        <div class="px-4 w-full lg:px-0 sm:max-w-5xl sm:mx-auto">
            <h1 class="font-bold text-2xl leading-tight">
                {{ $site->name }}
                <span class="dropdown">
                    <label tabindex="0" class="btn btn-sm btn-link underline">Change site</label>
                    <ul tabindex="0" class="dropdown-content menu p-1 shadow bg-base-100 w-52">
                        @foreach (App\Models\Site::all() as $item)
                        <li><a href="{{ route('site.view', ['site' => $item->uuid, 'period' => $period]) }}">{{ $item->name }}</a></li>
                        @endforeach
                    </ul>
                </span>
            </h1>

            <div class="flex flex-row">
                @include('components-site.filter', ['site' => $site])
            </div>
            <div class="mt-4 grid grid-cols-1 gap-4">
                <div class="stats stats-vertical md:stats-horizontal shadow bg-white text-gray-900">
                @each('components-site.stats-card', $stats, 'stat')
                </div>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-1">
                {!! $pagesChart->container() !!}
            </div>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                @include('components-site.pages-card')
                @include('components-site.sources-card')
                @include('components-site.browsers-card')
                @include('components-site.platforms-card')
                @include('components-site.devices-card')
                @include('components-site.countries-card')
                @include('components-site.languages-card')
            </div>
        </div>
    </div>

    @push('bottom')
    @apexchartsScripts
    {!! $pagesChart->script() !!}
    @endpush

    @push('head')
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.3/css/pro.min.css"/>
    @endpush
</x-guest-layout>
