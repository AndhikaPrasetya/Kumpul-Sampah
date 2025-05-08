<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penarikan Saldo - BANK SAMPAH UNIT {{ strtoupper($user->user->name) }}</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2e8b57;
            padding-bottom: 10px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        h3 { 
            color: #2e8b57;
            margin: 5px 0;
            font-size: 18px;
        }
        .subtitle {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }
        .periode {
            background-color: #f0f8ff;
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
            background-color: #2e8b57;
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
            background-color: #e6f7e6 !important;
        }
    </style>
</head>
<body>
    <div class="header">

        <h3>BANK SAMPAH UNIT {{ strtoupper($user->user->name) }}</h3>
        <div class="subtitle">{{$user->alamat}}</div>
        <div class="subtitle">Telp: {{$user->user->no_phone}} | Email: {{$user->user->email}}</div>
        
        <div style="margin-top: 15px;">
            <h3>LAPORAN PENARIKAN SALDO {{ strtoupper($user->user->name) }}</h3>
            <div class="periode">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Nasabah</th>
                <th>Jumlah Withdraw (Rp)</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $withdraw)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $withdraw->user->name }}</td>
                    <td>Rp {{ number_format($withdraw->amount, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($withdraw->created_at)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}<br>
        BANK SAMPAH UNIT {{ strtoupper($user->user->name) }}- Mengubah Sampah Menjadi Berkah
    </div>
</body>
</html>