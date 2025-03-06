@extends('layouts.layout')
@section('content')
{{-- @include('layouts.breadcrumb') --}}
<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Buat Article</h3>
    </div>
      <form id="createFormArticle">
          @csrf
          <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="title" class="required">Judul</label>
                        <input type="text" class="form-control shadow-sm" name="title" id="title" placeholder="Malin kundang" required>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="thumbnail" class="required">Thumbnail</label>
                        <input type="file" class="dropify" name="thumbnail" />
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="image" class="required">image</label>
                        <input type="file" class="dropify" name="image" />
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="content" class="required">content</label>
                      <textarea name="content" class="form-control" id="content" cols="30" rows="10"></textarea>
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
                        onclick="window.location.href='{{ route('article.index') }}'"
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
        $('#createFormArticle').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '/article/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.href = '/article';
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
                    }
                });
            });


       })

     
    </script>
@endsection

