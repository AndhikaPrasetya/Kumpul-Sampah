@extends('layouts.layout')
@section('content')

<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Edit Artikel</h3>
    </div>
      <form id="updateFormArticle" enctype="multipart/form-data" data-id="{{ $data->id }}">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="title" class="required">Judul</label>
                        <input type="text" class="form-control shadow-sm" name="title" id="title" value="{{$data->title}}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="thumbnail" class="required">Thumbnail</label>
                        <input type="file" class="dropify" name="thumbnail" data-default-file="{{ asset($data->thumbnail) }}" />
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="image" class="required">image</label>
                        <input type="file" class="dropify" name="image" data-default-file="{{ asset($data->image) }}" />
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="content" class="required">content</label>
                      <textarea name="content" class="form-control" id="content" cols="30" rows="10">{{$data->content}}</textarea>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="status" class="required">Status</label>
                        <select class="form-control shadow-sm" name="status" id="status">
                        <option value="draft" {{$data->status ==='draft'?'selected' : ''}}>Draft</option>
                        <option value="published" {{$data->status ==='published'?'selected' : ''}}>Publish</option>
                        <option value="archived" {{$data->status ==='archived'?'selected' : ''}}>Archive</option>
                        </select>
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
        $('#updateFormArticle').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                const id = $(this).data('id');

                $.ajax({
                    url: '/admin/article/update/'+ id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.href='/admin/article';
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


       });

     
    </script>
@endsection

