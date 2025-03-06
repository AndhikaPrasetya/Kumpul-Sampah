
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

    $('#table_nasabah').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/nasabah",
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
                data: 'no_phone',
                name: 'no_phone',
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

    //-------------------------> start table history transaction <-----------------------\\

    $(".select2").select2({
        dropdownParent: $("#filterModalHistory"),
        placeholder: "Pilih Nasabah",
        allowClear: true,
      });
    
    // Inisialisasi Daterangepicker
  $("#daterange").daterangepicker({
    autoUpdateInput: false,
    locale: {
      format: "DD-MM-YYYY",
      separator: " s/d ",
      applyLabel: "Pilih",
      cancelLabel: "Batal",
      fromLabel: "Dari",
      toLabel: "Sampai",
    },
  });

  // Handle daterangepicker
  $("#daterange").on("apply.daterangepicker", function (ev, picker) {
    $(this).val(
      picker.startDate.format("DD-MM-YYYY") +
        " s/d " +
        picker.endDate.format("DD-MM-YYYY")
    );
  });

  $("#daterange").on("cancel.daterangepicker", function (ev, picker) {
    $(this).val("");
  });

    // Function to get selected columns
    function getSelectedColumns() {
        return $('.checkbox-list input:checked').map(function() {
            return parseInt($(this).data('column-index'));
        }).get();
    }

// Handle Excel export
$('#exportExcel').click(function() {
    table_history.button('.buttons-excel').trigger();
});

// Handle PDF export
$('#exportPDF').click(function() {
    table_history.button('.buttons-pdf').trigger();
});

  let table_history =  $('#table_history_transaction').DataTable({
    processing: true,
    serverSide: true,
    searching: true,
    stateSave: true,
    ajax: {
        url: "/history-transaction",
        type: "GET",
        data: function(d){
            d.nama_nasabah = $("#nama_nasabah_filter").val();
            const daterange = $("#daterange").val();
            if (daterange) {
                const dates = daterange.split(" s/d ");
                d.start_date = moment(dates[0], "DD-MM-YYYY").format("YYYY-MM-DD 00:00:00");
                d.end_date = moment(dates[1], "DD-MM-YYYY").format("YYYY-MM-DD 23:59:59");
            } 
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'transaction_id', name: 'transaction_id', orderable: false, searchable: true },
        { data: 'sampah_id', name: 'sampah_id', orderable: false, searchable: true },
        { data: 'berat', name: 'berat', orderable: false, searchable: true },
        { data: 'subtotal', name: 'subtotal', orderable: false, searchable: true },
        { data: 'points', name: 'points', orderable: false, searchable: true },
        { data: 'created_at', name: 'created_at', orderable: false, searchable: true },
    ],
    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    buttons: [
        {
            extend: 'excelHtml5',
            text: 'Excel',
            title: 'Laporan Riwayat Transaksi',
            exportOptions: {
                columns: function(idx, data, node) {
                    return getSelectedColumns().includes(idx);
                }
            }
        },
        {
            extend: 'pdfHtml5',
            text: 'PDF',
            exportOptions: {
                columns: function(idx, data, node) {
                    return getSelectedColumns().includes(idx);
                }
            },
            customize: function(doc) {
                // Set page size to A4
                doc.pageSize = 'A4';
                doc.pageOrientation = 'portrait';

                // Center the table
                doc.content[1].table.widths = 
                    Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                // Adjust table styling
                doc.styles.tableHeader.alignment = 'center';
                doc.styles.tableBodyEven.alignment = 'center';
                doc.styles.tableBodyOdd.alignment = 'center';

                // Add margin to center the table vertically
                doc.content[1].margin = [0, 10, 0, 0];

                // Adjust font size if needed
                doc.defaultStyle.fontSize = 10;

                // Add title
                doc.content.unshift({
                    text: 'Laporan Riwayat Transaksi',
                    style: 'title',
                    alignment: 'center',
                    margin: [0, 0, 0, 0]
                });

                // Define title style
                doc.styles.title = {
                    fontSize: 18,
                    bold: true,
                    alignment: 'center'
                };
            }
        }
    ]
});



    // Handle tombol apply filter
    $("#apply_filter").click(function () {
        const selectedNasabah = $("#nama_nasabah_filter").val(); 
        const selectedRange = $("#daterange").val(); 
        
        if (selectedNasabah||selectedRange) {
            $("#selectedNasabahFilter").text(selectedNasabah); 
            $("#selectedRangeFilter").text(selectedRange); 
            $("#filterContainerHistory").show(); // Tampilkan filter
        } else {
            $("#filterContainerHistory").hide();
        }
    
        table_history.draw(); 
        $("#filterModalHistory").modal("hide");
    });
    
    
      // Handle tombol reset
      $("#reset_filter").click(function () {
        // Reset select2 filter
        $("#nama_nasabah_filter").val([]).trigger("change"); 
    
        // Reset Daterangepicker dengan event khusus
        $("#daterange").val("").trigger("change"); 
        $("#daterange").data("daterangepicker").setStartDate(moment()); 
        $("#daterange").data("daterangepicker").setEndDate(moment()); 
        $("#filterContainerHistory").hide(); 
    
        // Reset Datatable_historys dan pastikan pemanggilan ulang ke server
        table_history.state.clear(); 
        table_history.ajax.reload();
    });

        //-------------------------> end table history transaction <-----------------------\\

    //-------------------------> start table transaction <-----------------------\\
    $(".select-transaction").select2({
        dropdownParent: $("#filterModalTransaction"),
        placeholder: "Pilih Nasabah",
        allowClear: true,
      }); 
      
