<x-guest-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (Auth::check())
            <div class="text-right">
                <a class="btn btn-accent" href="{{ route('site.create') }}">Create</a>
            </div>
            @endif
            @foreach ($sites as $site)
            <div class="card block bg-base-100 text-base-content shadow-md my-2 hover:shadow-xl hover:bg-base-200">
                <a class="card-body" href="{{ route('site.view', ['site' => $site->uuid]) }}">
                    <div class="flex">
                        <div class="basis-1/3">
                            <h2 class="card-title text-3xl">{{ $site->name }}</h2>
                            <div class="">{{ $site->domain }}</div>
                        </div>
                        <div class="basis-2/* w-full">{!! $miniSitesCharts[$loop->index]->container() !!}</div>
                    </div>
                </a>

                @if (Auth::check())
                <!-- The button to open modal -->
                <a href="#modal-{{ $site->id }}" class="btn-ghost p-3 inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear inline" viewBox="0 0 16 16">
                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                    </svg>
                    <span>Get script</span>
                </a>

                <a href="{{ route('site.settings', ['site' => $site->uuid]) }}" class="btn-ghost p-3 inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-code-slash inline" viewBox="0 0 16 16">
                        <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294l4-13zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0zm6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0z"/>
                      </svg>
                    <span>Settings</span>
                </a>

                @push('bottom')
                <div class="modal" id="modal-{{ $site->id }}">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">Script</h3>
                        <p class="mb-2">Include these lines at the end of your <code
                                class="bg-base-200 p-1 rounded text-xs text-base-content">{{ '<body>'}}</code> (or
                            in the {{ '<head>'}}) :</p>
                        <div class="text-base-content bg-base-200 p-2 rounded text-xs">
                            <pre
                                class="whitespace-pre-line">@include('components-site.script-snippet')</pre>
                        </div>
                        <div class="modal-action">
                            <a href="#" class="btn">Close</a>
                        </div>
                    </div>
                </div>
                @endpush
                @endif
            </div>

            @endforeach
        </div>
    </div>

    @push('bottom')
    @apexchartsScripts
    @foreach ($miniSitesCharts as $site)
    {!! $site->script() !!}
    @endforeach
    @endpush

</x-guest-layout>
