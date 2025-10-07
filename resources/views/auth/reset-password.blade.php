<x-guest-layout>
    <div class="p-6 lg:p-8 flex flex-col items-center justify-center min-h-screen"
         style="background: linear-gradient(135deg, #E0F7FA, #F0F4FF);">

        <div class="w-full max-w-lg bg-white p-8 md:p-10 rounded-xl shadow-2xl">

            <div class="mb-8 text-center">
                <div class="text-3xl font-bold text-primary-blue mb-2">
                    <span class="text-lg mr-1">::</span>Medic
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900">
                    Atur Ulang Kata Sandi
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Masukkan kata sandi baru untuk akun Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-900 dark:text-gray-900" />
                    <x-text-input id="email" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Kata Sandi Baru')" class="text-gray-900 dark:text-gray-900" />
                    <x-text-input id="password" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-gray-900 dark:text-gray-900" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="w-full px-5 py-2 text-white bg-primary-blue hover:bg-blue-700 rounded-lg text-base font-bold transition duration-150 shadow-lg">
                        {{ __('Atur Ulang Kata Sandi') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
