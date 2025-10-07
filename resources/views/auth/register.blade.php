<x-guest-layout>
    <div class="p-6 lg:p-8 flex flex-col items-center justify-center min-h-screen"
         style="background: linear-gradient(135deg, #E0F7FA, #F0F4FF);">

        <div class="w-full max-w-lg bg-white p-8 md:p-10 rounded-xl shadow-2xl">

            <div class="mb-8 text-center">
                <div class="text-3xl font-bold text-primary-blue mb-2">
                    <span class="text-lg mr-1">::</span>Medic
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900">
                    Daftar Akun Baru
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Ambil langkah pertama menuju manajemen kesehatan yang lebih baik.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div>
                    {{-- FIX: Tambahkan text-gray-900 dark:text-gray-900 --}}
                    <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-900 dark:text-gray-900" />
                    <x-text-input id="name" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    {{-- FIX: Tambahkan text-gray-900 dark:text-gray-900 --}}
                    <x-input-label for="email" :value="__('Alamat Email')" class="text-gray-900 dark:text-gray-900" />
                    <x-text-input id="email" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    {{-- FIX: Tambahkan text-gray-900 dark:text-gray-900 --}}
                    <x-input-label for="role_id" :value="__('Pilih Peran')" class="text-gray-900 dark:text-gray-900" />
                    <select id="role_id" name="role_id" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue rounded-md shadow-sm">
                        {{-- Menggunakan display_name untuk tampilan --}}
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                </div>

                <div class="mt-4">
                    {{-- FIX: Tambahkan text-gray-900 dark:text-gray-900 --}}
                    <x-input-label for="password" :value="__('Password')" class="text-gray-900 dark:text-gray-900" />
                    <x-text-input id="password" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    {{-- FIX: Tambahkan text-gray-900 dark:text-gray-900 --}}
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-900 dark:text-gray-900" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full bg-white text-gray-900 dark:bg-white dark:text-gray-900 border-gray-300 focus:border-primary-blue focus:ring-primary-blue"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a class="underline text-sm text-gray-600 hover:text-primary-blue" href="{{ route('login') }}">
                        {{ __('Sudah punya akun? Masuk') }}
                    </a>

                    <button type="submit" class="ms-4 px-5 py-2 text-white bg-primary-blue hover:bg-blue-700 rounded-lg text-base font-bold transition duration-150 shadow-lg">
                        {{ __('Daftar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
