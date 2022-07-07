<x-guest-layout>

    <div class="min-h-screen bg-base-200 text-base-content py-6 flex flex-col sm:py-16">

        <div class="px-4 w-full lg:px-0 sm:max-w-5xl sm:mx-auto">
            <h1 class="font-bold text-3xl leading-tight">
                {{ $site->name }}
            </h1>

            <div class="w-full">
                @if ($errors->any())
                <div class="alert alert-error shadow-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('site.update', ['site' => $site->id]) }}" method="post">
                    @csrf
                    <div class="form-control w-full max-w-lg my-2">
                        <label class="label">
                            <span class="label-text">Name</span>
                        </label>
                        <input name="name" type="text" placeholder="Name" value="{{ old('name') ?? $site->name }}"
                            class="input input-bordered w-full max-w-lg" />
                    </div>
                    <div class="form-control w-full max-w-lg my-2">
                        <label class="label">
                            <span class="label-text">Domain</span>
                        </label>
                        <input name="domain" type="text" placeholder="Domain" value="{{ old('domain') ?? $site->domain }}"
                            class="input input-bordered w-full max-w-lg" />
                    </div>
                    <div class="form-control w-full max-w-lg my-2">
                        <label class="label">
                            <span class="label-text">Public</span>
                        </label>
                        <input name="public" type="checkbox" value="1" {{ old('public') || $site->public ? 'checked' : '' }} class="checkbox" />
                        <label class="label">
                            <span class="label-text">Public list</span>
                        </label>
                        <input name="public_list" type="checkbox" value="1" {{ old('public_list') || $site->public_list ? 'checked' : '' }} class="checkbox" />
                    </div>
                    <div class="form-control w-full max-w-lg my-2">
                        <label class="label">
                            <span class="label-text">Domain whitelist</span>
                        </label>
                        <input name="domain_whitelist" type="text" placeholder="e.g. : example.com,app.example.com" value="{{ old('domain_whitelist') ??$site->domain_whitelist }}"
                            class="input input-bordered w-full max-w-lg" />
                        <label class="label">
                            <span class="label-text">e.g. : example.com,app.example.com</span>
                        </label>
                    </div>
                    <div class="form-control w-full max-w-lg my-2">
                        <label class="label">
                            <span class="label-text">Ignore paths</span>
                        </label>
                        <input name="ignore_paths" type="text" placeholder="e.g. : /contact,/tags,/posts/*" value="{{ old('ignore_paths') ??$site->ignore_paths }}"
                            class="input input-bordered w-full max-w-lg" />
                            <label class="label">
                                <span class="label-text">e.g. : /contact,/tags,/posts/*</span>
                            </label>
                    </div>
                    <input type="submit" value="Save" class="btn btn-primary my-2">
                </form>
            </div>

        </div>
    </div>

    @push('head')
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.3/css/pro.min.css"/>
    @endpush
</x-guest-layout>
