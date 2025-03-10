@extends('layouts.layout')
@section('content')
    <section class="content m-5">
        <div class="card card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Edit data Sampah</h3>
            </div>
            <form id="updateFormSampah" enctype="multipart/form-data" data-id="{{ $data->id }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="nama" class="required">Nama sampah</label>
                                <input type="text" class="form-control shadow-sm" name="nama" id="nama"
                                    value="{{ $data->nama }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="category_id" class="required">Kategori sampah</label>
                                <select class="form-control" name="category_id" id="category_id">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach ($categories as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            {{ $data->category_id == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="harga" class="required">Harga per KG</label>
                                <input type="text" class="form-control shadow-sm" name="harga" id="harga"
                                    value="{{ number_format($data->harga, 0, ',', '.') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="points">Points per KG</label>
                                <input type="text" class="form-control shadow-sm" name="points" id="points"
                                    value="{{ $data->points }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="image" class="required">Gambar sampah</label>
                                <input type="file" class="dropify" name="image"
                                    data-default-file="{{ asset($data->image) }}" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" class="form-control">{{ $data->deskripsi }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary px-4 mr-1">
                                <i class="fas fa-save mr-1"></i> Submit
                            </button>
                            <button type="button" onclick="window.location.href='{{ route('sampah.index') }}'"
                                class="btn btn-warning px-4">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </section>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(() => {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "1000",
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
            $('#updateFormSampah').on('submit', function(e) {
                e.preventDefault();
                $(this).find('button[type="submit"]').prop('disabled', true);

                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                const id = $(this).data('id');

                $.ajax({
                    url: '/admin/sampah/update/' + id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.href = '/admin/list-sampah';
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
                        // Re-enable the submit button
                        $(this).find('button[type="submit"]').prop('disabled', false);
                    }
                });
            });


        });
    </script>
@endsection
