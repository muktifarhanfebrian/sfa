<h5>Data Gudang</h5>
<hr>
<div class="row">
    <div class="col-md-4">
        <h6>Tambah Gudang Baru</h6>
        <form action="{{ route('settings.locations.gudang.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="gudang_name" class="form-label">Nama Gudang</label>
                <input type="text" name="name" class="form-control" id="gudang_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <div class="col-md-8">
        <h6>Daftar Gudang</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>Nama Gudang</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($gudangs as $gudang)
                        <tr>
                            <td>{{ $gudang->name }}</td>
                            <td>
                                {{-- PERUBAHAN DI SINI --}}
                                <form id="delete-gudang-{{ $gudang->id }}" action="{{ route('settings.locations.gudang.destroy', $gudang->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('delete-gudang-{{ $gudang->id }}', 'Menghapus gudang akan menghapus semua gate dan block di dalamnya!')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Belum ada data gudang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
