@extends('layouts.layout')
@section('content')
    @include('layouts.breadcrumb')
    <section class="content">

        <div class="card card-primary">
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Role : {{ $role->name }}</label>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <form id="addPermission" data-id="{{ $role->id }}">
                    @csrf
                    @method('PUT')
                    <div class="d-flex">

                        @foreach ($permissions as $key => $permission)
                            <div class="form-group mr-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch{{ $key }}" 
                                        name="permission[]" value="{{ $permission->name }}"
                                        {{in_array($permission->id,$rolePermissions)? 'checked' : ''}}>
                                    <label class="custom-control-label" for="customSwitch{{ $key }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                                
                            </div>
                        @endforeach

                    </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button"onclick="window.location.href='{{ route('roles.index') }}'"
                    class="btn btn-warning"><span>Back</span></button>
            </div>
        </div>
    </form>
    </section>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(() => {
            toastr.options = {
                "positionClass": "toast-top-right", // Posisi toast
                "timeOut": "1000",
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
            

            const handleAddForm = (formId) => {
                const form = $(`#${formId}`);
                const id = form.data('id');
                $.ajax({
                    url: `/roles/givePermission/${id}`,
                    type: 'PUT',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.message);
                        } else {
                            showToast('error', response.message);
                            console.log(response);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON.error);
                        if (xhr.status === 422) {
                            showToast('error', xhr.responseJSON.errors);
                        } else {
                            showToast('error', xhr.responseJSON.message);
                        }
                    }
                });
            }
            $('#addPermission').on('submit', function(e) {
                e.preventDefault();
                handleAddForm('addPermission');
            });


        })
    </script>
@endsection
