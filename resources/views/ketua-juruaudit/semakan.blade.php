<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-b-4 border-blue-800 pb-2 inline-block">
            {{ __('Semakan & Pengesahan Ketua Juruaudit') }}
        </h2>
    </x-slot>

    <style>
        html { scroll-behavior: smooth; }
    </style>

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

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

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
                                        $arrPerkara = explode("\n", $item->perkara_periksa ?? '-');
                                        $arrUlasan = explode("\n", $item->ulasan ?? 'Tiada rekod penemuan.');
                                        $count = max(count($arrPerkara), count($arrUlasan));
                                    @endphp

                                    <div class="space-y-4 mb-6">
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
                                        <div class="w-full md:w-1/3 text-sm bg-blue-50 border border-blue-100 p-2.5 rounded shadow-sm text-gray-700 font-semibold">
                                            {{ $item->rujukan ?? 'Tiada rujukan disertakan' }}
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 pt-6">
                                        <div class="flex flex-col md:flex-row justify-between md:items-center mb-4">
                                            <h4 class="text-sm font-bold text-gray-800 uppercase flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                Rekod Ketidakpatuhan (NCR) & Penambahbaikan (OFI)
                                            </h4>
                                            
                                            @if($borang->status != 'selesai')
                                            <a href="{{ route('ketua.semakan.edit_ncr_ofi', ['borang_id' => $borang->id, 'item_id' => $item->id]) }}" class="px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-xs font-bold rounded-md shadow-sm border border-yellow-300 transition flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                Edit NCR / OFI
                                            </a>
                                            @endif
                                        </div>
                                        
                                        @php
                                            $ncrDetailsArr = json_decode($item->ncr_details, true) ?? [];
                                            $ofiDetailsArr = json_decode($item->ofi_details, true) ?? [];
                                        @endphp

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @if($item->ncr_count > 0 || count($ncrDetailsArr) > 0)
                                                <div class="bg-gray-50 border border-gray-200 rounded-md p-5 shadow-sm">
                                                    <span class="inline-block px-3 py-1 bg-gray-800 text-white text-[12px] uppercase font-bold rounded-full mb-4 shadow-sm">
                                                        Jumlah NCR: {{ $item->ncr_count }}
                                                    </span>
                                                    <ul class="list-disc list-outside text-sm text-gray-900 ml-4 space-y-3">
                                                        @forelse($ncrDetailsArr as $detail)
                                                            @if(trim($detail) !== '')
                                                                <li class="pl-1 text-justify">{{ $detail }}</li>
                                                            @endif
                                                        @empty
                                                            <li class="italic text-red-500 list-none ml-[-1rem]">Tiada butiran direkodkan.</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            @else
                                                <div class="bg-gray-100 border border-gray-200 rounded-md p-5 shadow-sm text-center text-gray-400 text-sm italic">
                                                    Tiada rekod NCR.
                                                </div>
                                            @endif

                                            @if($item->ofi_count > 0 || count($ofiDetailsArr) > 0)
                                                <div class="bg-gray-50 border border-gray-200 rounded-md p-5 shadow-sm">
                                                    <span class="inline-block px-3 py-1 bg-gray-800 text-white text-[12px] uppercase font-bold rounded-full mb-4 shadow-sm">
                                                        Jumlah OFI: {{ $item->ofi_count }}
                                                    </span>
                                                    <ul class="list-disc list-outside text-sm text-gray-900 ml-4 space-y-3">
                                                        @forelse($ofiDetailsArr as $detail)
                                                            @if(trim($detail) !== '')
                                                                <li class="pl-1 text-justify">{{ $detail }}</li>
                                                            @endif
                                                        @empty
                                                            <li class="italic text-yellow-500 list-none ml-[-1rem]">Tiada butiran direkodkan.</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            @else
                                                <div class="bg-gray-100 border border-gray-200 rounded-md p-5 shadow-sm text-center text-gray-400 text-sm italic">
                                                    Tiada rekod OFI.
                                                </div>
                                            @endif
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

                    <form method="POST" action="{{ route('ketua.semakan.update', $borang->id) }}">
                        @csrf
                        
                        @if($borang->status != 'selesai')
                            <p class="text-sm text-gray-600 mb-4">Pastikan anda telah selesai menyemak dan menyunting (jika perlu) kesemua rekod penemuan audit di atas. Tandakan kotak di bawah untuk menutup fail audit secara rasmi.</p>
                            
                            <div class="mb-4 bg-blue-50 border border-blue-200 p-4 rounded-md">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="pengesahan_ketua" required class="rounded border-gray-400 text-blue-800 shadow-sm focus:ring-blue-500 w-5 h-5">
                                    <span class="ml-3 text-sm font-bold text-blue-900">Saya mengesahkan bahawa semakan telah dibuat dengan teliti dan saya bersetuju untuk menutup fail audit ini.</span>
                                </label>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 font-bold mb-4">Borang ini telah disahkan dan fail ditutup dengan jayanya. Sila cetak laporan PDF untuk rujukan atau simpanan.</p>
                        @endif

                        <div class="mt-4 flex items-center justify-end space-x-3 border-t pt-4">
                            @if($borang->status != 'selesai')
                                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                                    Batal
                                </a>
                                <button type="submit" onclick="return confirm('Sahkan borang ini? Borang akan dikunci dan PDF laporan dijana.');" class="px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 shadow-md">
                                    Sahkan & Tutup Fail
                                </button>
                            @else
                                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                                    Kembali ke Dashboard
                                </a>
                                
                                <a href="{{ route('ketua.semakan.pdf_ncr_ofi', $borang->id) }}" target="_blank" class="px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 shadow-md flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Cetak PDF Penemuan (NCR/OFI)
                                </a>

                                <a href="{{ route('ketua.semakan.pdf', $borang->id) }}" target="_blank" class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 shadow-md flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Cetak Laporan Penuh
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button id="scrollToTopBtn" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });"
        class="fixed bottom-8 right-8 bg-[#003366] text-white p-3 rounded-full shadow-lg hover:bg-blue-900 transition-all duration-300 opacity-0 invisible z-50 transform hover:scale-110">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollBtn = document.getElementById('scrollToTopBtn');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    scrollBtn.classList.remove('opacity-0', 'invisible');
                    scrollBtn.classList.add('opacity-100', 'visible');
                } else {
                    scrollBtn.classList.remove('opacity-100', 'visible');
                    scrollBtn.classList.add('opacity-0', 'invisible');
                }
            });
        });

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