<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - KELURAHAN {{ strtoupper($user->user->name) }}</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        h3 { 
            color: #007bff;
            margin: 5px 0;
            font-size: 18px;
        }
        .subtitle {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }
        .periode {
            background-color: #e6f0ff;
            padding: 8px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 0;
            font-weight: bold;
        }
        table { 
            width: 100%; 
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .total-row {
            font-weight: bold;
            background-color: #e6f7ff !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>PEMERINTAH KELURAHAN {{ strtoupper($user->user->name) }}</h3>
        <div class="subtitle">{{ $user->alamat }}</div>
        <div class="subtitle">Telp: {{ $user->user->no_phone }} | Email: {{ $user->user->email }}</div>
        
        <div style="margin-top: 15px;">
            <h3>LAPORAN TRANSAKSI BANK SAMPAH WILAYAH KELURAHAN {{ strtoupper($user->user->name) }}</h3>
            <div class="periode">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama BSU</th>
                <th width="20%">Nama Nasabah</th>
                <th width="15%">Jumlah Setor (KG)</th>
                <th width="15%">Pendapatan</th>
                <th width="10%">Poin</th>
                <th width="15%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->bsu->user->name ?? '-' }}</td>
                    <td>{{ $transaction->users->name }}</td>
                    <td>{{ number_format($transaction->details->sum('berat'), 0, ',', '.') }} KG</td>
                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    <td>{{ number_format($transaction->total_points, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d F Y') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">TOTAL</td>
                <td>{{ number_format($data->sum(fn($t) => $t->details->sum('berat')), 0, ',', '.') }} KG</td>
                <td>Rp {{ number_format($data->sum('total_amount'), 0, ',', '.') }}</td>
                <td>{{ number_format($data->sum('total_points'), 0, ',', '.') }}</td>
                <td>{{ $data->count() }} Transaksi</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}<br>
        KELURAHAN {{ strtoupper($user->user->name) }} – Mendukung Pengelolaan Sampah Berkelanjutan
    </div>
</body>
</html>
