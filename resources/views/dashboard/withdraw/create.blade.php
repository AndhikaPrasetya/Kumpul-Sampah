@extends('layouts.layout')
@section('content')
    {{-- @include('layouts.breadcrumb') --}}
    <section class="content m-5">
        <div class="card card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Buat Penarikan dana</h3>
            </div>
            <form id="createFormWithdraw" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="user_id" class="required">Nasabah</label>
                                <select class="form-control" name="user_id" id="user_id">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach ($nasabahs as $nasabah)
                                        <option value="{{ $nasabah->id }}">{{ $nasabah->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="amount" class="required">amount</label>
                                <input type="text" class="form-control shadow-sm" name="amount" id="amount"
                                    placeholder="5000" required>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary px-4 mr-1">
                            <i class="fas fa-save mr-1"></i> Submit
                        </button>
                        <button type="button" onclick="window.location.href='{{ route('withdraw.index') }}'"
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
            $('#createFormWithdraw').on('submit', function(e) {
                e.preventDefault();

                // Ambil nilai amount dan hapus format titik
                const rawAmount = $('#amount').val();
                const unformattedAmount = hapusFormatAngka(rawAmount);

                // Update nilai amount di form sebelum dikirim
                $('#amount').val(unformattedAmount);


                const formData = new FormData(this);

                $.ajax({
                    url: '/withdraw/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        showToast('success', response.message);
                        // setTimeout(() => {
                        //     window.location.href = '/withdraw';
                        // }, 2000);
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
            $('#amount').on('input', function() {
                let unformattedValue = hapusFormatAngka($(this).val());
                $(this).val(formatAngka(unformattedValue));
            });

        });
    </script>
@endsection
