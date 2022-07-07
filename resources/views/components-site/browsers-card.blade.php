<div class="shadow-sm bg-white rounded-lg overflow-hidden">
    <div class="px-4 sm:px-6 py-5">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Browsers</h3>
    </div>
    <div class="px-4 sm:px-6 py-3 flex justify-between bg-gray-50 border-t border-b border-gray-200 text-xs font-medium leading-4 tracking-wider text-gray-600 uppercase">
        <div>Browser</div>
        <div>Total</div>
    </div>
    <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
        @foreach ($browsers as $browser)
            <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                <div class="pr-5 text-sm leading-5 text-gray-800 truncate w-full">
                    @include('components-site.percentage-progress', ['pct' => $browser->percentage])
                    <div class="flex items-center">
                        <img class="w-4 mr-3" src="https://cdn.statically.io/gh/moneesh2011/browser-icons/62e89d8b/icons/32/{{ str($browser->browser)->lower() }}-32.png" alt="" />
                        {{ $browser->browser }}
                    </div>
                </div>
                <div class="text-sm leading-5 text-gray-600">{{ $browser->total }}</div>
            </div>
        @endforeach
    </div>
</div>
