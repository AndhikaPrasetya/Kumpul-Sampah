@extends('layouts.layout')
@section('content')
{{-- @include('layouts.breadcrumb') --}}
<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Buat Transaksi</h3>
    </div>
      <form id="createFormTransaction">
          @csrf
          <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="user_id" class="required">Nasabah</label>
                       <select name="user_id" class="form-control" id="user_id">
                        <option value="" disabled selected>Pilih nasabah</option>
                        @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                       </select>
                    </div>
                </div>
            </div>
               

            <div id="dynamic-input-sampah">
                <div class="row input-group-sampah">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="sampah_id" class="required">Sampah</label>
                            <select name="sampah_id[]" class="form-control sampah-select">
                                <option value="" disabled selected>Pilih sampah</option>
                                @foreach($sampahs as $sampah)
                                <option value="{{$sampah->id}}" data-harga="{{$sampah->harga}}">
                                    {{$sampah->nama}} - Rp {{number_format($sampah->harga, 0, ',', '.')}}/kg
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="berat" class="required">Berat</label>
                            <div class="input-group">
                                <input type="text" class="form-control berat-input" name="berat[]" placeholder="Contoh: 1" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 text-left">
                <button type="button" class="btn btn-primary add-row-sampah"><i class="fas fa-plus"></i></button>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="total_amount">Total Amount</label>
                        <input type="text" class="form-control shadow-sm" name="total_amount" id="total_amount" readonly>
                        <input type="hidden" name="total_amount_hidden" id="total_amount_hidden">
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
                        onclick="window.location.href='{{ route('transaction.index') }}'"
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
    // Toastr Configuration
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "1000",          
    };

    // Fungsi untuk menampilkan Toastr
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

    // Fungsi untuk menangani form transaksi
    const handleCreateForm = (formId) => {
        const form = $(`#${formId}`);
        $.ajax({
            url: '/transaction/store',
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(() => {
                        window.location.href = '/transaction';
                    }, 1000);
                } else {
                    showToast('error', response.message);
                }
            },
            error: (xhr) => {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, (field, messages) => {
                        messages.forEach(message => {
                            showToast('error', message);
                        });
                    });
                } else {
                    showToast('error', xhr.responseJSON.error);
                }
            }
        });
    };

    // Event submit form transaksi
    $('#createFormTransaction').on('submit', function(e) {
        e.preventDefault();
        handleCreateForm('createFormTransaction');
    });

    // Tambah input baru
    $('.add-row-sampah').on('click', function () {
            const newInput = $('.input-group-sampah').first().clone();
            newInput.find('input').val('');
            newInput.find('select').val('');
            $('#dynamic-input-sampah').append(newInput);
        });
        const hitungTotalAmount = () => {
    let totalAmount = 0;

    $(".input-group-sampah").each(function () {
        const selectedOption = $(this).find('.sampah-select option:selected');
        const harga = selectedOption.data('harga');
        const berat = $(this).find('.berat-input').val();

        if (harga && berat && !isNaN(berat)) {
            const subtotal = parseFloat(harga) * parseFloat(berat);
            totalAmount += subtotal;
        }
    });

    // Tampilkan total amount
    $('#total_amount').val(totalAmount.toLocaleString('id-ID'));
    $('#total_amount_hidden').val(totalAmount);
};

// Event listener untuk select sampah dan input berat
$(document).on('change', '.sampah-select', hitungTotalAmount);
$(document).on('input', '.berat-input', hitungTotalAmount);


});

    </script>
@endsection

