@extends('layouts.main')

@section('title', 'Gaji')

@push('style')
    <style>
        .card {
            width: 100%;
            max-width: 1200px;
        }
    </style>
@endpush

@section('main')
    <div class="card">
        <div class="card-header text-center">
            <h5>Employee PT Argo Industri</h5>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Tombol Tambah di sisi kiri -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    + Tambah
                </button>

                <!-- Tombol Logout di sisi kanan -->
                <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a>
            </div>

            <table class="table table-responsive table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Tahun Masuk</th>
                        <th>Gaji Pokok</th>
                        <th>Jabatan</th>
                        <th>Total Gaji</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gajis as $gaji)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $gaji->nip }}</td>
                            <td>{{ $gaji->nama }}</td>
                            <td>{{ $gaji->tahun_masuk }}</td>
                            <td>Rp. {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                            <td>{{ $gaji->jabatan }}</td>
                            <td>Rp. {{ number_format($gaji->gaji_akhir, 0, ',', '.') }}</td>
                            <td>{{ $gaji->user->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('gaji.cetak', $gaji->id) }}" class="btn btn-info btn-sm">Cetak</a>

                                <!-- Delete Form -->
                                <form action="{{ route('gaji.destroy', $gaji->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Tambah Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEmployeeForm" action="{{ route('gaji.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                            <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" required>
                        </div>
                        <div class="mb-3">
                            <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                            <input type="number" class="form-control" id="gaji_pokok" name="gaji_pokok" required>
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select class="form-select" id="jabatan" name="jabatan" required>
                                <option value="" disabled selected>Pilih Jabatan</option>
                                <option value="satpam">Satpam</option>
                                <option value="sales">Sales</option>
                                <option value="administrasi">Administrasi</option>
                                <option value="manajer">Manajer</option>
                            </select>
                        </div>

                        <!-- Dynamic Fields -->
                        <div class="mb-3 d-none" id="fieldJamLembur">
                            <label for="jam_lembur" class="form-label">Jam Lembur</label>
                            <input type="number" class="form-control" id="jam_lembur" name="jam_lembur" value="0">
                        </div>
                        <div class="mb-3 d-none" id="fieldJumlahPelanggan">
                            <label for="jumlah_pelanggan" class="form-label">Jumlah Pelanggan Terlayani</label>
                            <input type="number" class="form-control" id="jumlah_pelanggan" name="jumlah_pelanggan"
                                value="0">
                        </div>
                        <div class="mb-3 d-none" id="fieldPeningkatanPenjualan">
                            <label for="peningkatan_penjualan" class="form-label">Peningkatan Penjualan (%)</label>
                            <input type="number" class="form-control" id="peningkatan_penjualan"
                                name="peningkatan_penjualan" value="0">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const jabatanSelect = document.getElementById('jabatan');
        const fieldJamLembur = document.getElementById('fieldJamLembur');
        const fieldJumlahPelanggan = document.getElementById('fieldJumlahPelanggan');
        const fieldPeningkatanPenjualan = document.getElementById('fieldPeningkatanPenjualan');

        jabatanSelect.addEventListener('change', () => {
            // Hide all fields first
            fieldJamLembur.classList.add('d-none');
            fieldJumlahPelanggan.classList.add('d-none');
            fieldPeningkatanPenjualan.classList.add('d-none');

            // Show relevant field based on the selected job
            const selectedJabatan = jabatanSelect.value;
            if (selectedJabatan === 'satpam') {
                fieldJamLembur.classList.remove('d-none');
            } else if (selectedJabatan === 'sales') {
                fieldJumlahPelanggan.classList.remove('d-none');
            } else if (selectedJabatan === 'manajer') {
                fieldPeningkatanPenjualan.classList.remove('d-none');
            }
        });
    </script>
@endpush
