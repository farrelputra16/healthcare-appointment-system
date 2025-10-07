<x-guest-layout>
    <div class="p-6 lg:p-8 flex flex-col items-center justify-center min-h-screen"
         style="background: linear-gradient(135deg, #E0F7FA, #F0F4FF);">

        <div class="w-full max-w-lg bg-white p-8 md:p-10 rounded-xl shadow-2xl">

            <div class="mb-8 text-center">
                <div class="text-3xl font-bold text-primary-blue mb-2">
                    <span class="text-lg mr-1">::</span>Medic
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900">
                    Konfirmasi Akses
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Area ini aman. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.
                </p>
            </div>

            <div class="mb-4 text-sm text-gray-700">
                {{ __('Ini adalah area aman aplikasi. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div>
                    <x-input-label for="password" :value="__('Kata Sandi')" class="text-gray-900 dark:text-gray-900" />

                    <x-text-input id="password" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="w-full px-5 py-2 text-white bg-primary-blue hover:bg-blue-700 rounded-lg text-base font-bold transition duration-150 shadow-lg">
                        {{ __('Konfirmasi') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
