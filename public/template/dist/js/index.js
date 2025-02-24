//delete button data
$(document).on('click', '.delete-button', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    let section = $(this).data('section');

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

    let url = `/${section}/delete/${id}`;
    Swal.fire({
        title: "Anda yakin ingin menghapus?",
        text: "Data akan hilang!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Delete"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showToast('success', response.message);
                    location.reload();
                },
                error: function() {
                    Swal.fire("Error!", "There was a problem deleting the item.",
                        "error");
                }
            });
        }
    });
});


$(document).ready(function() {
    //table data users 
    $('#table_users').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/users",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'name',
                name: 'name',
                orderable: false,
            },
            {
                data: 'email',
                name: 'email',
                orderable: false,
            },
            {
                data: 'role',
                name: 'role',
                orderable: false,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });
    
    //table data role
    $('#table_role').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/roles",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'name',
                name: 'Name',
                orderable: false,
            },
            {
                data: 'guard_name',
                name: 'guard_name',
                orderable: false,
            },
            {
                data: 'permission',
                name: 'Permission',
                orderable: false,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });
    //table data permission
    $('#table_permission').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/permission",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'name',
                name: 'Name',
                orderable: false,
            },
            {
                data: 'guard_name',
                name: 'Guard Name',
                orderable: false,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });
    $('#table_kategori_sampah').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/kategori-sampah",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'nama',
                name: 'nama',
            orderable: false,
            },
            {
                data: 'deskripsi',
                name: 'deskripsi',
                orderable: false,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });
    $('#table_sampah').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/list-sampah",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'nama',
                name: 'nama',
                orderable: false,
            },
            {
                data: 'category_id',
                name: 'category_id',
                orderable: false,
            },
            {
                data: 'harga',
                name: 'harga',
                orderable: false,
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });

    let table_history = $('#table_history_transaction').DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/history-transaction",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'transaction_id', name: 'transaction_id', orderable: true },
            { data: 'sampah_id', name: 'sampah_id', orderable: true },
            { data: 'berat', name: 'berat', orderable: true },
            { data: 'subtotal', name: 'subtotal', orderable: true },
            { data: 'created_at', name: 'created_at', orderable: true },
        ],
        order: [[2, 'desc']], // Default sorting berdasarkan tanggal terbaru

        // Pastikan searching tetap ada dengan format dom yang benar
        dom: "<'row'<'col-md-6'l><'col-md-6'f>>" + 
             "<'row'<'col-md-12'B>>" + 
             "<'row'<'col-md-12'tr>>" + 
             "<'row'<'col-md-5'i><'col-md-7'p>>", 
    
             buttons: [
                {
                    extend: 'colvis',
                    text: 'Pilih Kolom',
                    className: 'btn btn-sm btn-secondary'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Export Excel',
                    className: 'btn btn-sm btn-success',
                    exportOptions: {
                        columns: function (idx, data, node) {
                            return $(node).css('display') !== 'none' ? true : false;
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> Export PDF',
                    className: 'btn btn-sm btn-danger',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: function (idx, data, node) {
                            return $(node).css('display') !== 'none' ? true : false;
                        }
                    }
                }
            ]
    });

    // Menampilkan tombol di tempat yang diinginkan
    table_history.buttons().container().appendTo('#export-buttons');

    let table = $('#table_transaction').DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/transaction",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_id', name: 'user_id', orderable: true },
            { data: 'tanggal', name: 'tanggal', orderable: true },
            { data: 'total_amount', name: 'total_amount', orderable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[2, 'desc']], // Default sorting berdasarkan tanggal terbaru

        // Pastikan searching tetap ada dengan format dom yang benar
        dom: "<'row'<'col-md-6'l><'col-md-6'f>>" + 
             "<'row'<'col-md-12'B>>" + 
             "<'row'<'col-md-12'tr>>" + 
             "<'row'<'col-md-5'i><'col-md-7'p>>", 
    
             buttons: [
                {
                    extend: 'colvis',
                    text: 'Pilih Kolom',
                    className: 'btn btn-sm btn-secondary'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Export Excel',
                    className: 'btn btn-sm btn-success',
                    exportOptions: {
                        columns: function (idx, data, node) {
                            return $(node).css('display') !== 'none' ? true : false;
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> Export PDF',
                    className: 'btn btn-sm btn-danger',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: function (idx, data, node) {
                            return $(node).css('display') !== 'none' ? true : false;
                        }
                    }
                }
            ]
    });

    // Menampilkan tombol di tempat yang diinginkan
    table.buttons().container().appendTo('#export-buttons');


    //getDataRoleInUser

    $('.allRole').select2({
        ajax: {
            url: '/roles/getDataRole', 
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term 
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(function (role) {
                        return {
                            id: role.name,
                            text: role.name
                        };
                    })
                };
            },
            cache: true
        }
    });

    $('.dropify').dropify();
});


