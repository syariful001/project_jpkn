<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-b-4 border-blue-800 pb-2 inline-block">
            {{ __('Papan Pemuka Sistem Audit Digital JTDI') }}
        </h2>
    </x-slot>

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (Auth::user()->peranan === 'ketua_juruaudit')
                
                <div class="flex flex-col md:flex-row gap-6">
                    
                    <div class="w-full md:w-1/4">
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 sticky top-6">
                            <div class="p-4 bg-[#003366] text-white rounded-t-lg font-bold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                Menu
                            </div>

                            @php
                                    $menungguSemakan = \App\Models\BorangAudit::where('ketua_juruaudit_id', Auth::id())
                                                                ->where('status', 'siap_disemak')
                                                                ->count();
                                @endphp

                            <div class="p-2 space-y-1">
                                <a href="#sesi-audit" class="flex justify-between items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 rounded-md transition font-semibold">
                                    <span>Sesi Audit</span>
                                    @if($menungguSemakan > 0)
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                                            {{ $menungguSemakan }}
                                        </span>
                                    @endif
                                </a>

                                @php
                                    $tugasKetuaBerjalan = \App\Models\BorangAudit::where('juruaudit_ditugaskan_id', Auth::id())
                                                                ->where('status', '!=', 'selesai')
                                                                ->count();
                                @endphp
                                
                                <a href="#tugasan-kendiri" class="flex justify-between items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 rounded-md transition font-semibold">
                                    <span>Peti Masuk Tugasan</span>
                                    
                                    @if($tugasKetuaBerjalan > 0)
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                                            {{ $tugasKetuaBerjalan }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-3/4 space-y-6">
                        
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-[#003366]">
                            <div class="p-6 text-gray-900 flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-bold text-[#003366]">Selamat Datang, Ketua Juruaudit {{ Auth::user()->name }}!</h3>
                                    <p class="text-sm text-gray-500 mt-1">Gunakan paparan ini untuk memantau kemajuan pasukan dan mengurus sesi audit jabatan.</p>
                                </div>
                                <div class="space-x-2">
                                    <a href="{{ route('ketua.sesi.create') }}" class="inline-flex items-center px-4 py-2 bg-[#003366] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 shadow-md transition">
                                        + Cipta Sesi Audit Baru
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-purple-600 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] text-gray-600 font-bold uppercase tracking-wider">Sesi Dijalankan</p>
                                    <p class="text-2xl font-extrabold text-gray-800">{{ $statistik['jumlah_sesi'] }}</p>
                                </div>
                                <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" /></svg>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-blue-500 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] text-gray-600 font-bold uppercase tracking-wider">Total Borang</p>
                                    <p class="text-2xl font-extrabold text-gray-800">{{ $statistik['total_borang'] }}</p>
                                </div>
                                <div class="p-2 bg-blue-50 text-blue-500 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-green-500 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] text-gray-600 font-bold uppercase tracking-wider">Audit Siap</p>
                                    <p class="text-2xl font-extrabold text-green-600">{{ $statistik['borang_selesai'] }}</p>
                                </div>
                                <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-red-500 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] text-gray-600 font-bold uppercase tracking-wider">Belum Siap</p>
                                    <p class="text-2xl font-extrabold text-red-600">{{ $statistik['borang_belum'] }}</p>
                                </div>
                                <div class="p-2 bg-red-50 text-red-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div id="sesi-audit" class="scroll-mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                            <div class="p-6 text-gray-900 bg-[#003366] border-b flex justify-between items-center">
                                <h4 class="font-bold text-white text-[16px] uppercase">Sesi Audit</h4>
                            </div>
                            
                            <div class="p-0">
                                @if($senaraiSesi->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-widest w-1/4">Maklumat Sesi</th>
                                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-widest w-1/4">Status Sesi</th>
                                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-widest w-1/2">Pemantauan Pasukan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($senaraiSesi as $sesi)
                                                <tr class="hover:bg-blue-50/50 transition duration-150">
                                                    <td class="px-6 py-5 align-top">
                                                        <div class="text-base font-extrabold text-[#003366] uppercase">{{ $sesi->tajuk_sesi }}</div>
                                                        <div class="text-xs text-gray-500 mt-1.5 font-medium">
                                                            {{ \Carbon\Carbon::parse($sesi->tarikh_mula)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sesi->tarikh_tamat)->format('d/m/Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-5 whitespace-nowrap align-top">
                                                        @if($sesi->status == 'dirancang')
                                                            <span class="px-3 py-1 inline-flex text-[12px] font-bold uppercase rounded-full bg-gray-100 text-gray-800 border border-gray-200">Dirancang</span>
                                                        @elseif($sesi->status == 'siap')
                                                            <span class="px-3 py-1 inline-flex text-[12px] font-bold uppercase rounded-full bg-blue-100 text-blue-800 border border-blue-200">Sedang Berjalan / Semakan</span>
                                                        @elseif($sesi->status == 'selesai')
                                                            <span class="px-3 py-1 inline-flex text-[12px] font-bold uppercase rounded-full bg-green-100 text-green-800 border border-green-200">Selesai</span>
                                                        @else
                                                            <span class="px-3 py-1 inline-flex text-[12px] font-bold uppercase rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">{{ ucfirst(str_replace('_', ' ', $sesi->status)) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-5 align-top">
                                                        <div class="grid grid-cols-1 gap-3">
                                                            @forelse($sesi->senaraiBorang as $borang)
                                                                <div class="flex items-center justify-between bg-white p-3 rounded-md border border-gray-200 shadow-sm hover:border-blue-300 transition">
                                                                    <div class="text-left w-3/4">
                                                                        <span class="block font-bold text-gray-800 text-sm uppercase">👤 {{ $borang->namaJuruaudit->name ?? 'Tiada Nama' }}</span>
                                                                        
                                                                        <span class="block text-xs font-semibold text-gray-600 mt-1 truncate" title="{{ $borang->bahagian_cawangan }}">
                                                                            📍 {{ $borang->bahagian_cawangan ?? 'Lokasi Tidak Dinyatakan' }}
                                                                        </span>

                                                                        <span class="text-[12px] uppercase font-bold px-3 py-1 mt-2 inline-block rounded shadow-sm
                                                                            {{ $borang->status == 'ditugaskan' ? 'bg-gray-100 gray-700 border border-gray-200' : 
                                                                              ($borang->status == 'sedang_diisi' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 
                                                                              ($borang->status == 'siap_disemak' ? 'bg-red-100 text-red-700 border border-red-200 animate-pulse' : 
                                                                              'bg-green-100 text-green-700 border border-green-200')) }}">
                                                                            {{ str_replace('_', ' ', $borang->status) }}
                                                                        </span>
                                                                    </div>
                                                                    <a href="{{ route('ketua.semakan.show', $borang->id) }}" class="px-4 py-2 bg-gray-800 text-white rounded text-xs font-bold hover:bg-black uppercase shadow-sm flex-shrink-0">
                                                                        Buka
                                                                    </a>
                                                                </div>
                                                            @empty
                                                                <span class="text-gray-400 text-xs italic">Tiada juruaudit ditugaskan.</span>
                                                            @endforelse
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($senaraiSesi->hasPages())
                                        <div class="px-6 py-4 bg-white border-t border-gray-200">
                                            {{ $senaraiSesi->links() }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-12 text-gray-400 italic">
                                        <p>Belum ada sebarang sesi audit dicipta buat masa ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(isset($senaraiTugasan) && $senaraiTugasan->count() > 0)
                        <div id="tugasan-kendiri" class="scroll-mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                            <div class="p-6 bg-[#003366] border-b border-green-100 flex justify-between items-center">
                                <h4 class="font-bold text-white uppercase text-[16px]">Peti Masuk Tugasan Audit</h4>
                            </div>
                            <div class="p-0">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tarikh</th>
                                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tajuk Sesi</th>
                                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Cawangan Diaudit</th>
                                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Status</th>
                                                <th class="px-8 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Tindakan</th>
                                            </tr>
                                        </thead>

                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($senaraiTugasan as $tugasan)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">

                                                <td class="px-5 py-3 text-sm text-gray-500">
                                                    {{ $tugasan->created_at->format('d/m/Y') }}
                                                </td>

                                                <td class="px-5 py-3 text-sm font-semibold text-[#003366] uppercase">
                                                    {{ optional(\App\Models\SesiAudit::find($tugasan->sesi_audit_id))->tajuk_sesi ?? 'Tiada Sesi' }}
                                                </td>

                                                <td class="px-5 py-3 text-sm font-medium text-gray-800">
                                                    {{ $tugasan->bahagian_cawangan }}
                                                </td>

                                                <td class="px-5 py-3">
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                                        {{ $tugasan->status == 'selesai' 
                                                            ? 'bg-green-100 text-green-700' 
                                                            : 'bg-red-100 text-red-700 animate-pulse'}}">
                                                        {{ strtoupper(str_replace('_', ' ', $tugasan->status)) }}
                                                    </span>
                                                </td>

                                                <td class="px-5 py-3 text-right">
                                                    <a href="{{ route('juruaudit.borang.show', $tugasan->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-green-600 rounded-md hover:bg-green-700 transition">
                                                        Mula Audit
                                                    </a>
                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($senaraiTugasan->hasPages())
                                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                                        {{ $senaraiTugasan->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div> 
                </div> 
                
            @elseif (Auth::user()->peranan === 'juruaudit')

                <div class="flex flex-col md:flex-row gap-6">
                    
                    <div class="w-full md:w-1/4">
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 sticky top-6">
                            <div class="p-4 bg-green-600 text-white rounded-t-lg font-bold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                Menu
                            </div>
                            <div class="p-2 space-y-1">
                                <a href="#analitik-juruaudit" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 rounded-md transition font-semibold">
                                    Statistik Kendiri
                                </a>

                                @php
                                    $tugasanBaru = \App\Models\BorangAudit::where('juruaudit_ditugaskan_id', Auth::id())
                                                                ->where('status', 'ditugaskan')
                                                                ->count();
                                @endphp

                                <a href="#tugasan-borang" class="flex justify-between items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 rounded-md transition font-semibold">
                                    <span>Peti Masuk Tugasan Borang</span>
                                    
                                    @if($tugasanBaru > 0)
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                                            {{ $tugasanBaru }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-3/4 space-y-6">

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-600">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-bold text-green-700">Selamat Datang, Juruaudit {{ Auth::user()->name }}!</h3>
                                <p class="text-sm text-gray-500 mt-1">Sila lengkapkan tugasan audit lapangan anda mengikut senarai di bawah.</p>
                            </div>
                        </div>

                        <div id="analitik-juruaudit" class="scroll-mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-gray-600 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-600 font-bold uppercase">Borang Audit Keseluruhan</p>
                                    <p class="text-3xl font-extrabold text-gray-800">{{ $statistik['jumlah_keseluruhan'] }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 text-gray-500 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-yellow-500 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-600 font-bold uppercase">Borang Ditugaskan</p>
                                    <p class="text-3xl font-extrabold text-yellow-600">{{ $statistik['jumlah_berjalan'] }}</p>
                                </div>
                                <div class="p-3 bg-yellow-50 text-yellow-500 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-green-600 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-600 font-bold uppercase">Borang Telah Siap</p>
                                    <p class="text-3xl font-extrabold text-green-600">{{ $statistik['jumlah_selesai'] }}</p>
                                </div>
                                <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div id="tugasan-borang" class="scroll-mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                            <div class="p-6 bg-green-600 border-b flex justify-between items-center">
                                <h4 class="font-bold text-white uppercase text-[16px]">Peti Masuk Tugasan Borang Audit</h4>
                            </div>
                            <div class="p-0">
                                @if($senaraiTugasan->count() > 0)
                                    <div class="overflow-x-auto">
                                    <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tarikh</th>
                                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tajuk Sesi</th>
                                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Cawangan / Lokasi</th>
                                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Status Borang</th>
                                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Tindakan</th>
                                            </tr>
                                        </thead>

                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($senaraiTugasan as $tugasan)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                
                                                <td class="px-5 py-3 text-sm text-gray-500">
                                                    {{ $tugasan->created_at->format('d/m/Y') }}
                                                </td>

                                                <td class="px-5 py-3 text-sm font-semibold text-[#003366] uppercase">
                                                    {{ optional(\App\Models\SesiAudit::find($tugasan->sesi_audit_id))->tajuk_sesi ?? 'Tiada Sesi' }}
                                                </td>

                                                <td class="px-5 py-3 text-sm font-medium text-gray-800 uppercase">
                                                    {{ $tugasan->bahagian_cawangan }}
                                                </td>

                                                <td class="px-5 py-3">
                                                        <span class="px-3 py-1.5 inline-flex text-[12px] uppercase font-bold rounded-full border shadow-sm
                                                            {{ $tugasan->status == 'ditugaskan' ? 'bg-red-100 text-red-700 border-red-200 animate-pulse' : 
                                                              ($tugasan->status == 'sedang_diisi' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : 
                                                              ($tugasan->status == 'siap_disemak' ? 'bg-gray-100 text-gray-700 border-gray-200' : 
                                                              'bg-green-100 text-green-700 border-green-200')) }}">
                                                            {{ str_replace('_', ' ', $tugasan->status) }}
                                                        </span>
                                                </td>

                                                <td class="px-1 py-3 text-right">
                                                    <a href="{{ route('juruaudit.borang.show', $tugasan->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-[#003366] rounded-md hover:bg-blue-900 transition">
                                                        {{ $tugasan->status == 'selesai' || $tugasan->status == 'siap_disemak' ? 'Lihat Borang' : 'Mula Audit' }}
                                                    </a>
                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                    @if($senaraiTugasan->hasPages())
                                        <div class="px-6 py-4 bg-white border-t border-gray-200">
                                            {{ $senaraiTugasan->links() }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-12 text-gray-400 italic">
                                        <p>Tiada sebarang borang audit ditugaskan kepada anda buat masa ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div> 
                </div> 
            @endif
        </div>
    </div>
</x-app-layout>