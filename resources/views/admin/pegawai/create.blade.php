@extends('layouts.admin.template')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Management Karyawan /</span> <span
                class="text-muted fw-light">Pegawai /</span> Create</h4>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Tambah Karyawan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="nama_pegawai">Nama</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control @error('nama_pegawai') is-invalid @enderror" 
                                    id="nama_pegawai" placeholder="Masukan Nama Lengkap" name="nama_pegawai" 
                                    value="{{ old('nama_pegawai') }}" required />
                            </div>
                            @error('nama_pegawai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="jenis_kelamin">Jenis Kelamin</label>
                        <div class="col-sm-10">
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="" selected disabled>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="id_cabang">Lokasi</label>
                        <div class="col-sm-10">
                            <select name="id_cabang" id="id_cabang" class="form-control @error('id_cabang') is-invalid @enderror" required>
                                <option value="" selected disabled>-- Pilih Lokasi --</option>
                                @foreach ($cabang as $data)
                                <option value="{{ $data->id }}" {{ old('id_cabang') == $data->id ? 'selected' : '' }}>{{ $data->nama_cabang }}</option>
                                @endforeach
                            </select>
                            @error('id_cabang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="id_jabatan">Golongan</label>
                        <div class="col-sm-10">
                            <select name="id_jabatan" id="id_jabatan" class="form-control @error('id_jabatan') is-invalid @enderror" required>
                                <option value="" selected disabled>-- Pilih Golongan --</option>
                                @foreach ($jabatan as $data)
                                    <option value="{{ $data->id }}" {{ old('id_jabatan') == $data->id ? 'selected' : '' }}>{{ $data->nama_jabatan }}</option>
                                @endforeach
                            </select>
                            @error('id_jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="email">Email</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    placeholder="Masukan Email" name="email" value="{{ old('email') }}" required />
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="alamat">Alamat</label>
                        <div class="col-sm-10">
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" 
                                placeholder="Masukan Alamat Lengkap" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="device_user_id">ID Device Biometric</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control @error('device_user_id') is-invalid @enderror" id="device_user_id"
                                    placeholder="Masukan ID User di Device Fingerprint (opsional)" name="device_user_id" 
                                    value="{{ old('device_user_id') }}" />
                            </div>
                            <small class="form-text text-muted">ID unik karyawan di mesin biometric untuk integrasi absensi otomatis</small>
                            @error('device_user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
