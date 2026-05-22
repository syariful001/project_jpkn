<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Audit JTDI</title>
    <style>
        /* CSS Standard Untuk DOMPDF */
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            color: #000; 
            margin: 0;
            padding: 0;
        }
        
        /* Teks Kod Borang Penjuru Kanan Atas */
        .code-right {
            text-align: right;
            font-size: 11px;
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
            width: 130px;
            height: 70px;
        }

        /* Jadual Utama (Menggabungkan Maklumat & Item Semakan) */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
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
        .num-bullet {
            font-weight: bold;
            margin-right: 4px;
        }
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
                SENARAI SEMAK AUDIT DALAMAN<br>
                JABATAN TEKNOLOGI DIGITAL DAN INOVASI NEGERI SABAH
            </td>
            <td width="20%"></td>
        </tr>
    </table>

    <table class="main-table">
        <tr>
            <td colspan="3">
                <span class="label-text">JURUAUDIT:</span><br>
                <span class="data-text">{{ $borang->namaJuruaudit->name ?? '' }}</span>
            </td>
            <td colspan="1">
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
            <th width="6%">KLAUSA</th>
            <th width="37%">PERKARA-PERKARA UNTUK DIPERIKSA</th>
            <th width="47%">RESPON/ BUKTI/ PENEMUAN/ ULASAN</th>
            <th width="10%">RUJUKAN</th>
        </tr>

        @forelse($senaraiItem as $item)
            @php
                // Pisahkan data yang disambung menggunakan baris baru (\n)
                $rawPerkara = trim($item->perkara_periksa ?? '');
                $rawUlasan = trim($item->ulasan ?? '');
                
                $arrPerkara = $rawPerkara !== '' ? explode("\n", $rawPerkara) : [];
                $arrUlasan = $rawUlasan !== '' ? explode("\n", $rawUlasan) : [];
                
                // Cari jumlah baris terbanyak antara Perkara atau Ulasan
                $count = max(count($arrPerkara), count($arrUlasan), 1);
            @endphp

            @for ($i = 0; $i < $count; $i++)
                @php
                    // Tetapkan "-" jika baris tersebut kosong
                    $valP = isset($arrPerkara[$i]) && trim($arrPerkara[$i]) !== '' ? trim($arrPerkara[$i]) : '-';
                    $valU = isset($arrUlasan[$i]) && trim($arrUlasan[$i]) !== '' ? trim($arrUlasan[$i]) : '-';
                @endphp
                <tr>
                    @if ($i === 0)
                        <td style="text-align: center; vertical-align: middle;" rowspan="{{ $count }}">{{ $item->no_klausa }}</td>
                    @endif
                    
                    <td style="text-align: justify;">
                        @if($count > 1)<span class="num-bullet">{{ $i + 1 }}.</span>@endif {{ $valP }}
                    </td>
                    
                    <td style="text-align: justify;">
                        @if($count > 1)<span class="num-bullet">{{ $i + 1 }}.</span>@endif {{ $valU }}
                    </td>
                    
                    @if ($i === 0)
                        <td style="text-align: center; vertical-align: middle;" rowspan="{{ $count }}">{{ $item->rujukan ?? '-' }}</td>
                    @endif
                </tr>
            @endfor
        @empty
        <tr>
            <td colspan="4" style="text-align: center; padding: 20px; font-style: italic;">
                Tiada rekod penemuan bagi sesi ini.
            </td>
        </tr>
        @endforelse
    </table>

</body>
</html>