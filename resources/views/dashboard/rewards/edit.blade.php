@extends('layouts.layout')
@section('content')

<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Edit Rewards</h3>
    </div>
      <form id="updateFormRewards" data-id="{{ $rewards->id }}">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="name" class="required">Nama barang</label>
                        <input type="text" class="form-control shadow-sm" name="name" id="name" value="{{$rewards->name}}">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="points" class="required">Jumlah poin</label>
                        <input type="text" class="form-control shadow-sm" name="points" id="points" value="{{ number_format($rewards->points, 0, ',', '.');}}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="image" class="required">Gambar barang</label>

                        <input type="file" class="dropify shadow-sm" name="image" id="image" data-default-file="{{ secure_asset($rewards->image) }}">
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
                        onclick="window.location.href='{{ route('rewards.index') }}'"
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
        $('#updateFormRewards').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                const id = $(this).data('id');

                $.ajax({
                    url: '/admin/rewards/update/'+ id,
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
                            window.location.href='/admin/rewards';
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

