<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Bank Imam</title>
    <meta name="description" content="Bank imam">
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href={{asset('/template/plugins/select2/css/select2.min.css')}}>
    <link rel="stylesheet" href={{asset('/template/plugins/fontawesome-free/css/all.min.css')}}>
    <link rel="stylesheet" href="{{ asset('template-fe/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{asset('template/plugins/toastr/toastr.min.css')}}">

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @livewireStyles
</head>

<body>

    <!-- loader -->
    <div id="loader">
        <img src="{{ asset('template-fe/assets/img/bank.svg') }}" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->


    <!-- App Header -->
    <div class="appHeader" style="max-width: 400px; margin:0 auto;">
        <div class="left">
            <a href="{{ route(View::yieldContent('route', 'home')) }}" class="headerButton">
                <i class="fas fa-arrow-left"></i>
            </a>

        </div>
        <div class="pageTitle">Setor Sampah</div>

    </div>
    <!-- * App Header -->



    <div class="container mb-5 pb-5" id="appCapsule" style="max-width: 400px; margin:0 auto;">
          <div class="card card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Buat Transaksi</h3>
            </div>
            <form id="createFormTransaction">
                @csrf
                <div class="card-body">
                    <!-- Dynamic Input Sampah -->
                    <div id="dynamic-input-sampah">
                        <div class="row align-items-center input-group-sampah mb-3">
                            <div class="col-12 col-md-12 mb-2 mb-md-0">
                                <div class="form-group">
                                    <label for="sampah_id" class="required">Sampah</label>
                                    <select name="sampah_id[]" class="form-control select2">
                                        <option value="" disabled selected>Pilih sampah</option>
                                        @foreach($sampahs as $sampah)
                                        <option value="{{$sampah->id}}" data-harga="{{$sampah->harga}}" data-points="{{$sampah->points}}">
                                            {{$sampah->nama}} - Rp {{number_format($sampah->harga, 0, ',', '.')}}/kg
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-12">
                                <div class="form-group">
                                    <label for="berat" class="required">Berat (KG)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control berat-input" name="berat[]" required>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- Tombol Tambah Baris -->
                    <div class="mb-3 text-left">
                        <button type="button" class="btn btn-primary add-row-sampah">
                            <i class="fas fa-plus"></i> Tambah Sampah
                        </button>
                    </div>
            
                    <!-- Total Amount dan Total Points -->
                    <div class="row">
                        <div class="col-6 col-md-6 mb-3 mb-md-0">
                            <div class="form-group">
                                <label for="total_amount">Total Pendapatan</label>
                                <input type="text" class="form-control shadow-sm" name="total_amount" id="total_amount" readonly>
                                <input type="hidden" name="total_amount_hidden" id="total_amount_hidden">
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="form-group">
                                <label for="total_points">Total Points</label>
                                <input type="text" class="form-control shadow-sm" name="total_points" id="total_points" readonly>
                                <input type="hidden" name="total_points_hidden" id="total_points_hidden">
                            </div>
                        </div>
                    </div>
            
                    <!-- Tombol Submit -->
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary px-4">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
          </div>
    
        
        
    </div>


    <!-- ========= JS Files =========  -->
    <script src={{ asset('/template/plugins/jquery/jquery.min.js') }}></script>
    <script src="{{ asset('template-fe/assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <!-- Base Js File -->
    <script src="{{ asset('template-fe/assets/js/base.js') }}"></script>
    <script src="{{asset('template/plugins/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('/template/plugins/select2/js/select2.full.min.js')}}"></script>

    <script>
        // Add to Home with 2 seconds delay.
        AddtoHome("2000", "once");
    </script>

     <script>
        $(function () {
            $('.select2').select2();
        })
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
             url: 'setor-sampah/store',
             type: 'POST',
             data: form.serialize(),
             success: function(response) {
                 if (response.success) {
                     showToast('success', response.message);
                     setTimeout(() => {
                         window.location.href = '/transaksi';
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
                 <div class="col-12 col-md-12">
                         <div class="form-group">
                             <label for="sampah_id" class="required">Sampah</label>
                             <select name="sampah_id[]" class="form-control select2">
                                 <option value="" disabled selected>Pilih sampah</option>
                                 @foreach($sampahs as $sampah)
                                 <option value="{{$sampah->id}}" data-harga="{{$sampah->harga}}" data-points="{{$sampah->points}}">
                                     {{$sampah->nama}} - Rp {{number_format($sampah->harga, 0, ',', '.')}}/kg
                                 </option>
                                 @endforeach
                             </select>
                         </div>
                     </div>
                     <div class="col-12 col-md-12">
                         <div class="form-group">
                             <label for="berat" class="required">Berat</label>
                             <div class="input-group">
                                 <input type="text" class="form-control berat-input" name="berat[]" required>
                             </div>
                         </div>
                     </div>
                 <div class="mt-2 mb-2" >
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
         const selectedOption = $(this).find('.select2 option:selected');
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
 $(document).on('change', '.select2', hitungTotalAmount);
 $(document).on('input', '.berat-input', hitungTotalAmount);
 
 
 });
 
     </script>


</body>

</html>
