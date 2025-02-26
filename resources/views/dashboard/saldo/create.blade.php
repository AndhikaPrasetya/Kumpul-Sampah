@extends('layouts.layout')
@section('content')
{{-- @include('layouts.breadcrumb') --}}
<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Buat data Sampah</h3>
    </div>
      <form id="createFormSaldo" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="row">
                <div class="col-12">
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
               
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="saldo_masuk" class="required">Saldo masuk</label>
                        <input type="text" class="form-control shadow-sm" name="saldo_masuk" id="saldo_masuk" placeholder="5000" required>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="saldo_keluar">Saldo keluar</label>
                        <input type="text" class="form-control shadow-sm" name="saldo_keluar" id="saldo_keluar" placeholder="5000">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="saldo_akhir">Saldo akhir</label>
                        <input type="text" class="form-control shadow-sm" name="saldo_akhir" id="saldo_akhir" readonly>
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
                    url: '/saldo/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.href = '/saldo';
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

            function formatAngka(value) {
        return value.replace(/[^0-9]/g, ''); // Hanya angka
    }

    function hitungSaldoAkhir() {
        let saldoMasuk = parseFloat(formatAngka($('#saldo_masuk').val())) || 0;
        let saldoKeluar = parseFloat(formatAngka($('#saldo_keluar').val())) || 0;
        let saldoAkhir = saldoMasuk - saldoKeluar;

        if (saldoMasuk > 0 && saldoKeluar === 0) {
            saldoAkhir = saldoMasuk;
        }

        $('#saldo_akhir').val(saldoAkhir);
    }

    $('#saldo_masuk, #saldo_keluar').on('input', function () {
        $(this).val(formatAngka($(this).val()));
        hitungSaldoAkhir();
    });

    // Jalankan saat halaman dimuat
    hitungSaldoAkhir();


       });

     
    </script>
@endsection

