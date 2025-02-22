@extends('layouts.layout')
@section('content')
    <section class="content m-5">
        <div class="card card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Create New User</h3>
            </div>
            <form id="formSetting" action="{{ route('website-settings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="website_name">Website Name</label>
                        <input type="text" name="website_name" id="website_name" class="form-control shadow-sm"
                            value="{{ $settings->website_name ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="website_description">Website Description</label>
                        <textarea name="website_description" id="website_description" class="form-control shadow-sm">{{ $settings->website_description ?? '' }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="logo">Logo</label>
                                <div class="img-wrapper mb-3">

                                    @if ($settings && $settings->logo)
                                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="logo" width="100">
                                    @else
                                        <p><i>Anda belum mengupload logo</i></p>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input shadow-sm" name="logo"
                                            id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="favicon">favicon</label>
                                <div class="img-wrapper mb-3">
                                    @if ($settings && $settings->favicon)
                                    <img src="{{ asset('storage/' . $settings->favicon) }}" alt="favicon" width="100">
                                    @else
                                        <p><i>Anda belum mengupload favicon</i></p>
                                    @endif
                                </div>
                               
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input shadow-sm" name="favicon"
                                            id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary px-4 mr-1">
                            <i class="fas fa-save mr-1"></i> Simpan
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

            $('#formSetting').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '/website-settings/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.reload();
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
