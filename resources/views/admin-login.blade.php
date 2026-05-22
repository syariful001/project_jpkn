<x-guest-layout>
    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div class="mb-6 text-center border-b-2 border-red-700 pb-4">
            <h4 class="text-lg font-bold text-red-800">PORTAL PENTADBIR SISTEM</h4>
            <p class="text-sm text-gray-500">Log masuk pengurusan (Admin) JTDI.</p>
        </div>

        <div>
            <x-input-label for="email" value="Alamat Emel Admin" class="font-bold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-50 border border-gray-300 focus:border-red-700 focus:ring-red-700" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Kata Laluan" class="font-bold text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full bg-gray-50 border border-gray-300 focus:border-red-700 focus:ring-red-700" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                LOG MASUK
            </button>
        </div>
    </form>
</x-guest-layout>