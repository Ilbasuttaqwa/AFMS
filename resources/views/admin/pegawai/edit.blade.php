@extends('layouts.admin.template')  
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Management Karyawan /</span> <span
                class="text-muted fw-light">Pegawai /</span> Edit</h4>

        <!-- Display Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit pegawai</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Nama Lengkap</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control @error('nama_pegawai') is-invalid @enderror" id="basic-icon-default-fullname"
                                    placeholder="Masukan Nama Lengkap" name="nama_pegawai"
                                    value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}" />
                            </div>
                            @error('nama_pegawai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Golongan</label>
                        <div class="col-sm-10">
                            <select name="id_jabatan" class="form-control @error('id_jabatan') is-invalid @enderror">
                                <option value="" disabled {{ !$pegawai->id_jabatan ? 'selected' : '' }}>-- Pilih Golongan --</option>
                                @foreach ($jabatan as $data)
                                    <option value="{{ $data->id }}"
                                        {{ $data->id == old('id_jabatan', $pegawai->id_jabatan) ? 'selected' : '' }}>
                                        {{ $data->nama_jabatan }}</option>
                                @endforeach
                            </select>
                            @error('id_jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Lokasi</label>
                        <div class="col-sm-10">
                            <select name="id_cabang" class="form-control @error('id_cabang') is-invalid @enderror">
                                <option value="" disabled {{ !$pegawai->id_cabang ? 'selected' : '' }}>-- Pilih Lokasi --</option>
                                @foreach ($cabang as $data)
                                <option value="{{ $data->id }}"
                                    {{ $data->id == old('id_cabang', $pegawai->id_cabang) ? 'selected' : '' }}>
                                    {{ $data->nama_cabang }}</option>
                                @endforeach
                            </select>
                            @error('id_cabang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Jenis Kelamin</label>
                        <div class="col-sm-10">
                            <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                <option value="" disabled {{ !$pegawai->jenis_kelamin ? 'selected' : '' }}>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-Laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>
                                    Laki-Laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Alamat</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <textarea type="text" class="form-control @error('alamat') is-invalid @enderror" id="basic-icon-default-fullname" placeholder="Masukan Alamat Anda"
                                    name="alamat">{{ old('alamat', $pegawai->alamat) }}</textarea>
                            </div>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Email</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="basic-icon-default-fullname"
                                    placeholder="Masukan Email Anda" name="email"
                                    value="{{ old('email', $pegawai->email) }}" />
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="device_user_id">ID Device Biometric</label>
                        <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="device_user_id"
                                    placeholder="Masukan ID User di Device Fingerprint (opsional)" name="device_user_id"
                                    value="{{ old('device_user_id', $pegawai->device_user_id) }}" />
                            </div>
                            <small class="form-text text-muted">ID unik karyawan di mesin biometric untuk integrasi absensi otomatis</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="gaji">Gaji</label>
                        <div class="col-sm-10">
                            @php
                                $gajiJabatan = $pegawai->jabatan ? $pegawai->jabatan->gaji_pokok : 0;
                                $gajiPegawai = $pegawai->gaji ?? 0;
                                $isGajiEdited = $gajiPegawai != $gajiJabatan;
                            @endphp
                            
                            <div class="input-group">
                                <input type="number" class="form-control @error('gaji') is-invalid @enderror" id="gaji"
                                    placeholder="Masukan Gaji" name="gaji"
                                    value="{{ old('gaji', $pegawai->gaji) }}" />
                                @if($gajiJabatan > 0)
                                    <button type="button" class="btn btn-outline-secondary" id="resetGaji" 
                                            onclick="resetToStandardSalary({{ $gajiJabatan }})"
                                            title="Reset ke gaji pokok jabatan">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                @endif
                            </div>
                            
                            @if($gajiJabatan > 0)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Gaji pokok untuk jabatan <strong>{{ $pegawai->jabatan->nama_jabatan }}</strong>: 
                                        <span class="fw-bold text-primary">Rp {{ number_format($gajiJabatan, 0, ',', '.') }}</span>
                                    </small>
                                    @if($isGajiEdited)
                                        <br>
                                        <small class="text-warning">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Gaji saat ini berbeda dari standar jabatan (sudah diedit manual)
                                        </small>
                                    @else
                                        <br>
                                        <small class="text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Gaji sesuai dengan standar jabatan
                                        </small>
                                    @endif
                                </div>
                            @endif
                            
                            @error('gaji')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <!-- Save Button -->
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <a href="{{ route('pegawai.index') }}" class="btn btn-primary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to reset salary to standard jabatan salary
        function resetToStandardSalary(standardSalary) {
            document.getElementById('gaji').value = standardSalary;
        }

        // Update salary when jabatan changes
        document.querySelector('select[name="id_jabatan"]').addEventListener('change', function() {
            const jabatanId = this.value;
            
            if (jabatanId) {
                // Get jabatan data (you might want to make an AJAX call here for dynamic data)
                // For now, we'll use the data already available in the page
                const jabatanOptions = @json($jabatan->keyBy('id'));
                
                if (jabatanOptions[jabatanId] && jabatanOptions[jabatanId].gaji_pokok) {
                    const gajiPokok = jabatanOptions[jabatanId].gaji_pokok;
                    
                    // Ask user if they want to update salary to match jabatan
                    if (confirm('Apakah Anda ingin mengupdate gaji sesuai dengan gaji pokok jabatan yang dipilih (Rp ' + 
                               new Intl.NumberFormat('id-ID').format(gajiPokok) + ')?')) {
                        document.getElementById('gaji').value = gajiPokok;
                    }
                }
            }
        });
    </script>
    @endsection
