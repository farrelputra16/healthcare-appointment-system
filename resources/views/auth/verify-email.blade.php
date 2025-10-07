<x-guest-layout>
    <div class="p-6 lg:p-8 flex flex-col items-center justify-center min-h-screen"
         style="background: linear-gradient(135deg, #E0F7FA, #F0F4FF);">

        <div class="w-full max-w-lg bg-white p-8 md:p-10 rounded-xl shadow-2xl">

            <div class="mb-8 text-center">
                <div class="text-3xl font-bold text-primary-blue mb-2">
                    <span class="text-lg mr-1">::</span>Medic
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900">
                    Verifikasi Email
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Langkah terakhir untuk mengaktifkan akun Anda.
                </p>
            </div>

            <div class="mb-4 text-sm text-gray-700">
                {{ __('Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan melalui email? Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan yang lain.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between">

                <form method="POST" action="{{ route('verification.send') }}" class="w-2/3">
                    @csrf

                    <button type="submit" class="w-full px-5 py-2 text-white bg-primary-blue hover:bg-blue-700 rounded-lg text-base font-bold transition duration-150 shadow-lg">
                        {{ __('Kirim Ulang Email Verifikasi') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="underline text-sm text-gray-600 hover:text-primary-blue rounded-md focus:outline-none focus:ring-0">
                        {{ __('Keluar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
