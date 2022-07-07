<div class="basis-1/2 self-end">
    @if ($variables->count() > 0)
    <select class="select select-ghost w-full max-w-xs" onchange="location = this.value;">
        <option disabled selected>Filter by variable</option>
        @foreach ($variables as $v)
        <option @if ($variable == $v->variable) selected @endif value="{{ route('site.view', ['site' => $site->uuid, 'period' => $period, 'variable' => $v->variable]) }}">{{ $v->variable }} ({{ $v->total}})</option>
        @endforeach
        <option value="{{ route('site.view', ['site' => $site->uuid, 'period' => $period, 'variable' => null]) }}">Reset</option>
      </select>
    @endif
</div>
<div class="basis-1/2 justify-end">
    <div class="flex-col">
        <div class="text-end">
            <div class="dropdown relative">
                <button class="dropdown-toggle btn btn-accent flex items-center whitespace-nowrap" type="button"
                    id="dropdown-period" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ data_get($periods[$period], 'label') }}
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-down" class="w-2 ml-2"
                        role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path fill="currentColor"
                            d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z">
                        </path>
                    </svg>
                </button>
                <ul class="dropdown-menu min-w-max absolute hidden bg-base-100 text-base-content z-50 float-left py-2 list-none text-left rounded-lg shadow-lg mt-1 hidden m-0 bg-clip-padding border-none"
                    aria-labelledby="dropdown-period">
                    @foreach ($periods as $key => $p)
                    <a href="{{ route('site.view', ['site' => $site->uuid, 'period' => $key, 'variable' => $variable]) }}"
                        class="dropdown-item text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent hover:bg-gray-100">{{ data_get($p, 'label') }}</a>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="text-end text-sm mt-1">From:
            {{ data_get(config('recolus.periods.'.$period), 'start')->isoFormat('L') }}, to
            {{ data_get(config('recolus.periods.'.$period), 'end')->isoFormat('L') }}</div>
    </div>
</div>
