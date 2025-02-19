//delete button data
$(document).on('click', '.delete-button', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    let section = $(this).data('section');

    let url = `/${section}/delete/${id}`;
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
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
                    Swal.fire({
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
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
                name: 'Guard Name',
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
    $('#table_document').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/document",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'file_name',
                name: 'file_name'
            },
            {
                data: 'category_id',
                name: 'category_id'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'uploaded_by',
                name: 'uploaded_by'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });
    $('#table_category').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/category",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'name',
                name: 'Name'
            },
            {
                data: 'description',
                name: 'description'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });
    $('#table_perusahaan').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/perusahaan",
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
                data: 'alamat',
                name: 'alamat',
                orderable: false,
            },
            {
                data: 'email',
                name: 'email',
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
    $('#table_aktaPerusahaan').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/aktaPerusahaan",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'kode_akta',
                name: 'kode_akta',
                orderable: false,
            },
            {
                data: 'nama_akta',
                name: 'nama_akta',
                orderable: false,
            },
            {
                data: 'uid_profile_perusahaan',
                name: 'uid_profile_perusahaan',
                orderable: false,
            },
            {
                data: 'tgl_terbit',
                name: 'tgl_terbit',
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
    $('#table_lokasi').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/lokasi",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'uid_profile_perusahaan',
                name: 'uid_profile_perusahaan',
                orderable: false,
            },
            {
                data: 'nama',
                name: 'nama',
                orderable: false,
            },
            {
                data: 'category',
                name: 'category',
                orderable: false,
            },
            {
                data: 'type',
                name: 'type',
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
    $('#table_jenisDokumen').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/jenisDokumen",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'nama_jenis_dokumen',
                name: 'nama_jenis_dokumen',
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
    $('#table_sewaMenyewa').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/sewaMenyewa",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'no_dokumen',
                name: 'no_dokumen',
                orderable: false,
            },
            {
                data: 'lokasi_id',
                name: 'lokasi_id',
                orderable: false,
            },
            {
                data: 'jenis_dokumen_id',
                name: 'jenis_dokumen_id',
                orderable: false,
            },
            {
                data: 'tanggal_dokumen',
                name: 'tanggal_dokumen',
                orderable: false,
            },
            {
                data: 'sign_by',
                name: 'sign_by',
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

    $('#table_log').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: "/activityLog",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'log_name',
                name: 'log_name',
                orderable: false,
            },
            {
                data: 'description',
                name: 'description',
                orderable: false,
            },
            {
                data: 'created_at',
                name: 'created_at',
                orderable: false,
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
});


