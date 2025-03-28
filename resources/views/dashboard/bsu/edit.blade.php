@extends('layouts.layout')
@section('content')
    <section class="content m-5">
        <div class="card card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Edit BSU</h3>
            </div>
            <form id="updateFormBsu" data-id="{{ $bsu->id }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control shadow-sm" name="name" id="name"
                                    value="{{ $bsu->name }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control shadow-sm" name="email" id="email"
                                    value="{{ $bsu->email }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="password">New password</label>
                                <input type="text" class="form-control shadow-sm" name="password" id="password">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="no_phone">Nomer Handphone</label>
                                <input type="text" class="form-control shadow-sm" name="no_phone" id="no_phone"
                                    placeholder="0812XXXXXX" value="{{$bsu->no_phone}}">
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label for="rt">RT</label>
                                <input type="text" class="form-control shadow-sm" name="rt" id="rt" value="{{$bsuDetail->rt}}">
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label for="rw">RW</label>
                                <input type="text" class="form-control shadow-sm" name="rw" id="rw" value="{{$bsuDetail->rw}}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="kelurahan">Kelurahan</label>
                                <input type="text" class="form-control shadow-sm" name="kelurahan" id="kelurahan" value="{{$bsuDetail->kelurahan}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control shadow-sm" name="alamat" id="alamat"
                            placeholder="Jl.kayu" value="{{$bsuDetail->alamat}}">
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary px-4 mr-1">
                            <i class="fas fa-save mr-1"></i> Submit
                        </button>
                        <button type="button" 
                                onclick="window.location.href='{{ route('bsu.index') }}'"
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
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right", // Posisi toast
                "timeOut": "2000",
            };

            const showToast = (icon, message) => {
                if (icon === 'error') {
                    toastr.error(message); // Toast untuk error
                } else if (icon === 'success') {
                    toastr.success(message); // Toast untuk sukses
                } else if (icon === 'info') {
                    toastr.info(message); // Toast untuk info
                } else {
                    toastr.warning(message); // Toast untuk warning
                }
            };


            const handleFormSubmit = (formId) => {
                const form = $(`#${formId}`);
                const id = form.data('id');
               
                // const formData = new FormData(form[0]);
                // // Tambahkan method PUT karena route menggunakan PUT
                // formData.append('_method', 'PUT');

                $.ajax({
                    url: `/admin/bsu/update/${id}`,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:form.serialize(),
                    success: (response) => {
                        if (response.success) {
                            showToast('success', response.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            showToast('error', response.message);
                        }
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
                            showToast('error', 'An unexpected error occurred.');
                        }
                    }
                });
            };

            $('#updateFormBsu').on('submit', function(e) {
                e.preventDefault();
                handleFormSubmit('updateFormBsu');
            });



        });
    </script>
@endsection