// Handle Excel export
$('#exportExcel').click(function() {
    table_transaction.button('.buttons-excel').trigger();
});

// Handle PDF export
$('#exportPDF').click(function() {
    table_transaction.button('.buttons-pdf').trigger();
});

    let table_transaction = $('#table_transaction').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/transaction",
            type: "GET",
            data:function(d){
                d.nama_nasabah_transaksi = $("#nama_nasabah_transaksi_filter").val();
                const daterange = $("#daterange").val();
                if (daterange) {
                    const dates = daterange.split(" s/d ");
                    d.start_date = moment(dates[0], "DD-MM-YYYY").format("YYYY-MM-DD 00:00:00");
                    d.end_date = moment(dates[1], "DD-MM-YYYY").format("YYYY-MM-DD 23:59:59");
                }
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_id', name: 'user_id', orderable: false, searchable: true },
            { data: 'status', name: 'status', orderable: false, searchable: true },
            { data: 'total_amount', name: 'total_amount', orderable: false, searchable: true },
            { data: 'total_points', name: 'total_points', orderable: false, searchable: true },
            { data: 'tanggal', name: 'tanggal', orderable: false, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Excel',
                title: 'Laporan Transaksi',
                exportOptions: {
                    columns: function(idx, data, node) {
                        return getSelectedColumns().includes(idx);
                    }
                },
                //customize excel
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                exportOptions: {
                    columns: function(idx, data, node) {
                        return getSelectedColumns().includes(idx);
                    }
                },
                customize: function(doc) {
                    // Set page size to A4
                    doc.pageSize = 'A4';
                    doc.pageOrientation = 'portrait';
    
                    // Center the table
                    doc.content[1].table.widths = 
                        Array(doc.content[1].table.body[0].length + 1).join('*').split('');
    
                    // Adjust table styling
                    doc.styles.tableHeader.alignment = 'center';
                    doc.styles.tableBodyEven.alignment = 'center';
                    doc.styles.tableBodyOdd.alignment = 'center';
    
                    // Add margin to center the table vertically
                    doc.content[1].margin = [0, 10, 0, 0];
    
                    // Adjust font size if needed
                    doc.defaultStyle.fontSize = 10;
    
                    // Add title
                    doc.content.unshift({
                        text: 'Laporan Transaksi',
                        style: 'title',
                        alignment: 'center',
                        margin: [0, 0, 0, 0]
                    });
    
                    // Define title style
                    doc.styles.title = {
                        fontSize: 18,
                        bold: true,
                        alignment: 'center'
                    };
                }
            }
        ]
    });

    
    // Handle tombol apply filter
    $("#apply_filter_transaction").click(function () {
        const selectedNasabahTransaction = $("#nama_nasabah_transaksi_filter").val(); 
        const selectedRange = $("#daterange").val(); 
        
        if (selectedNasabahTransaction||selectedRange) {
            $("#selectedNasabahTransactionFilter").text(selectedNasabahTransaction); 
            $("#selectedRangeTransactionFilter").text(selectedRange); 
            $("#filterContainerTransaction").show(); // Tampilkan filter
        } else {
            $("#filterContainerTransaction").hide();
        }
    
        table_transaction.draw(); 
        $("#filterModalTransaction").modal("hide");
    });
    
    
        // Handle tombol reset
        $("#reset_filter_transaction").click(function () {
            // Reset select2 filter
            $("#nama_nasabah_transaksi_filter").val([]).trigger("change"); 
        
            // Reset Daterangepicker dengan event khusus
            $("#daterange").val("").trigger("change"); 
            $("#daterange").data("daterangepicker").setStartDate(moment()); 
            $("#daterange").data("daterangepicker").setEndDate(moment()); 
            $("#filterContainerTransaction").hide(); 
        
            // Reset Datatable_transactions dan pastikan pemanggilan ulang ke server
            table_transaction.state.clear(); 
            table_transaction.ajax.reload();
        });

    

