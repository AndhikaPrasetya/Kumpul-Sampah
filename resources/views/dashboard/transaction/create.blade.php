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
                <div class="row align-items-center input-group-sampah">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="sampah_id" class="required">Sampah</label>
                            <select name="sampah_id[]" class="form-control sampah-select">
                                <option value="" disabled selected>Pilih sampah</option>
                                @foreach($sampahs as $sampah)
                                <option value="{{$sampah->id}}" data-harga="{{$sampah->harga}}" data-points="{{$sampah->points}}">
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
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="total_amount">Total Amount</label>
                        <input type="text" class="form-control shadow-sm" name="total_amount" id="total_amount" readonly>
                        <input type="hidden" name="total_amount_hidden" id="total_amount_hidden">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="total_points">Total Points</label>
                        <input type="text" class="form-control shadow-sm" name="total_points" id="total_points" readonly>
                        <input type="hidden" name="total_points_hidden" id="total_points_hidden">
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
            url: '/admin/transaction/store',
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(() => {
                        window.location.href = '/admin/transaction';
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
                $(this).find('button[type="submit"]').prop('disabled', false);

            }
        });
    };

    // Event submit form transaksi
    $('#createFormTransaction').on('submit', function(e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').prop('disabled', true);

        handleCreateForm('createFormTransaction');
    });

    // Tambah input baru
    $(document).on('click','.add-row-sampah', function(e) {
                e.preventDefault();

                if (e.target.classList.contains('add-row-sampah')) {
                    const newRow = document.createElement('div');
                    newRow.classList.add('row', 'align-items-center','input-group-sampah');
                    newRow.innerHTML = `
                <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="sampah_id" class="required">Sampah</label>
                            <select name="sampah_id[]" class="form-control sampah-select">
                                <option value="" disabled selected>Pilih sampah</option>
                                @foreach($sampahs as $sampah)
                                <option value="{{$sampah->id}}" data-harga="{{$sampah->harga}}" data-points="{{$sampah->points}}">
                                    {{$sampah->nama}} - Rp {{number_format($sampah->harga, 0, ',', '.')}}/kg
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
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
                <div class="col-1">
                    <button type="button" class="btn btn-danger remove-row"><i class="fas fa-trash"></i></button>
                </div>
            `;
                    const container = document.getElementById('dynamic-input-sampah');
                    container.appendChild(newRow);
                }
            });

            $(document).on('click', '.remove-row', function(e) {
                e.preventDefault();
                $(this).closest('.row').remove();
                hitungTotalAmount();
            });
    
    const hitungTotalAmount = () => {
    let totalAmount = 0;
    let totalPoints = 0;

    $(".input-group-sampah").each(function () {
        const selectedOption = $(this).find('.sampah-select option:selected');
        const harga = selectedOption.data('harga');
        const points = selectedOption.data('points');
        const berat = $(this).find('.berat-input').val();

        if (harga && berat && !isNaN(berat)) {
            const subtotal = parseFloat(harga) * parseFloat(berat);
            const subPoints = parseFloat(points) * parseFloat(berat);
            totalAmount += subtotal;
            totalPoints += subPoints;
        }
    });

    // Tampilkan total amount
    $('#total_amount').val(totalAmount.toLocaleString('id-ID'));
    $('#total_amount_hidden').val(totalAmount);
    $('#total_points').val(totalPoints.toLocaleString('id-ID'));
    $('#total_points_hidden').val(totalPoints);

};

// Event listener untuk select sampah dan input berat
$(document).on('change', '.sampah-select', hitungTotalAmount);
$(document).on('input', '.berat-input', hitungTotalAmount);


});

    </script>
@endsection

