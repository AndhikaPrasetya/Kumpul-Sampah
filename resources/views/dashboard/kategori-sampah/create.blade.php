@extends('layouts.layout')
@section('content')
{{-- @include('layouts.breadcrumb') --}}
<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Buat Kategori Sampah</h3>
    </div>
      <form id="createFormCategory">
          @csrf
          <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="nama" class="required">Nama Kategori</label>
                        <input type="text" class="form-control shadow-sm" name="nama" id="nama" placeholder="contoh: Plastik" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="deskripsi" class="required">Deskripsi</label>
                        <input type="text" class="form-control shadow-sm" name="deskripsi" id="deskripsi" placeholder="contoh: sampah jenis plastik" required>
                    </div>
                </div>
            </div>
          </div>
  
          <div class="card-footer bg-light">
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary px-4 mr-1">
                    <i class="fas fa-save mr-1"></i> Submit
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
        const handleCreateForm = (formId) =>{
          const form = $(`#${formId}`);
          $.ajax({
            url:'/admin/kategori-sampah/store',
            type:'POST',
            data:form.serialize(),
            success:function(response){
              if (response.success) {
                  showToast('success',response.message)
                 setTimeout(() => {
                       window.location.href = '/admin/kategori-sampah';
                 }, 1000);
              } else {
                  showToast('error',response.message)
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
                $(this).find('button[type="submit"]').prop('disabled', false);
            }
          });
        }
        $('#createFormCategory').on('submit', function(e){
          e.preventDefault();
          $(this).find('button[type="submit"]').prop('disabled', true);
          handleCreateForm('createFormCategory');
        });


       })

     
    </script>
@endsection