//-------------------------> end table transaction <-----------------------\\

$('#table_saldo').DataTable({
    processing: true,
    serverSide: true,
    searching: true,
    stateSave: true,
    ajax: {
        url: "/saldo",
        type: "GET"
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        {
            data: 'user_id',
            name: 'user_id',
            orderable: false,
        },
        {
            data: 'balance',
            name: 'balance',
            orderable: false,
        },
        {
            data: 'points',
            name: 'points',
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

$('#table_withdraw').DataTable({
    processing: true,
    serverSide: true,
    searching: true,
    stateSave: true,
    ajax: {
        url: "/withdraw",
        type: "GET"
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        {
            data: 'user_id',
            name: 'user_id',
            orderable: false,
        },
        {
            data: 'amount',
            name: 'amount',
            orderable: false,
        },
        {
            data: 'status',
            name: 'status',
            orderable: false,
        },
        {
            data: 'tanggal',
            name: 'tanggal',
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

$('#table_rewards').DataTable({
    processing: true,
    serverSide: true,
    searching: true,
    stateSave: true,
    ajax: {
        url: "/rewards",
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
            data: 'points',
            name: 'points',
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
$('#table_penukaran_points').DataTable({
    processing: true,
    serverSide: true,
    searching: true,
    stateSave: true,
    ajax: {
        url: "/penukaran-points",
        type: "GET"
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        {
            data: 'user_id',
            name: 'user_id',
            orderable: false,
        },
        {
            data: 'reward_id',
            name: 'reward_id',
            orderable: false,
        },
        {
            data: 'status',
            name: 'status',
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

$('#table_article').DataTable({
    processing: true,
    serverSide: true,
    searching: true,
    stateSave: true,
    ajax: {
        url: "/article",
        type: "GET"
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        {
            data: 'title',
            name: 'title',
            orderable: false,
        },
        {
            data: 'user_id',
            name: 'user_id',
            orderable: false,
        },
        {
            data: 'status',
            name: 'status',
            orderable: false,
        },
        {
            data: 'tanggal',
            name: 'tanggal',
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


