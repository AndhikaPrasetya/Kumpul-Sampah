@extends('layouts.layout')
@section('content')
    {{-- @include('layouts.breadcrumb') --}}
    <section class="content m-5">
        <div class="card card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Tambah data baru Kelurahan</h3>
            </div>
            <form id="createFormKelurahan">
                @csrf
                  <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control shadow-sm" name="name" id="name" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control shadow-sm" name="email" id="email"
                                   required>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="password">password</label>
                                <input type="text" class="form-control shadow-sm" name="password" id="password">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="no_phone">Nomer Handphone</label>
                                <input type="text" class="form-control shadow-sm" name="no_phone" id="no_phone"
                                    placeholder="0812XXXXXX" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label for="kecamatan">kecamatan</label>
                                <input type="text" class="form-control shadow-sm" name="kecamatan" id="kecamatan" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label for="kota">kota</label>
                                <input type="text" class="form-control shadow-sm" name="kota" id="kota" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="provinsi">provinsi</label>
                                <input type="text" class="form-control shadow-sm" name="provinsi" id="provinsi" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="alamat">Alamat</label>
                            <textarea type="text" class="form-control shadow-sm" name="alamat" id="alamat"
                            placeholder="Jl.kayu"></textarea>
                        </div>
                    </div>
                </div>

                <div class="cardoter bg-light">
                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary px-4 mr-1">
                            <i class="fas fa-save mr-1"></i> Submit
                        </button>
                        <button type="button" 
                                onclick="window.location.href='{{ route('kelurahan.index') }}'"
                                class="btn btn-warning px-4">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(() => {
            // Konfigurasi Toast
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "2000",
            };

            const showToast = (icon, message) => {
                if (icon === 'error') {
                    toastr.error(message);
                } else if (icon === 'success') {
                    toastr.success(message);
                } else if (icon === 'info') {
                    toastr.info(message);
                } else {
                    toastr.warning(message);
                }
            };

            $('#createFormKelurahan').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                console.log(formData);
                $.ajax({
                    url: '/admin/kelurahan',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.href = '/admin/kelurahan';
                        }, 2000);
                    },
                    error: (xhr) => {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (const [field, messages] of Object.entries(errors)) {
                                messages.forEach(message => {
                                    showToast('error', message);
                                });
                            }
                        } else {
                            showToast('error', xhr.responseJSON.error);
                        }
                    }
                });
            });
        });
    </script>
@endsection