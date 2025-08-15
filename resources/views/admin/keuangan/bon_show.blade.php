@extends('layouts.admin.template')

@section('title', 'Detail Bon')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Detail Bon</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Pegawai:</label>
                                <p class="text-sm">{{ $bon->pegawai->nama }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Jabatan:</label>
                                <p class="text-sm">{{ $bon->pegawai->jabatan->nama_jabatan }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Jumlah:</label>
                                <p class="text-sm">Rp {{ number_format($bon->jumlah, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Tanggal:</label>
                                <p class="text-sm">{{ $bon->tanggal->format('d F Y') }}</p>
                            </div>



                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Dibuat oleh:</label>
                                <p class="text-sm">{{ $bon->creator->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Keterangan:</label>
                                <p class="text-sm">{{ $bon->keterangan }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Dibuat pada:</label>
                                <p class="text-sm">{{ $bon->created_at->format('d F Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('keuangan.bon.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <div>
                                    <a href="{{ route('keuangan.bon.edit', $bon->id) }}" class="btn btn-warning me-2">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('keuangan.bon.destroy', $bon->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus bon ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
