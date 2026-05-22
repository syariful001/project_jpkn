<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cipta Sesi Audit & Tugasan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-[#003366]">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('ketua.sesi.store') }}">
                        @csrf

                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Bahagian 1: Maklumat Sesi Audit</h3>
                        
                        <div class="mb-4">
                            <x-input-label for="tajuk_sesi" value="Tajuk Sesi Audit" class="font-bold text-gray-700"/>
                            <x-text-input id="tajuk_sesi" class="block mt-1 w-full" type="text" name="tajuk_sesi" required autofocus />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <div>
                                <x-input-label for="tarikh_mula" value="Tarikh Mula" class="font-bold text-gray-700"/>
                                <x-text-input id="tarikh_mula" class="block mt-1 w-full" type="date" name="tarikh_mula" required />
                            </div>
                            <div>
                                <x-input-label for="tarikh_tamat" value="Tarikh Tamat" class="font-bold text-gray-700"/>
                                <x-text-input id="tarikh_tamat" class="block mt-1 w-full" type="date" name="tarikh_tamat" required />
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Bahagian 2: Penugasan Borang & Juruaudit</h3>
                        <p class="text-xs text-gray-500 mb-4">Anda boleh menugaskan lokasi/cawangan yang berbeza kepada juruaudit yang berbeza di dalam sesi yang sama. Tekan butang tambah untuk memasukkan lebih ramai juruaudit.</p>

                        @if ($errors->any())
                            <div class="mb-4 p-3 bg-red-50 text-red-600 border border-red-200 rounded text-sm font-bold">
                                Sila pastikan semua ruangan juruaudit dan cawangan telah diisi.
                            </div>
                        @endif

                        @php
                            $senaraiLokasi = [
                                'JTDI Wilayah' => [
                                    'Cawangan Bandaraya (Ibu Pejabat)',
                                    'Wilayah Pantai Barat Utara',
                                    'Wilayah Pedalaman Atas',
                                    'Wilayah Pedalaman Bawah',
                                    'Wilayah Sandakan',
                                    'Wilayah Tawau'
                                ],
                                'Pasukan Inovasi Digital (PID)' => [
                                    'PID Kementerian Kewangan (MOF)',
                                    'PID Kementerian Pembangunan Luar Bandar (KPLB)',
                                    'PID Kementerian Kerajaan Tempatan dan Perumahan (KKTP)',
                                    'PID Kementerian Kerja Raya (KKR)',
                                    'PID Kementerian Pertanian, Perikanan dan Industri Makanan',
                                    'PID Kementerian Sains, Teknologi dan Inovasi (KSTI)',
                                    'PID Kementerian Pelancongan, Kebudayaan dan Alam Sekitar',
                                    'PID Kementerian Belia dan Sukan (KBS)',
                                    'PID Kementerian Pembangunan Perindustrian dan Keusahawanan',
                                    'PID Kementerian Pembangunan Masyarakat dan Kesejahteraan Rakyat',
                                    'PID Jabatan Ketua Menteri (JKM)',
                                    'PID Dewan Bandaraya Kota Kinabalu (DBKK)',
                                    'PID Jabatan Air Negeri Sabah (JANS)',
                                    'PID Jabatan Kerja Raya (JKR)',
                                    'PID Jabatan Tanah dan Ukur (JTU)',
                                    'PID Jabatan Hal Ehwal Agama Islam Negeri Sabah (JHEAINS)',
                                    'PID Jabatan Perhutanan Sabah',
                                    'PID Jabatan Perkhidmatan Veterinar',
                                    'PID Jabatan Perancang Bandar dan Wilayah',
                                    'PID Pejabat Hasil Bumi',
                                    'PID Lembaga Industri Getah Sabah (LIGS)'
                                ],
                                'Unit Sokongan ICT (USIT)' => [
                                    'USIT Jabatan Pembangunan Perindustrian & Penyelidikan',
                                    'USIT Jabatan Pembangunan Sumber Manusia (JPSM)',
                                    'USIT Jabatan Pertanian Sabah',
                                    'USIT Jabatan Perikanan Sabah',
                                    'USIT Pejabat Daerah'
                                ]
                            ];
                        @endphp

                        <div class="overflow-x-auto border border-gray-200 rounded-md mb-4 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase w-1/3">Juruaudit Ditugaskan</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase w-1/2">Bahagian / Lokasi / Cawangan Diaudit</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase w-1/6">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody id="tugasan-container" class="bg-white divide-y divide-gray-200">
                                    
                                    <tr class="tugasan-row hover:bg-gray-50 transition">
                                        <td class="px-4 py-3">
                                            <select name="juruaudit_id[]" required class="block w-full border-gray-300 focus:border-[#003366] focus:ring-[#003366] rounded-md shadow-sm text-sm">
                                                <option value="" disabled selected>-- Pilih Juruaudit --</option>
                                                @foreach($senaraiJuruaudit as $auditor)
                                                    <option value="{{ $auditor->id }}">{{ $auditor->name }} (ID: {{ $auditor->id }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <select name="bahagian_cawangan[]" required class="block w-full border-gray-300 focus:border-[#003366] focus:ring-[#003366] rounded-md shadow-sm text-sm">
                                                <option value="" disabled selected>-- Pilih Lokasi Audit --</option>
                                                @foreach($senaraiLokasi as $kategori => $cawangan)
                                                    <optgroup label="=== {{ $kategori }} ===" class="bg-gray-100 font-bold text-blue-900">
                                                        @foreach($cawangan as $lokasi)
                                                            <option value="{{ $lokasi }}" class="bg-white text-gray-800 font-normal">{{ $lokasi }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" onclick="removeRow(this)" class="px-3 py-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 font-bold text-xs shadow-sm transition">X</button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="mb-8">
                            <button type="button" onclick="addRow()" class="px-4 py-2 bg-gray-600 text-white text-xs font-bold rounded shadow-sm hover:bg-gray-700 transition">
                                + Tambah 
                            </button>
                        </div>

                        <div class="mb-8">
                            <x-input-label for="kategori_senarai_semak" value="Kod Borang / Kategori Semakan (Standard Keselamatan)" class="font-bold text-gray-700"/>
                            <select id="kategori_senarai_semak" name="kategori_senarai_semak" 
                                class="mt-1 block w-full md:w-1/2 border-gray-300 bg-gray-50 font-bold text-[#003366] rounded-md shadow-sm cursor-not-allowed" required>
                                <option value="JPKN-U-03/B3" selected>JPKN-U-03/B3</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-6">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 mr-3">
                                Batal
                            </a>
                            <button type="submit" onclick="return confirm('Pasti mahu mencipta sesi ini dan mengagihkan tugasan kepada juruaudit yang dipilih?');" class="inline-flex items-center px-5 py-2.5 bg-[#003366] border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-800 shadow-md transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan & Agihkan Tugasan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function addRow() {
            const container = document.getElementById('tugasan-container');
            const firstRow = container.querySelector('.tugasan-row').cloneNode(true);
            
            // Set semula kotak dropdown kepada nilai kosong untuk baris baru
            firstRow.querySelectorAll('select').forEach(select => select.value = '');
            
            container.appendChild(firstRow);
        }

        function removeRow(btn) {
            const container = document.getElementById('tugasan-container');
            if (container.querySelectorAll('.tugasan-row').length > 1) {
                btn.closest('tr').remove();
            } else {
                alert('Amaran: Anda mesti mempunyai sekurang-kurangnya SATU tugasan juruaudit dalam sesi ini.');
            }
        }
    </script>
</x-app-layout>