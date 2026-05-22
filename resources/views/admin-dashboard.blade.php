<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-b-4 border-red-700 pb-2 inline-block">
            {{ __('Papan Pemuka Pentadbir Sistem (Admin)') }}
        </h2>
    </x-slot>

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div id="success-alert"
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm mb-6 transition-opacity duration-500 ease-in-out opacity-100"
                    role="alert">
                    <span class="block sm:inline font-bold">{{ session('success') }}</span>
                </div>

                <script>
                    setTimeout(function () {
                        const alert = document.getElementById('success-alert');
                        if (alert) {
                            alert.classList.remove('opacity-100');
                            alert.classList.add('opacity-0');

                            setTimeout(() => alert.remove(), 500);
                        }
                    }, 5000);
                </script>
            @endif

            <div class="flex flex-col md:flex-row gap-6">
                
                <div class="w-full md:w-1/4">
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 sticky top-6">
                        <div class="p-4 bg-red-700 text-white rounded-t-lg font-bold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            Menu
                        </div>
                        <div class="p-2 space-y-1">
                            <a href="#analitik-global" class="block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md transition font-semibold">
                                Analitik & Statistik
                            </a>
                            <a href="#pengurusan-sesi" class="block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md transition font-semibold">
                                Pengurusan Sesi Audit
                            </a>
                            <a href="#pengurusan-borang" class="block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md transition font-semibold">
                                Pengurusan Borang Lapangan
                            </a>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-3/4 space-y-6">

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-700">
                        <div class="p-6 text-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-red-800">Modul Pentadbiran Utama JTDI</h3>
                                <p class="text-sm text-gray-500 mt-1">Urus senarai klausa borang, pengurusan sesi dan borang audit, dan pantau status keseluruhan audit.</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.templat.index') }}" class="inline-flex items-center px-5 py-2 bg-[#003366] text-white text-xs font-bold uppercase rounded-md shadow hover:bg-blue-800 transition">
                                    + Urus Klausa
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="analitik-global" class="scroll-mt-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white rounded-lg shadow-sm p-6 border-t-4 border-[#003366] relative overflow-hidden">
                                <div class="text-sm font-medium text-gray-500 uppercase">Borang Audit Selesai</div>
                                <div class="mt-2 text-4xl font-extrabold text-gray-800">{{ $statistik['borang_selesai'] }}</div>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm p-6 border-t-4 border-green-600 relative overflow-hidden">
                                <div class="text-sm font-medium text-gray-500 uppercase">Juruaudit Berdaftar</div>
                                <div class="mt-2 text-4xl font-extrabold text-gray-800">{{ $statistik['juruaudit'] }}</div>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm p-6 border-t-4 border-yellow-500 relative overflow-hidden">
                                <div class="text-sm font-medium text-gray-500 uppercase">Bilangan Klausa (Aktif)</div>
                                <div class="mt-2 text-4xl font-extrabold text-gray-800">{{ $statistik['templat'] }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                                <h4 class="font-bold text-gray-700 mb-4 border-b pb-2">Status Borang Audit</h4>
                                <div class="relative h-64 w-full flex justify-center">
                                    <canvas id="cartaStatus"></canvas>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                                <h4 class="font-bold text-gray-700 mb-4 border-b pb-2">5 Lokasi Paling Kerap Diaudit</h4>
                                <div class="relative h-64 w-full">
                                    <canvas id="cartaLokasi"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pengurusan-sesi" class="scroll-mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                            <h3 class="text-md font-bold text-gray-800 uppercase tracking-widest">Pengurusan Sesi Audit</h3>
                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Maklumat Sesi</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tarikh</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Tindakan Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($semuaSesi as $sesi)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $sesi->tajuk_sesi }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($sesi->tarikh_mula)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sesi->tarikh_tamat)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-[12px] font-bold uppercase rounded-full {{ $sesi->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ str_replace('_', ' ', $sesi->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right flex justify-end space-x-2">
                                                <a href="{{ route('admin.sesi.edit', $sesi->id) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 shadow-sm transition">Edit</a>
                                                <form action="{{ route('admin.sesi.destroy', $sesi->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" onclick="return confirm('AWAS! Memadam Sesi akan turut memadam SEMUA borang di bawahnya. Teruskan?')" class="px-3 py-1.5 bg-red-600 text-white rounded text-xs font-bold hover:bg-red-700 shadow-sm transition">Padam</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">Tiada rekod sesi di dalam sistem.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-6 py-4 border-t bg-gray-50">{{ $semuaSesi->links() }}</div>
                        </div>
                    </div>

                    <div id="pengurusan-borang" class="scroll-mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-4 bg-gray-50 border-b flex justify-between items-center gap-4">
                            <h3 class="text-md font-bold text-gray-800 uppercase tracking-widest">Pengurusan Borang Audit Lapangan</h3>
                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tarikh</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tajuk Sesi</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Juruaudit / Auditee</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Lokasi</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($semuaBorang as $borang)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-3 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($borang->updated_at)->format('d/m/Y') }}</td>
                                            
                                            <td class="px-6 py-4 text-sm font-bold text-[#003366] uppercase">
                                                {{ optional(\App\Models\SesiAudit::find($borang->sesi_audit_id))->tajuk_sesi ?? 'Tiada Sesi' }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <span class="font-bold block">{{ $borang->namaJuruaudit->name ?? 'Tiada Nama' }}</span>
                                                <span class="text-[12px] text-gray-500 block">Kpd: {{ $borang->nama_auditee ?? '-' }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-700">{{ $borang->bahagian_cawangan }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-[12px] font-bold uppercase rounded-full {{ $borang->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ str_replace('_', ' ', $borang->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right flex justify-end space-x-2">
                                                <a href="{{ route('admin.borang.edit', $borang->id) }}" class="px-3 py-1.5 bg-gray-600 text-white rounded text-xs font-bold hover:bg-black shadow-sm transition">Edit</a>
                                                <form action="{{ route('admin.borang.destroy', $borang->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Pasti mahu memadam borang ini secara kekal?')" class="px-3 py-1.5 bg-red-600 text-white rounded text-xs font-bold hover:bg-red-700 shadow-sm transition">Padam</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 italic">Tiada borang direkodkan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-6 py-4 border-t bg-gray-50">{{ $semuaBorang->links() }}</div>
                        </div>
                    </div>

                </div> </div> </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataStatusRaw = @json($dataStatus);
            const dataLokasiRaw = @json($dataLokasi);

            const statusLabels = Object.keys(dataStatusRaw).map(label => label.replace('_', ' ').toUpperCase());
            const statusData = Object.values(dataStatusRaw);
            
            if(statusData.length > 0) {
                new Chart(document.getElementById('cartaStatus'), {
                    type: 'doughnut',
                    data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: ['#EF4444', '#0bf555', '#3B82F6', '#efe814'], borderWidth: 1 }] },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            const lokasiLabels = Object.keys(dataLokasiRaw).map(label => label.length > 25 ? label.substring(0, 25) + '...' : label);
            const lokasiData = Object.values(dataLokasiRaw);

            if(lokasiData.length > 0) {
                new Chart(document.getElementById('cartaLokasi'), {
                    type: 'bar',
                    data: { labels: lokasiLabels, datasets: [{ label: 'Jumlah Borang Diaudit', data: lokasiData, backgroundColor: '#2a8ae9', borderRadius: 4 }] },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }, plugins: { legend: { display: false } } }
                });
            }
        });
    </script>
</x-app-layout>