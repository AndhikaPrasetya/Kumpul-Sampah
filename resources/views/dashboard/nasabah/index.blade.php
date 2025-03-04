@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Nasabah</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="p-3">
                            <a href="{{ route('nasabah.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah
                                nasabah</a>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-hover " id="table_nasabah">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Nomer HP</th>
                                        <th class="w-25 text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
<script>
   
   $(document).ready(function() {
    // Ketika tombol atau elemen yang memicu modal diklik
    $('.btn-show-user').on('click', function() {
        var userId = $(this).data('id'); // Ambil ID pengguna dari atribut data-id
        // Lakukan request AJAX
        $.ajax({
            url: '/users/' + userId, // Sesuaikan dengan URL endpoint Anda
            method: 'GET',
            success: function(response) {

                // Tampilkan data pengguna
                $('#name').text(response.name || '-');
                $('#email').text(response.email || '-');
                $('#no_phone').text(response.no_phone || '-');
                $('#alamat').text(response.alamat || '-');

                // Tampilkan bagian user-details
                $('#user-details').removeClass('d-none');
            },
            error: function(xhr) {

                // Tampilkan pesan error
                alert('User not found or an error occurred.');
            }
        });
    });
});



    
</script>
    
@endsection