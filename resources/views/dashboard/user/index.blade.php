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
                        <li class="breadcrumb-item active">Users</li>
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
                            <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create
                                Users</a>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-hover " id="table_users">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class="w-25 text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>

                            <!-- Modal View User -->

                            <div class="modal fade" id="modal-lg" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h4 class="modal-title">Personal Information</h4>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                            
                                            <div id="user-details" class="d-none">
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label font-weight-bold">Name</label>
                                                            <div class="col-sm-8">
                                                                <p class="form-control-plaintext" id="name">-</p>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label font-weight-bold">Email</label>
                                                            <div class="col-sm-8">
                                                                <p class="form-control-plaintext" id="email">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label font-weight-bold">Phone</label>
                                                            <div class="col-sm-8">
                                                                <p class="form-control-plaintext" id="no_phone">-</p>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label font-weight-bold">Role</label>
                                                            <div class="col-sm-8">
                                                                <p class="form-control-plaintext" id="roles">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="font-weight-bold mb-3">Address</h6>
                                                        <p class="form-control-plaintext" id="alamat">-</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                              <!-- Modal View User -->

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

                // Tampilkan roles (jika ada)
                if (response.roles && response.roles.length > 0) {
                    $('#roles').text(response.roles.join(', '));
                } else {
                    $('#roles').text('-');
                }

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