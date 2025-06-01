@extends('layouts.layout')
@section('content')

<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Edit penarikan</h3>
    </div>
      <form id="updateFormWithdraw" data-id="{{ $data->id }}">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="user_id" class="required">Nasabah</label>
                        <select class="form-control" name="user_id" id="user_id">
                            <option value="" disabled selected>Pilih Kategori</option>
                         @foreach ($nasabahs as $nasabah)
                         <option value="{{$nasabah->id}}" {{$data->user_id == $nasabah->id ? 'selected' : ''}}>{{$nasabah->name}}</option>
                         @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="amount" class="required">amount</label>
                        <input type="text" class="form-control shadow-sm" name="amount" id="amount"
                            value="{{number_format($data->amount, 0, ',', '.')}}">

                    </div>
                </div>

                @can('update withdraw')
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="status" class="required">status</label>
                                <select name="status" class="form-control">
                                    <option value="pending" {{$data->status === 'pending' ? 'selected' : ''}}>menunggu</option>
                                    <option value="rejected" {{$data->status === 'rejected' ? 'selected' : ''}}>tolak</option>
                                    <option value="approved" {{$data->status === 'approved' ? 'selected' : ''}}>approved</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-12 col-md-6">
                              <div class="form-group">
                                  <label for="status">Metode Penarikan</label>
                                 <input type="text" class="form-control" value="{{$data->metode_penarikan}}">
                              </div>
                          </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="image" class="required">Bukti pencairan</label>
                                <input type="file" class="dropify" name="image" />
                            </div>
                        </div>
                            
                        @endcan
                
            </div>
          
          </div>
  
          <div class="card-footer bg-light">
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary px-4 mr-1">
                    <i class="fas fa-save mr-1"></i> Submit
                </button>
                <button type="button" 
                        onclick="window.location.href='{{ route('withdraw.index') }}'"
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
        $('#updateFormWithdraw').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                const id = $(this).data('id');

                $.ajax({
                    url: '/admin/withdraw/update/'+ id,
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
                            window.location.href='/admin/withdraw';
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

