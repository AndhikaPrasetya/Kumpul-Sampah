@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kelurahan</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="p-3">
                            <a href="{{ route('kelurahan.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                                Tambah
                                Kelurahan</a>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-hover ">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kelurahan</th>
                                        <th>Kecamatan</th>
                                        <th>Kota</th>
                                        <th>Provinsi</th>
                                        <th class="w-25 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $kelurahan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $kelurahan->user->name }}</td>
                                            <td>{{ $kelurahan->kecamatan }}</td>
                                            <td>{{ $kelurahan->kota }}</td>
                                            <td>{{ $kelurahan->provinsi }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('kelurahan.edit', $kelurahan->id) }}"
                                                    class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                                <button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="{{$kelurahan->user->id }}" data-section="kelurahan"><i class="fas fa-trash-alt"></i>
                                     </button>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
