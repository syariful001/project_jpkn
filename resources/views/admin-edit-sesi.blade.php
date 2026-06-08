<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-b-4 border-red-700 pb-2 inline-block">
            {{ __('Kemaskini Sesi Audit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.sesi.update', $sesi->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700">Tajuk Sesi</label>
                            <input type="text" name="tajuk_sesi" value="{{ $sesi->tajuk_sesi }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Tarikh Mula</label>
                                <input type="date" name="tarikh_mula" value="{{ $sesi->tarikh_mula }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Tarikh Tamat</label>
                                <input type="date" name="tarikh_tamat" value="{{ $sesi->tarikh_tamat }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700">Status Sesi</label>
                            <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="dirancang" {{ $sesi->status == 'dirancang' ? 'selected' : '' }}>Dirancang</option>
                                <option value="sedang berjalan" {{ $sesi->status == 'siap' ? 'selected' : '' }}>Sedang Berjalan / Semakan</option>
                                <option value="selesai" {{ $sesi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-bold hover:bg-gray-300">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>