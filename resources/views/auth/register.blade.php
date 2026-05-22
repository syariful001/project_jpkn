<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-6 text-center">
            <h4 class="text-lg font-bold text-gray-700">PENDAFTARAN PENGGUNA BARU</h4>
            <p class="text-sm text-gray-500">Sila isi maklumat di bawah untuk mencipta akaun.</p>
        </div>

        <div>
            <x-input-label for="name" value="Nama Penuh" class="font-bold text-gray-700" />
            <x-text-input id="name" class="block mt-1 w-full bg-gray-50 border border-gray-300 focus:border-[#003366] focus:ring-[#003366]" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Alamat Emel" class="font-bold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-50 border border-gray-300 focus:border-[#003366] focus:ring-[#003366]" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Kata Laluan" class="font-bold text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full bg-gray-50 border border-gray-300 focus:border-[#003366] focus:ring-[#003366]"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Sahkan Kata Laluan" class="font-bold text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-50 border border-gray-300 focus:border-[#003366] focus:ring-[#003366]"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a class="text-sm text-[#003366] hover:underline" href="{{ route('login') }}">
                Sudah mempunyai akaun?
            </a>

            <button type="submit" class="py-2.5 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#003366] hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#003366] transition ease-in-out duration-150">
                DAFTAR
            </button>
        </div>
    </form>
</x-guest-layout>