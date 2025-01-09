<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Preferences') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile preferences.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.preferences') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="ville" :value="__('Ville')" />
            <x-text-input id="ville" name="ville" type="text" class="mt-1 block w-full" :value="old('ville', $user->ville)" required autofocus autocomplete="ville" />
            <x-input-error class="mt-2" :messages="$errors->get('ville')" />
        </div>

        <div>
            <x-input-label for="carburant_pref" :value="_('Carburant_pref')"/>
            <select id="carburant_pref" name="carburant_pref" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option>Choisissez un carburant</option>
                <option value="1" {{ old('carburant_pref', $user->carburant_pref) == 1 ? 'selected' : '' }}>Gazole</option>
                <option value="2" {{ old('carburant_pref', $user->carburant_pref) == 2 ? 'selected' : '' }}>SP95</option>
                <option value="3" {{ old('carburant_pref', $user->carburant_pref) == 3 ? 'selected' : '' }}>E10</option>
                <option value="4" {{ old('carburant_pref', $user->carburant_pref) == 4 ? 'selected' : '' }}>SP98</option>
                <option value="5" {{ old('carburant_pref', $user->carburant_pref) == 5 ? 'selected' : '' }}>GPLc</option>
                <option value="6" {{ old('carburant_pref', $user->carburant_pref) == 6 ? 'selected' : '' }}>E85</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('carburant_pref')" />
        </div>


       
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
