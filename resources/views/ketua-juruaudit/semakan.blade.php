<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-b-4 border-blue-800 pb-2 inline-block">
            {{ __('Semakan & Pengesahan Ketua Juruaudit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-800 mb-6">
                <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase">Cawangan / Lokasi</p>
                        <p class="text-sm font-semibold">{{ $borang->bahagian_cawangan }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase">Nama Auditee</p>
                        <p class="text-sm font-bold text-gray-700">{{ $borang->nama_auditee ?? 'Tidak Dinyatakan' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase">Kategori Semakan</p>
                        <p class="text-sm font-semibold">{{ $borang->kategori_senarai_semak }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 font-bold uppercase">Status Borang</p>
                        <span class="px-3 py-1 mt-1 inline-block text-xs font-bold rounded-full {{ $borang->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ strtoupper(str_replace('_', ' ', $borang->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Senarai Penemuan & Bukti Audit</h3>
                </div>
                
                <div class="p-6">
                    @if(count($senaraiItem) == 0)
                        <div class="text-center py-12 bg-gray-50 border-dashed border-2 border-gray-200 text-gray-400 rounded-lg italic font-medium">
                            Tiada sebarang klausa atau penemuan direkodkan dalam borang ini.
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($senaraiItem as $item)
                            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
                                
                                <div class="bg-gray-800 p-4 flex justify-between items-center cursor-pointer hover:bg-gray-700 transition" onclick="window.toggleCard('{{ $item->id }}')">
                                    <div class="flex items-center space-x-4">
                                        <span class="bg-white text-gray-800 px-3 py-1 rounded text-sm font-bold">Klausa {{ $item->no_klausa }}</span>
                                        <span class="text-white font-bold text-base truncate max-w-md">{{ $item->templatKlausa->tajuk_klausa ?? 'Keperluan Standard' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <button type="button" class="text-white text-xs font-bold uppercase tracking-widest flex items-center bg-gray-600 px-3 py-1.5 rounded hover:bg-gray-500">
                                            <span id="btn-text-{{ $item->id }}">Tutup</span>
                                            <svg id="icon-{{ $item->id }}" class="w-4 h-4 ml-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <div id="card-body-{{ $item->id }}" class="p-6 bg-gray-50">
                                    
                                    <div class="bg-white border border-gray-300 p-6 mb-6 text-justify text-gray-600 rounded-md shadow-inner flex items-center justify-center text-sm">
                                        {{ $item->templatKlausa->deskripsi ?? 'Tiada deskripsi khusus disediakan untuk klausa ini.' }}
                                    </div>

                                    @php
                                        // Sistem memisahkan baris cantuman \n menjadi array
                                        $arrPerkara = explode("\n", $item->perkara_periksa ?? '-');
                                        $arrUlasan = explode("\n", $item->ulasan ?? 'Tiada rekod penemuan.');
                                        $count = max(count($arrPerkara), count($arrUlasan));
                                    @endphp

                                    <div class="space-y-4">
                                        @for($i = 0; $i < $count; $i++)
                                            @php
                                                $valP = trim($arrPerkara[$i] ?? '-');
                                                $valU = trim($arrUlasan[$i] ?? '-');
                                            @endphp
                                            
                                            @if($valP !== '' || $valU !== '')
                                            <div class="bg-white p-5 border border-gray-200 rounded-md shadow-sm relative">
                                                <label class="block text-[12px] font-bold text-blue-900 uppercase mb-1">Perkara Untuk Diperiksa</label>
                                                <div class="w-full text-sm bg-blue-50 border border-blue-100 p-3 rounded mb-4 text-gray-800">{{ $valP ?: '-' }}</div>

                                                <label class="block text-[12px] font-bold text-blue-900 uppercase mb-1">Respon / Bukti / Penemuan</label>
                                                <div class="w-full text-sm bg-blue-50 border border-gray-200 p-3 rounded text-gray-800">{{ $valU ?: '-' }}</div>
                                            </div>
                                            @endif
                                        @endfor
                                    </div>

                                    <div class="mt-6 border-t border-gray-200 pt-5">
                                        <label class="block text-xs font-bold text-blue-900 uppercase mb-2">Rujukan (Jika Ada)</label>
                                        <div class="w-full md:w-1/3 text-sm bg-blue border border-gray-300 p-2.5 rounded shadow-sm text-gray-700 font-semibold">
                                            {{ $item->rujukan ?? 'Tiada rujukan disertakan' }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Keputusan Semakan & Pengesahan Ketua Juruaudit</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 font-bold">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ketua.semakan.update', $borang->id) }}">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="ulasan_ketua" value="Ulasan Akhir Keseluruhan (Audit Summary)" class="font-bold text-blue-900 uppercase text-xs"/>
                            <textarea id="ulasan_ketua" name="ulasan_ketua" rows="5" 
                                class="block mt-2 w-full border-gray-300 rounded-md shadow-sm {{ $borang->status == 'selesai' ? 'bg-gray-200 text-gray-700 cursor-not-allowed' : 'focus:border-blue-800 focus:ring-blue-800' }}" 
                                placeholder="Tuliskan rumusan keseluruhan hasil audit, cadangan penambahbaikan, atau status ketidakpatuhan (NCR/OFI)..." 
                                {{ $borang->status == 'selesai' ? 'disabled' : 'required' }}>{{ $borang->ulasan_ketua }}</textarea>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3 border-t pt-4">
                            @if($borang->status != 'selesai')
                                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                                    Batal
                                </a>
                                <button type="submit" onclick="return confirm('Sahkan borang ini? Borang akan dikunci dan PDF laporan akan dijana.');" class="px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 shadow-md">
                                    Sahkan & Tutup Fail
                                </button>
                            @else
                                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                                    Kembali ke Dashboard
                                </a>
                                <a href="{{ route('ketua.semakan.pdf', $borang->id) }}" target="_blank" class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 shadow-md flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Cetak PDF Laporan
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.toggleCard = function(itemId) {
            const body = document.getElementById('card-body-' + itemId);
            const icon = document.getElementById('icon-' + itemId);
            const text = document.getElementById('btn-text-' + itemId);

            if (body.classList.contains('hidden')) {
                body.classList.remove('hidden');
                icon.style.transform = 'rotate(0deg)';
                text.innerText = 'Tutup';
            } else {
                body.classList.add('hidden');
                icon.style.transform = 'rotate(180deg)';
                text.innerText = 'Buka';
            }
        }
    </script>
</x-app-layout>