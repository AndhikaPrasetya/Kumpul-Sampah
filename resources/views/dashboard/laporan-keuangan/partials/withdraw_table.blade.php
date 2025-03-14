<table  class="table table-bordered table-hover ">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Nasabah</th>
            <th>Jumlah Withdraw</th>
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
