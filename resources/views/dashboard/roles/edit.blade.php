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
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item">Edit</li>

                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="card card-primary">
            <form id="updateFormRole" data-id="{{ $role->id }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ $role->name }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="permission-section">
                        <label>Set Permission</label>
                        <div class="row">
                            <div class="col-md-12"></div>
                            @foreach ($permissionGroups as $group => $permissions)
                                <div class="col-md-3 mb-2 mt-2">
                                    <label>{{ $group }}</label>
                                    @foreach ($permissions as $key => $permission)
                                        <div class="form-group">
                                            <div class="form-check">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox"
                                                        id="customCheckbox{{ $group }}{{ $key }}"
                                                        name="permission[]" value="{{ $permission->name }}"
                                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                    <label for="customCheckbox{{ $group }}{{ $key }}"
                                                        class="custom-control-label">{{ $permission->name }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <button type="button" onclick="window.location.href='{{ route('roles.index') }}'"
                            class="btn btn-warning">
                            <span>Back</span>
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
                //get id form
                const form = $(`#${formId}`);
                //get id user
                const id = form.data('id');

                $.ajax({
                    url: `/roles/update/${id}`,
                    type: 'PUT',
                    data: form.serialize(),
                    success: (response) => {
                        if (response.status) {
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
                            showToast('error', xhr.responseJSON.error);
                        }
                    }
                });
            };

            $('#updateFormRole').on('submit', function(e) {
                e.preventDefault();
                handleFormSubmit('updateFormRole');
            });
        });
    </script>
@endsection
