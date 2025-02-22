@extends('layouts.layout')

@section('content')
  <section class="content m-5">
      <div class="card card-primary">
        <div class="card-header bg-primary">
            <h3 class="card-title text-white">Create New Role</h3>
        </div>
          <form id="updateFormPermission" data-id="{{ $data->id }}">
              @csrf
              @method('PUT')
              <div class="card-body">
                  <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}" required>
                  </div>
              </div>
              <div class="card-footer bg-light">
                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary px-4 mr-1">
                        <i class="fas fa-save mr-1"></i> Submit
                    </button>
                    <button type="button" 
                            onclick="window.location.href='{{ route('permission.index') }}'"
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

        const handleFormSubmit = (formId) => {
          //get id form
            const form = $(`#${formId}`);
          //get id user
            const id = form.data('id');

            $.ajax({
                url: `/permission/update/${id}`,
                type: 'PUT',
                data: form.serialize(),
                success: (response) => {
                    if (response.status) {
                      showToast('success', response.message);
                      setTimeout(() => {
                        window.location.href = '/permission';
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

        $('#updateFormPermission').on('submit', function (e) {
            e.preventDefault();
            handleFormSubmit('updateFormPermission');
        });
    });
</script>
@endsection
