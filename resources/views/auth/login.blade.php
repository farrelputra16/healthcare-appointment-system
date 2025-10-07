<x-guest-layout>
    <div class="p-6 lg:p-8 flex flex-col items-center justify-center min-h-screen"
         style="background: linear-gradient(135deg, #E0F7FA, #F0F4FF);">

        <div class="w-full max-w-lg bg-white p-8 md:p-10 rounded-xl shadow-2xl">

            <div class="mb-8 text-center">
                <div class="text-3xl font-bold text-primary-blue mb-2">
                    <span class="text-lg mr-1">::</span>Medic
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900">
                    Masuk ke Akun Anda
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Silakan masukkan kredensial untuk melanjutkan.
                </p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-900 dark:text-gray-900" />
                    <x-text-input id="email" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-900 dark:text-gray-900" />

                    <x-text-input id="password" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-blue shadow-sm focus:ring-primary-blue" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-primary-blue" href="{{ route('password.request') }}">
                            {{ __('Lupa Password?') }}
                        </a>
                    @endif

                    <button type="submit" class="ms-3 px-5 py-2 text-white bg-primary-blue hover:bg-blue-700 rounded-lg text-base font-bold transition duration-150 shadow-lg">
                        {{ __('Masuk') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
