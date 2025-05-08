<table  class="table table-bordered table-hover ">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Nasabah</th>
            <th>Jumlah Setor (KG)</th>
            <th>Pendapatan</th>
            <th>Poin</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $transaction->users->name }}</td>
                <td>{{ number_format($transaction->details->sum('berat'), 0, ',', '.')}} KG</td>
                <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                <td>{{ number_format($transaction->total_points, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d-m-Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
