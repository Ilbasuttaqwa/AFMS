@extends('layouts.admin.template')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold py-3">Form Edit Lokasi</h5>
                    <div class="row">
                        <form action="{{ route('cabang.update', $cabang->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="nama_cabang" class="form-label">Nama Lokasi</label>
                                <input type="text" class="form-control" name="nama_cabang" value="{{ $cabang->nama_cabang }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3">{{ $cabang->alamat }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="kode_cabang" class="form-label">Kode Lokasi</label>
                                <input type="text" class="form-control" name="kode_cabang" value="{{ $cabang->kode_cabang }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('cabang.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection