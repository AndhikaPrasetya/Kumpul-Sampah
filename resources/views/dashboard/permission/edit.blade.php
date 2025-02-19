@extends('layouts.layout')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{$title}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('permission.index')}}">Permission</a></li>
                    <li class="breadcrumb-item">Edit</li>
  
                </ol>
            </div>
        </div>
    </div>
  </section>
  <section class="content">
      <div class="card card-primary">
          <form id="updateFormPermission" data-id="{{ $data->id }}">
              @csrf
              @method('PUT')
              <div class="card-body">
                  <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}" required>
                  </div>
              </div>
              <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" onclick="window.location.href='{{ route('permission.index') }}'" class="btn btn-warning">
                      <span>Back</span>
                  </button>
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
