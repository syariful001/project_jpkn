<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-b-4 border-red-700 pb-2 inline-block">
            {{ __('Pengurusan Bank Klausa (MS ISO 9001:2015)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- BUTANG KEMBALI KE DASHBOARD -->
            <div class="mb-2">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 shadow-sm transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- KAD TAMBAH KLAUSA -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                <div class="p-6 text-gray-900 bg-gray-50 border-b">
                    <h3 class="text-lg font-bold text-[#003366]">Tambah Klausa / Keperluan Standard Baharu</h3>
                    <p class="text-sm text-gray-500">MS ISO 9001:2015</p>
                </div>
                
                <div class="p-6">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul class="list-disc pl-5 text-sm font-bold">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.templat.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            
                            <div>
                                <x-input-label for="no_klausa" value="Nombor Klausa (Cth: 4, 4.1, 7.1.5.1)" class="font-bold text-gray-700"/>
                                <input type="text" name="no_klausa" id="no_klausa" value="{{ old('no_klausa') }}" 
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-800 focus:ring-blue-800 rounded-md shadow-sm" 
                                    required>
                            </div>

                            <div>
                                <x-input-label for="tajuk_klausa" value="Tajuk Klausa / Sub-Klausa" class="font-bold text-gray-700"/>
                                <input type="text" name="tajuk_klausa" id="tajuk_klausa" value="{{ old('tajuk_klausa') }}" 
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-800 focus:ring-blue-800 rounded-md shadow-sm" 
                                    required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="deskripsi" value="Deskripsi / Perkara Yang Diperiksa (Pilihan)" class="font-bold text-gray-700"/>
                            <textarea name="deskripsi" id="deskripsi" rows="3" 
                                class="mt-1 block w-full border-gray-300 focus:border-blue-800 focus:ring-blue-800 rounded-md shadow-sm" 
                                placeholder="Tuliskan butiran yang perlu disemak oleh juruaudit di lapangan...">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-[#003366] text-white rounded-md font-bold text-xs uppercase tracking-widest hover:bg-blue-900 shadow-md transition">
                                + Simpan Ke Pangkalan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- JADUAL SENARAI KLAUSA -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Senarai Klausa Sedia Ada</h3>
                    <span class="px-3 py-1 bg-blue-100 text-[#003366] text-xs font-bold rounded-full">
                        Jumlah: {{ $senaraiKlausa->total() ?? $senaraiKlausa->count() }} Rekod
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase w-1/4">No. Klausa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase w-1/3">Tajuk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase">Deskripsi</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold uppercase w-24">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($senaraiKlausa as $klausa)
                            
                            @php
                                $kedalaman = substr_count($klausa->no_klausa, '.');
                                
                                $paddingClass = 'pl-6'; 
                                $bgClass = 'bg-white hover:bg-gray-50';
                                $textClass = 'text-gray-900';
                                $fontWeight = 'font-bold text-base';
                                
                                if ($kedalaman == 1) { 
                                    $paddingClass = 'pl-10';
                                    $fontWeight = 'font-semibold text-sm';
                                    $textClass = 'text-blue-900';
                                } elseif ($kedalaman == 2) { 
                                    $paddingClass = 'pl-16';
                                    $fontWeight = 'font-medium text-sm';
                                    $textClass = 'text-gray-700';
                                } elseif ($kedalaman >= 3) { 
                                    $paddingClass = 'pl-24';
                                    $fontWeight = 'font-normal text-sm italic';
                                    $textClass = 'text-gray-600';
                                }
                            @endphp

                            <tr class="{{ $bgClass }} transition duration-150">
                                <td class="py-3 pr-6 whitespace-nowrap align-top {{ $paddingClass }}">
                                    <span class="inline-block px-2 py-1 rounded {{ $kedalaman == 0 ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-800 border' }} font-bold text-xs">
                                        {{ $klausa->no_klausa }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 align-top">
                                    <div class="{{ $fontWeight }} {{ $textClass }}">{{ $klausa->tajuk_klausa }}</div>
                                </td>
                                <td class="px-6 py-3 text-xs text-gray-600 align-top">
                                    {{ $klausa->deskripsi ?? '-' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium align-top">
                                    <form method="POST" action="{{ route('admin.templat.destroy', $klausa->id) }}" onsubmit="return confirm('Anda pasti mahu memadam klausa {{ $klausa->no_klausa }} ini? Semua rekod audit yang menggunakan klausa ini mungkin terkesan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold bg-red-50 px-2 py-1 rounded">Padam</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400 font-medium bg-gray-50 border-dashed border-2">
                                    Belum ada sebarang klausa dimasukkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($senaraiKlausa->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $senaraiKlausa->links() }}
                </div>
                @endif
                
            </div>

        </div>
    </div>
</x-app-layout>