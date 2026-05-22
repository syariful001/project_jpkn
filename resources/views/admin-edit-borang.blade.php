<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-b-4 border-red-700 pb-2 inline-block">
            {{ __('Kemaskini Maklumat Borang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-l-4 border-yellow-500 mb-6 bg-yellow-50">
                    <p class="text-sm text-yellow-800 font-semibold">Tindakan Admin: Anda boleh mengubah status borang yang "Selesai" kembali kepada "Sedang Diisi" jika Juruaudit membuat kesilapan dan perlu mengisinya semula.</p>
                </div>

                <div class="p-6 text-gray-900 bg-white">
                    <form method="POST" action="{{ route('admin.borang.update', $borang->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700">Lokasi / Cawangan Diaudit</label>
                            <input type="text" name="bahagian_cawangan" value="{{ $borang->bahagian_cawangan }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700">Nama Auditee</label>
                            <input type="text" name="nama_auditee" value="{{ $borang->nama_auditee }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700">Status Borang</label>
                            <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="ditugaskan" {{ $borang->status == 'ditugaskan' ? 'selected' : '' }}>Ditugaskan (Borang Kosong)</option>
                                <option value="sedang_diisi" {{ $borang->status == 'sedang_diisi' ? 'selected' : '' }}>Sedang Diisi (Mod Draf)</option>
                                <option value="siap_disemak" {{ $borang->status == 'siap_disemak' ? 'selected' : '' }}>Menunggu Semakan Ketua</option>
                                <option value="selesai" {{ $borang->status == 'selesai' ? 'selected' : '' }}>Selesai (Tamat)</option>
                            </select>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-bold hover:bg-gray-300">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700">Kemaskini Borang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>