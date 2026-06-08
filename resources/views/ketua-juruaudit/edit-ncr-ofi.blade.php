<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-b-4 border-yellow-500 pb-2 inline-block">
            {{ __('Sunting Rekod NCR / OFI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-gray-800 text-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="font-bold text-lg">Klausa {{ $item->no_klausa }}</h3>
                <p class="text-sm text-gray-300 mt-1">{{ $item->templatKlausa->tajuk_klausa ?? 'Perkara Semakan' }}</p>
                <p class="text-sm text-gray-400 mt-2 italic">Cawangan: {{ $borang->bahagian_cawangan }}</p>
            </div>

            <form method="POST" action="{{ route('ketua.semakan.update_ncr_ofi', ['borang_id' => $borang->id, 'item_id' => $item->id]) }}" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                @csrf
                @method('PUT')

                @php
                    $ncrDetailsArr = json_decode($item->ncr_details, true) ?? [];
                    $ofiDetailsArr = json_decode($item->ofi_details, true) ?? [];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- BAHAGIAN NCR -->
                    <div class="bg-red-50 p-5 rounded-md border border-red-200 shadow-inner">
                        <h4 class="font-bold text-red-800 uppercase mb-4 border-b border-red-200 pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Ketidakpatuhan (NCR)
                        </h4>
                        
                        <div id="ncr_container" class="space-y-4">
                            @if(count($ncrDetailsArr) > 0)
                                @foreach($ncrDetailsArr as $index => $detail)
                                    <div class="bg-white p-3 border border-red-200 rounded shadow-sm relative group">
                                        <textarea name="ncr_details[]" class="w-full text-sm border-gray-300 focus:border-red-500 rounded pr-8" rows="3" placeholder="Nyatakan butiran NCR...">{{ $detail }}</textarea>
                                        <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-300 hover:text-red-600 transition" title="Padam Baris Ini">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="bg-white p-3 border border-red-200 rounded shadow-sm relative group">
                                    <textarea name="ncr_details[]" class="w-full text-sm border-gray-300 focus:border-red-500 rounded" rows="3" placeholder="Nyatakan butiran NCR..."></textarea>
                                </div>
                            @endif
                        </div>
                        
                        <button type="button" onclick="addField('ncr')" class="mt-4 px-4 py-2 bg-red-600 text-white text-xs font-bold uppercase tracking-wider rounded shadow hover:bg-red-700 transition w-full">
                            + Tambah Butiran NCR
                        </button>
                    </div>

                    <!-- BAHAGIAN OFI -->
                    <div class="bg-yellow-50 p-5 rounded-md border border-yellow-200 shadow-inner">
                        <h4 class="font-bold text-yellow-800 uppercase mb-4 border-b border-yellow-200 pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            Penambahbaikan (OFI)
                        </h4>
                        
                        <div id="ofi_container" class="space-y-4">
                            @if(count($ofiDetailsArr) > 0)
                                @foreach($ofiDetailsArr as $index => $detail)
                                    <div class="bg-white p-3 border border-yellow-200 rounded shadow-sm relative group">
                                        <textarea name="ofi_details[]" class="w-full text-sm border-gray-300 focus:border-yellow-500 rounded pr-8" rows="3" placeholder="Nyatakan butiran OFI...">{{ $detail }}</textarea>
                                        <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-yellow-400 hover:text-yellow-600 transition" title="Padam Baris Ini">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="bg-white p-3 border border-yellow-200 rounded shadow-sm relative group">
                                    <textarea name="ofi_details[]" class="w-full text-sm border-gray-300 focus:border-yellow-500 rounded" rows="3" placeholder="Nyatakan butiran OFI..."></textarea>
                                </div>
                            @endif
                        </div>

                        <button type="button" onclick="addField('ofi')" class="mt-4 px-4 py-2 bg-yellow-600 text-white text-xs font-bold uppercase tracking-wider rounded shadow hover:bg-yellow-700 transition w-full">
                            + Tambah Butiran OFI
                        </button>
                    </div>

                </div>

                <div class="mt-8 flex items-center justify-end space-x-3 border-t border-gray-200 pt-6">
                    <p class="text-xs text-gray-400 mr-auto italic">* Nota: Jika anda mengosongkan teks atau memadam semua kotak, rekod akan dibuang secara automatik.</p>
                    <a href="{{ route('ketua.semakan.show', $borang->id) }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-md font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-800 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-900 shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addField(type) {
            const container = document.getElementById(type + '_container');
            const borderColor = type === 'ncr' ? 'border-red-200' : 'border-yellow-200';
            const focusColor = type === 'ncr' ? 'focus:border-red-500' : 'focus:border-yellow-500';
            const btnColor = type === 'ncr' ? 'text-red-300 hover:text-red-600' : 'text-yellow-400 hover:text-yellow-600';
            const placeholder = type === 'ncr' ? 'Nyatakan butiran NCR...' : 'Nyatakan butiran OFI...';
            
            const div = document.createElement('div');
            div.className = `bg-white p-3 border ${borderColor} rounded shadow-sm relative group`;
            
            div.innerHTML = `
                <textarea name="${type}_details[]" class="w-full text-sm border-gray-300 ${focusColor} rounded pr-8" rows="3" placeholder="${placeholder}"></textarea>
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 ${btnColor} transition" title="Padam Baris Ini">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                </button>
            `;
            
            container.appendChild(div);
        }
    </script>
</x-app-layout>