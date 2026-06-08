<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan NCR/OFI Audit JTDI</title>
    <style>
        /* Tetapan Kertas Kepada Landscape */
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        /* CSS Standard Untuk DOMPDF */
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            color: #000; 
            margin: 0;
            padding: 0;
        }
        
        /* Teks Kod Borang Penjuru Kanan Atas */
        .code-right {
            text-align: right;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        /* Bahagian Header & Logo */
        .header-table {
            width: 100%;
            margin-bottom: 5px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .title-center {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }
        .logo {
            width: 180px;
            height: 100px;
        }

        /* Jadual Utama (Menggabungkan Maklumat & Item Semakan) */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.main-table th, table.main-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }
        table.main-table th {
            text-align: center;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
            background-color: #f2f2f2;
        }
        
        /* Gaya Teks Dalam Jadual */
        .label-text {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .data-text {
            font-size: 12px;
            text-transform: uppercase;
        }
        
        ul { margin-top: 0; margin-bottom: 0; padding-left: 15px; text-align: justify; }
        li { margin-bottom: 4px; }
    </style>
</head>
<body>

    <div class="code-right">JPKN-U-03/B3</div>

    <table class="header-table">
        <tr>
            <td width="20%">
                <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo JPKN">
            </td>
            <td width="60%" class="title-center">
                LAPORAN PENEMUAN KETIDAKPATUHAN (NCR) & PENAMBAHBAIKAN (OFI)<br>
                JABATAN TEKNOLOGI DIGITAL DAN INOVASI NEGERI SABAH
            </td>
            <td width="20%"></td>
        </tr>
    </table>

    <!-- Jadual Maklumat -->
    <table class="main-table">
        <tr>
            <td colspan="2" width="60%">
                <span class="label-text">JURUAUDIT:</span><br>
                <span class="data-text">{{ $borang->juruauditDitugaskan->name ?? ($borang->namaJuruaudit->name ?? '') }}</span>
            </td>
            <td colspan="2" width="40%">
                <span class="label-text">TARIKH:</span><br>
                <span class="data-text">{{ \Carbon\Carbon::parse($borang->updated_at)->format('d/m/Y') }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span class="label-text">BAHAGIAN/ CAWANGAN/ KSIT:</span><br>
                <span class="data-text">{{ $borang->bahagian_cawangan }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span class="label-text">AUDITEE:</span><br>
                <span class="data-text">{{ $borang->nama_auditee ?? 'TIDAK DINYATAKAN' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; color: #000;">
                <span class="label-text">JUMLAH KESELURUHAN NCR: </span> <span style="font-size: 16px; font-weight: bold;">{{ $totalNcr }}</span>
            </td>
            <td colspan="2" style="text-align: center; color: #000;">
                <span class="label-text">JUMLAH KESELURUHAN OFI: </span> <span style="font-size: 16px; font-weight: bold;">{{ $totalOfi }}</span>
            </td>
        </tr>
    </table>

    <!-- Jadual Senarai Penemuan -->
    <table class="main-table">
        <tr>
            <th width="10%">KLAUSA</th>
            <th width="45%">KETIDAKPATUHAN (NCR)</th>
            <th width="45%">PENAMBAHBAIKAN (OFI)</th>
        </tr>

        @forelse($senaraiItem as $item)
            @php
                $ncrArr = json_decode($item->ncr_details, true) ?? [];
                $ofiArr = json_decode($item->ofi_details, true) ?? [];
            @endphp
            <tr>
                <td style="text-align: center; vertical-align: top;">
                    <strong>{{ $item->no_klausa }}</strong><br>
                </td>
                
                <td style="text-align: justify; vertical-align: top;">
                    @if(count($ncrArr) > 0)
                        <ul>
                            @foreach($ncrArr as $ncr)
                                <li>{{ $ncr }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div style="text-align: center; font-style: italic; color: #777;">- Tiada NCR -</div>
                    @endif
                </td>
                
                <td style="text-align: justify; vertical-align: top;">
                    @if(count($ofiArr) > 0)
                        <ul>
                            @foreach($ofiArr as $ofi)
                                <li>{{ $ofi }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div style="text-align: center; font-style: italic; color: #777;">- Tiada OFI -</div>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 20px; font-style: italic;">
                    Tiada sebarang rekod penemuan NCR atau OFI bagi sesi ini.
                </td>
            </tr>
        @endforelse
    </table>

</body>
</html>