<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-6 text-center">
            <h4 class="text-lg font-bold text-gray-700">LOG MASUK PENGGUNA</h4>
            <p class="text-sm text-gray-500">Sila masukkan maklumat anda untuk meneruskan.</p>
        </div>

        <div>
            <x-input-label for="email" value="Alamat Emel" class="font-bold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-50 border border-gray-300 focus:border-[#003366] focus:ring-[#003366]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Kata Laluan" class="font-bold text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full bg-gray-50 border border-gray-300 focus:border-[#003366] focus:ring-[#003366]"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#003366] shadow-sm focus:ring-[#003366]" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-[#003366] hover:underline" href="{{ route('password.request') }}">
                    Lupa kata laluan?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#003366] hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#003366] transition ease-in-out duration-150">
                LOG MASUK
            </button>
        </div>
        
        <div class="mt-4 text-center">
            <a class="text-sm text-gray-500 hover:text-gray-900" href="{{ route('register') }}">
                Belum ada akaun? Daftar di sini.
            </a>
        </div>
    </form>
</x-guest-layout>