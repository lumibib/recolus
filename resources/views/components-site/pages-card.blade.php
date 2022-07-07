<div class="shadow-sm bg-white rounded-lg overflow-hidden">
    <div class="px-4 sm:px-6 py-5">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Pages</h3>
    </div>
    <div class="px-4 sm:px-6 py-3 flex justify-between bg-gray-50 border-t border-b border-gray-200 text-xs font-medium leading-4 tracking-wider text-gray-600 uppercase">
        <div>Page</div>
        <div>Total</div>
    </div>
    <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
        @foreach ($pages as $page)
            <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                <div class="pr-5 text-sm leading-5 text-gray-800 truncate w-full">
                    @include('components-site.percentage-progress', ['pct' => $page->percentage])
                    <a href="{{ $page->url }}" target="_blank" class="hover:underline">
                        {{ $page->path }}
                    </a>
                    <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $page->rid }}" aria-expanded="false" aria-controls="collapse-{{ $page->rid }}"> - {{ $page->title }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down inline h3" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                          </svg>
                    </span>
                    <span>
                    </span>
                    <div class="collapse" id="collapse-{{ $page->rid }}">
                        <div class="block text-xs p-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-hourglass-split inline h-3" viewBox="0 0 16 16">
                                <path d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2h-7zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48V8.35zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z"/>
                              </svg>
                              Avarage duration : {{ number_format($page->avg_duration, 0) }}s
                              <span class="mx-1"></span>
                              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-bar-down inline h-3" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"/>
                              </svg>
                              Avarage scroll percent : {{ number_format($page->avg_scroll, 0) }}%
                        </div>
                      </div>
                </div>
                <div class="text-sm leading-5 text-gray-600">{{ $page->total }}</div>
            </div>
        @endforeach
    </div>
</div>
