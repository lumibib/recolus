<div class="shadow-sm bg-white rounded-lg overflow-hidden">
    <div class="px-4 sm:px-6 py-5">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Sources</h3>
    </div>
    <div class="px-4 sm:px-6 py-3 flex justify-between bg-gray-50 border-t border-b border-gray-200 text-xs font-medium leading-4 tracking-wider text-gray-600 uppercase">
        <div>Source</div>
        <div>Total</div>
    </div>
    <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
        @foreach ($sources as $source)
            <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                <div class="pr-5 text-sm leading-5 text-gray-800 truncate w-full">
                    @include('components-site.percentage-progress', ['pct' => $source->percentage])
                    <div class="flex items-center">
                        @if ($source->source)
                        <img class="w-4 h-4 mr-3" src="https://www.google.com/s2/favicons?domain={{ urlencode($source->source) }}&sz=128" alt="" />
                        <a href="{{ $source->page }}" target="_blank" class="hover:underline">
                            {{ $source->source ?? 'Direct' }}
                        </a>
                        @else
                        <div class="w-4 h-4 mr-3"></div>
                        Direct
                        @endif
                    </div>
                </div>
                <div class="text-sm leading-5 text-gray-600">{{ $source->total }}</div>
            </div>
        @endforeach
    </div>
</div>
