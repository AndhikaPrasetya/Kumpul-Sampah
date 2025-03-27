@extends('layouts.layout')
@section('content')
{{-- @include('layouts.breadcrumb') --}}
<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Buat Reward</h3>
    </div>
      <form id="createFormRewards" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="name" class="required">Nama barang</label>
                        <input type="text" class="form-control shadow-sm" name="name" id="name">
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="points" class="required">Jumlah poin</label>
                        <input type="text" class="form-control shadow-sm" name="points" id="points" placeholder="5000" required>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="stok" class="required">Jumlah Stok</label>
                        <input type="number" class="form-control shadow-sm" name="stok" id="stok" required>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="tanggal_expired" class="required">Batas Waktu</label>
                        <input type="date" class="form-control shadow-sm" name="tanggal_expired" id="tanggal_expired" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="deskripsi" class="required">Deskripsi</label>
                       <textarea name="deskripsi" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="image" class="required">Gambar barang</label>
                        <input type="file" class="dropify shadow-sm" name="image" id="image">
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
        $('#createFormRewards').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '/admin/rewards/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.href = '/admin/rewards';
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
    $('#points').on('input', function () {
        let value = $(this).val();
        let unformattedValue = hapusFormatAngka(value);
        $(this).val(formatAngka(unformattedValue));
    });

       });

     
    </script>
@endsection

