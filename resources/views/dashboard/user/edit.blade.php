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
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item">Edit</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card card-primary">
            <form id="updateFormUser" data-id="{{ $data->id }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ $data->name }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ $data->email }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="password">New password</label>
                                <input type="text" class="form-control" name="password" id="password">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="no_phone">Nomer Handphone</label>
                                <input type="text" class="form-control" name="no_phone" id="no_phone"
                                    placeholder="0812XXXXXX" value="{{$data->no_phone}}">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" name="alamat" id="alamat"
                            placeholder="Jl.kayu" value="{{$data->alamat}}">
                        </div>
                    </div>
                    <div class="row">

                        {{-- <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="roles">Roles</label>
                                <select @cannot('create-user') disabled @endcannot class="allRole" name="roles[]"
                                    multiple="multiple" style="width: 100%;">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}"
                                            {{ in_array($role, $userRole) ? 'selected' : '' }}>
                                            {{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @cannot('create-user')
                                @foreach ($userRole as $role)
                                    <input type="hidden" name="roles[]" value="{{ $role }}">
                                @endforeach
                            @endcannot
                        </div> --}}
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="photo">Foto Profile</label>
                                <div class="img-wrapper mb-3">
                                    @if (!empty($data->photo))
                                        <img src="{{ asset($data->photo) }}" alt="image" width="100px">
                                    @else
                                        <p><i>Anda belum mengupload foto</i></p>
                                    @endif
                                </div>
                                    <div class="input-group">
                                        <div class="custom-file">
                                          <input type="file" class="custom-file-input" name="photo" id="exampleInputFile">
                                          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                          <span class="input-group-text">Upload</span>
                                        </div>
                                      </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <button type="button"
                        onclick="window.location.href='{{ route('users.index') }}'" class="btn btn-warning"><span>Back</span></button>
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
                // // Buat FormData object
                const formData = new FormData(form[0]);
                // Tambahkan method PUT karena route menggunakan PUT
                formData.append('_method', 'PUT');

                $.ajax({
                    url: `/users/update/${id}`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
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

            $('#updateFormUser').on('submit', function(e) {
                e.preventDefault();
                handleFormSubmit('updateFormUser');
            });



        });
    </script>
@endsection
