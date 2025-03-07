@extends('layouts.layout')
@section('content')
{{-- @include('layouts.breadcrumb') --}}
<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Buat Saldo</h3>
    </div>
      <form id="createFormSaldo" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="user_id" class="required">Nasabah</label>
                        <select class="form-control" name="user_id" id="user_id">
                            <option value="" disabled selected>Pilih Kategori</option>
                         @foreach ($nasabahs as $nasabah)
                         <option value="{{$nasabah->id}}">{{$nasabah->name}}</option>
                         @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="balance" class="required">Balance</label>
                        <input type="text" class="form-control shadow-sm" name="balance" id="balance" placeholder="5000" required>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="points" class="required">Points</label>
                        <input type="text" class="form-control shadow-sm" name="points" id="points" placeholder="5000" required>
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
                        onclick="window.location.href='{{ route('saldo.index') }}'"
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
        $('#createFormSaldo').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '/admin/saldo/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.href = '/admin/saldo';
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

            
         // Format angka dengan titik sebagai pemisah ribuan
    function formatAngka(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Hapus format angka sebelum dikirim ke server
    function hapusFormatAngka(value) {
        return value.replace(/\./g, '');
    }

    // Event untuk input balance (format otomatis)
    $('#balance').on('input', function () {
        let value = $(this).val();
        let unformattedValue = hapusFormatAngka(value);
        $(this).val(formatAngka(unformattedValue));
    });

       });

     
    </script>
@endsection

