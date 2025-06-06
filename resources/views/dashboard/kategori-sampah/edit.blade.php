@extends('layouts.layout')

@section('content')
  <div class="container m-5">
      <div class="card card-primary">
        <div class="card-header bg-primary">
            <h3 class="card-title text-white">Edit Kategori</h3>
        </div>
          <form id="updateFormKategori" data-id="{{ $data->id }}">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="nama" class="required">Nama Kategori</label>
                                <input type="text" class="form-control shadow-sm" name="nama" id="nama" value="{{$data->nama}}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="deskripsi" class="required">Deskripsi</label>
                                <input type="text" class="form-control shadow-sm" name="deskripsi" id="deskripsi"  value="{{$data->deskripsi}}">
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
                    <button type="button" 
                            onclick="window.location.href='{{ route('kategori-sampah.index') }}'"
                            class="btn btn-warning px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </button>
                </div>
            </div>
          </form>
      </div>
  </div>
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
                url: `/admin/kategori-sampah/update/${id}`,
                type: 'PUT',
                data: form.serialize(),
                success: (response) => {
                    if (response.success) {
                      showToast('success', response.message);
                      setTimeout(() => {
                        window.location.href = '/admin/kategori-sampah';
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
                // Re-enable the submit button
                $(this).find('button[type="submit"]').prop('disabled', false);
            }
            });
        };

        $('#updateFormKategori').on('submit', function (e) {
            e.preventDefault();
            $(this).find('button[type="submit"]').prop('disabled', true);
            handleFormSubmit('updateFormKategori');
        });
    });
</script>
@endsection
