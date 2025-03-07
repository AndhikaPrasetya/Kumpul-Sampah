@extends('layouts.layout')
@section('content')
    {{-- @include('layouts.breadcrumb') --}}
    <section class="content m-5 ">
        <div class="card card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Create New Role</h3>
            </div>
    
            <form id="createFormRole">
                @csrf
                <div class="card-body">
                    <!-- Name Input Section -->
                    <div class="row mb-4">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="name" class="form-label font-weight-bold">Name</label>
                                <input type="text" 
                                       class="form-control form-control-lg shadow-sm" 
                                       name="name" 
                                       id="name" 
                                       placeholder="Enter role name"
                                       required>
                            </div>
                        </div>
                    </div>
    
                    <!-- Permissions Table Section -->
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover shadow-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="align-middle">Permission</th>
                                            <th class="text-center align-middle">Create</th>
                                            <th class="text-center align-middle">Read</th>
                                            <th class="text-center align-middle">Update</th>
                                            <th class="text-center align-middle">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissionGroups as $group => $permissions)
                                            <tr>
                                                <td class="font-weight-bold align-middle">{{ $group }}</td>
                                                @foreach ($permissions as $key => $permission)
                                                    <td class="text-center align-middle">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" 
                                                                   type="checkbox"
                                                                   id="customCheckbox{{$group}}{{ $key }}" 
                                                                   name="permission[]"
                                                                   value="{{ $permission->name }}">
                                                            <label for="customCheckbox{{$group}}{{ $key }}"
                                                                   class="custom-control-label"></label>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Form Footer -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary px-4 mr-1">
                            <i class="fas fa-save mr-1"></i> Submit
                        </button>
                        <button type="button" 
                                onclick="window.location.href='{{ route('roles.index') }}'"
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
                "positionClass": "toast-top-right", // Posisi toast
                "timeOut": "1000",
                "closeButton": true,
                "progressBar": true,
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

            const handleCreateForm = (formId) => {
                const form = $(`#${formId}`);
                $.ajax({
                    url: '/admin/roles/store',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.status) {
                            showToast('success', response.message)
                            //move page after 1000
                            setTimeout(() => {
                                window.location.href = '/admin/roles/edit/' + response.role_id;
                            }, 1000);
                        } else {
                            showToast('error', response.message)
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
            }
            $('#createFormRole').on('submit', function(e) {
                e.preventDefault();
                handleCreateForm('createFormRole');
            });


        })
    </script>
@endsection
