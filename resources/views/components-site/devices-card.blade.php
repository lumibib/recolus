<div class="shadow-sm bg-white rounded-lg overflow-hidden">
    <div class="px-4 sm:px-6 py-5">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Devices</h3>
    </div>
    <div class="px-4 sm:px-6 py-3 flex justify-between bg-gray-50 border-t border-b border-gray-200 text-xs font-medium leading-4 tracking-wider text-gray-600 uppercase">
        <div>Type</div>
        <div>Total</div>
    </div>
    <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
        @foreach ($devices as $device)
            <div class="px-4 sm:px-6 py-3 flex justify-between hover:bg-gray-50">
                <div class="pr-5 text-sm leading-5 text-gray-800 truncate w-full">
                    @include('components-site.percentage-progress', ['pct' => $device->percentage])
                    <div class="flex items-center">
                        <i class="far fa-{{ str($device->device)->lower() }} w-4 mr-3"></i>
                        {{ $device->device }}
                    </div>
                </div>
                <div class="text-sm leading-5 text-gray-600">{{ $device->total }}</div>
            </div>
        @endforeach
    </div>
</div>
