<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-b-4 border-green-600 pb-2 inline-block">
            {{ __('Pengisian Borang Senarai Semak Audit') }}
        </h2>
    </x-slot>

    @php
        $klausaGroup = [];
        $sortedItems = collect($senaraiItem)->sortBy('no_klausa', SORT_NATURAL);
        
        foreach($sortedItems as $item) {
            $mainKlausa = explode('.', $item->no_klausa)[0];
            $klausaGroup[$mainKlausa][] = $item;
        }

        $hasChildren = [];
        $expandedNodes = [];
        
        foreach($sortedItems as $item) {
            $hasChildren[$item->no_klausa] = false;
            foreach($sortedItems as $child) {
                if ($child->no_klausa !== $item->no_klausa && str_starts_with($child->no_klausa, $item->no_klausa . '.')) {
                    $hasChildren[$item->no_klausa] = true;
                    break;
                }
            }
            if ($item->ulasan) {
                $parts = explode('.', $item->no_klausa);
                while(count($parts) > 0) {
                    $expandedNodes[implode('.', $parts)] = true;
                    array_pop($parts);
                }
            }
        }
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('juruaudit.borang.update', $borang->id) }}">
                @csrf
                @method('PUT')

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-gray-800 mb-6">
                    <div class="p-6 text-gray-900 grid grid-cols-3 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 font-bold uppercase">Lokasi / Cawangan</p>
                            <p class="text-lg font-semibold">{{ $borang->bahagian_cawangan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-bold uppercase">Kategori Semakan</p>
                            <p class="text-lg font-semibold">{{ $borang->kategori_senarai_semak }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="nama_auditee" value="Nama Auditee (Individu/Unit Yang Diaudit)" class="font-bold text-gray-700 uppercase text-[12px]"/>
                            <input type="text" name="nama_auditee" id="nama_auditee" 
                                value="{{ old('nama_auditee', $borang->nama_auditee) }}" 
                                class="mt-1 block w-full border-gray-300 focus:border-green-600 focus:ring-green-600 rounded-md shadow-sm font-bold" 
                                autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-blue-200">
                    <div class="p-4 bg-blue-50 border-b border-blue-200">
                        <h3 class="text-lg font-bold text-blue-900">Langkah 1: Pilih Klausa Berkaitan</h3>
                        <p class="text-xs text-gray-600">Tekan butang [+] untuk melihat sub-klausa. Tandakan kotak (checkbox) pada klausa yang ingin diaudit.</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($klausaGroup as $main => $items)
                                <div class="border rounded-md p-4 bg-white shadow-sm">
                                    <h4 class="font-bold text-lg text-[#003366] border-b pb-2 mb-3">Klausa {{ $main }}</h4>
                                    
                                    <div class="tree-container space-y-1">
                                        @foreach($items as $item)
                                            @php
                                                $parent = '';
                                                $parts = explode('.', $item->no_klausa);
                                                while (count($parts) > 1) {
                                                    array_pop($parts);
                                                    $possibleParent = implode('.', $parts);
                                                    if (collect($items)->contains('no_klausa', $possibleParent)) {
                                                        $parent = $possibleParent;
                                                        break;
                                                    }
                                                }
                                                $isExpanded = isset($expandedNodes[$item->no_klausa]);
                                                $showNode = $parent == '' || isset($expandedNodes[$parent]);
                                                $btnText = $isExpanded ? '-' : '+';
                                            @endphp
                                            
                                            <div class="tree-node {{ $parent != '' ? 'ml-6 border-l-2 border-gray-200 pl-3 mt-1' : 'mt-2' }}" 
                                                 data-klausa="{{ $item->no_klausa }}" data-parent="{{ $parent }}" style="{{ !$showNode ? 'display: none;' : '' }}">
                                                 
                                                <div class="flex items-start py-1">
                                                    @if($hasChildren[$item->no_klausa])
                                                        <button type="button" class="toggle-btn w-5 h-5 flex-shrink-0 flex items-center justify-center bg-blue-100 text-blue-800 rounded mr-2 mt-0.5 hover:bg-blue-300 text-xs font-bold transition" data-target="{{ $item->no_klausa }}">{{ $btnText }}</button>
                                                    @else
                                                        <span class="w-5 h-5 flex-shrink-0 mr-2 inline-block"></span>
                                                    @endif
                                                    
                                                    <input type="checkbox" class="klausa-cb w-4 h-4 text-green-600 border-gray-300 rounded mr-3 mt-1 focus:ring-green-500" 
                                                        data-id="{{ $item->id }}" data-klausa="{{ $item->no_klausa }}" {{ $item->ulasan ? 'checked' : '' }}>
                                                    
                                                    <label class="text-sm font-medium text-gray-700 select-none cursor-pointer mt-0.5" onclick="this.previousElementSibling.click();">
                                                        <span class="font-bold text-gray-900">{{ $item->no_klausa }}</span> - {{ $item->templatKlausa->tajuk_klausa ?? 'Perkara Semakan' }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">Langkah 2: Pengisian Borang Semakan</h3>
                    </div>
                    
                    <div class="p-6">
                        <div id="empty-table-msg" class="text-center py-12 bg-gray-50 border-dashed border-2 border-gray-200 text-gray-400 rounded-lg">
                            Sila pilih sub-klausa di Langkah 1 untuk memulakan pengisian.
                        </div>

                        <div id="table-body" class="space-y-6">
                            @foreach($senaraiItem as $item)
                            <div id="row-{{ $item->id }}" class="klausa-row hidden bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
                                
                                <div class="bg-gray-800 p-4 flex justify-between items-center cursor-pointer hover:bg-gray-700 transition" onclick="window.toggleCard('{{ $item->id }}')">
                                    <div class="flex items-center space-x-4">
                                        <span class="bg-white text-gray-800 px-3 py-1 rounded text-sm font-bold">Klausa {{ $item->no_klausa }}</span>
                                        <span class="text-white font-bold text-base truncate max-w-md">{{ $item->templatKlausa->tajuk_klausa ?? 'Perkara Semakan' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <button type="button" class="text-white text-xs font-bold uppercase tracking-widest flex items-center bg-gray-600 px-3 py-1.5 rounded hover:bg-gray-500">
                                            <span id="btn-text-{{ $item->id }}">Tutup</span>
                                            <svg id="icon-{{ $item->id }}" class="w-4 h-4 ml-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <div id="card-body-{{ $item->id }}" class="p-6 bg-gray-50">
                                    
                                    <div class="bg-white border border-gray-300 p-6 mb-6 text-justify text-gray-600 rounded-md shadow-inner min-h-[80px] flex items-center justify-center text-sm">
                                        {{ $item->templatKlausa->deskripsi ?? 'Tiada deskripsi khusus diletakkan untuk klausa ini.' }}
                                    </div>

                                    <textarea name="perkara_periksa[{{ $item->id }}]" id="hidden-perkara-{{ $item->id }}" class="hidden">{{ old('perkara_periksa.'.$item->id, $item->ulasan ? $item->perkara_periksa : '') }}</textarea>
                                    <textarea name="ulasan[{{ $item->id }}]" id="hidden-ulasan-{{ $item->id }}" class="hidden">{{ old('ulasan.'.$item->id, $item->ulasan) }}</textarea>

                                    <div id="dynamic-container-{{ $item->id }}" class="space-y-4"></div>

                                    <div class="flex justify-end mt-4 mb-8">
                                        <button type="button" onclick="window.addRow('{{ $item->id }}')" class="bg-gray-800 text-white text-xs font-bold px-5 py-2.5 rounded shadow hover:bg-black transition uppercase tracking-wider">
                                            + Tambah
                                        </button>
                                    </div>

                                    <div class="mt-6 border-t border-gray-200 pt-5">
                                        <label class="block text-sm font-bold text-blue-900 uppercase mb-2">Rujukan (Jika ada)</label>
                                        <input type="text" name="rujukan[{{ $item->id }}]" id="input-rujukan-{{ $item->id }}" 
                                            class="w-full md:w-1/3 text-sm border-gray-300 focus:border-green-600 focus:ring-green-600 rounded shadow-sm item-input-{{ $item->id }}" 
                                            value="{{ old('rujukan.'.$item->id, $item->rujukan) }}" 
                                            autocomplete="off" disabled>
                                    </div>
                                </div>

                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-4">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">Kembali</a>
                    @if($borang->status != 'siap_disemak')
                        <button type="submit" name="simpan_draf" formnovalidate value="1" class="px-4 py-2 bg-gray-600 text-white rounded-md font-bold text-xs uppercase tracking-widest hover:bg-gray-700 shadow">Simpan Draf</button>
                        <button type="submit" name="hantar_tamat" value="1" onclick="return confirm('Hantar borang kepada Ketua?');" class="px-4 py-2 bg-green-600 text-white rounded-md font-bold text-xs uppercase tracking-widest hover:bg-green-700 shadow">Selesai & Hantar</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        // 1. FUNGSI TOGGLE KAD (BUKA/TUTUP)
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

        // 2. FUNGSI TAMBAH BARIS DINAMIK
        window.addRow = function(itemId, valP = '', valU = '') {
            const cb = document.querySelector(`.klausa-cb[data-id="${itemId}"]`);
            const isEnabled = cb ? cb.checked : false;
            const container = document.querySelector(`#dynamic-container-${itemId}`);

            const rowDiv = document.createElement('div');
            rowDiv.className = 'bg-white p-5 border border-gray-200 rounded-md shadow-sm relative mb-4';

            // Perkara
            const lblP = document.createElement('label');
            lblP.className = 'block text-[13px] font-bold text-blue-900 uppercase mb-1';
            lblP.innerText = 'Perkara Untuk Diperiksa';
            const inputP = document.createElement('input');
            inputP.type = 'text';
            inputP.className = `w-full text-sm border-gray-300 focus:border-green-600 focus:ring-green-600 rounded mb-4 input-perkara-${itemId}`;
            inputP.value = valP;
            inputP.autocomplete = 'off';
            inputP.disabled = !isEnabled;

            // Ulasan
            const lblU = document.createElement('label');
            lblU.className = 'block text-[13px] font-bold text-blue-900 uppercase mb-1';
            lblU.innerText = 'Respon / Bukti / Penemuan';
            const wrapperU = document.createElement('div');
            wrapperU.className = 'flex gap-2';
            const inputU = document.createElement('input');
            inputU.type = 'text';
            inputU.className = `w-full text-sm border-gray-300 focus:border-green-600 focus:ring-green-600 rounded input-ulasan-${itemId}`;
            inputU.value = valU;
            inputU.autocomplete = 'off';
            inputU.disabled = !isEnabled;

            // Butang X
            const btnX = document.createElement('button');
            btnX.type = 'button';
            btnX.className = 'px-3 py-1 bg-red-50 text-red-600 border border-red-200 rounded text-xs font-bold hover:bg-red-100 transition';
            btnX.innerHTML = 'X';
            btnX.onclick = function() {
                if(container.children.length > 1) {
                    container.removeChild(rowDiv);
                    window.syncHiddenFields(itemId);
                }
            };

            wrapperU.appendChild(inputU);
            wrapperU.appendChild(btnX);
            rowDiv.appendChild(lblP);
            rowDiv.appendChild(inputP);
            rowDiv.appendChild(lblU);
            rowDiv.appendChild(wrapperU);
            container.appendChild(rowDiv);

            [inputP, inputU].forEach(el => el.addEventListener('input', () => window.syncHiddenFields(itemId)));
        }

        window.syncHiddenFields = function(itemId) {
            const ps = Array.from(document.querySelectorAll(`.input-perkara-${itemId}`)).map(i => i.value);
            const us = Array.from(document.querySelectorAll(`.input-ulasan-${itemId}`)).map(i => i.value);
            document.getElementById(`hidden-perkara-${itemId}`).value = ps.join('\n');
            document.getElementById(`hidden-ulasan-${itemId}`).value = us.join('\n');
        }

        document.addEventListener('DOMContentLoaded', function() {
            
            window.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    // Benarkan 'Enter' hanya jika pengguna sedang fokus pada butang (Cth: Butang + Tambah)
                    if (e.target.tagName !== 'BUTTON') {
                        e.preventDefault();
                        return false;
                    }
                }
            });

            // Inisialisasi Data Sedia Ada
            document.querySelectorAll('.klausa-row').forEach(row => {
                const itemId = row.getAttribute('id').replace('row-', '');
                const ps = document.getElementById(`hidden-perkara-${itemId}`).value.split('\n');
                const us = document.getElementById(`hidden-ulasan-${itemId}`).value.split('\n');
                const count = Math.max(ps.length, us.length, 1);
                for(let i=0; i<count; i++) window.addRow(itemId, ps[i] || '', us[i] || '');
            });

            // Logik Checkbox & Tree (Langkah 1)
            document.querySelectorAll('.klausa-cb').forEach(cb => {
                cb.addEventListener('change', function() {
                    const itemId = this.getAttribute('data-id');
                    const row = document.getElementById('row-' + itemId);
                    const isChecked = this.checked;
                    
                    if(row) {
                        if(isChecked) row.classList.remove('hidden'); else row.classList.add('hidden');
                        row.querySelectorAll('input').forEach(i => i.disabled = !isChecked);
                    }
                    
                    const klausa = this.getAttribute('data-klausa');
                    document.querySelectorAll(`.klausa-cb[data-klausa^="${klausa}."]`).forEach(sub => {
                        sub.checked = isChecked;
                        sub.dispatchEvent(new Event('change'));
                    });

                    checkTableVisibility();
                });
            });

            function checkTableVisibility() {
                const any = document.querySelectorAll('.klausa-row:not(.hidden)').length > 0;
                document.getElementById('empty-table-msg').style.display = any ? 'none' : 'block';
            }
            
            document.querySelectorAll('.klausa-cb:checked').forEach(cb => cb.dispatchEvent(new Event('change')));
            
            // Logik Toggle [+] [-] (Langkah 1)
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    const isExp = this.textContent === '-';
                    document.querySelectorAll(`.tree-node[data-parent="${target}"]`).forEach(c => c.style.display = isExp ? 'none' : 'block');
                    this.textContent = isExp ? '+' : '-';
                });
            });
        });
    </script>
</x-app-layout>