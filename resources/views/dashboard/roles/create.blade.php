@extends('layouts.layout')
@section('content')
    {{-- @include('layouts.breadcrumb') --}}
    <section class="content">

        <div class="card card-primary">

            <form id="createFormRole">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control " name="name" id="name" required>
                            </div>
                        </div>
                    </div>

                    <div class="permission-section">
                        <label>Set Permission</label>
                        <div class="row">
                            <div class="col-md-12"></div>
                            @foreach ($permissionGroups as $group => $permissions)
                                <div class="col-md-3">
                                    <label>{{ $group }}</label>
                                    @foreach ($permissions as $key => $permission)
                                        <div class="form-group">
                                            <div class="form-check">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox"
                                                        id="customCheckbox{{$group}}{{ $key }}" name="permission[]"
                                                        value="{{ $permission->name }}">
                                                    <label for="customCheckbox{{$group}}{{ $key }}"
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
                        <button type="button"onclick="window.location.href='{{ route('roles.index') }}'"
                            class="btn btn-warning"><span>Back</span></button>
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
                    url: '/roles/store',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.status) {
                            showToast('success', response.message)
                            //move page after 1000
                            setTimeout(() => {
                                window.location.href = '/roles/edit/' + response.role_id;
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
