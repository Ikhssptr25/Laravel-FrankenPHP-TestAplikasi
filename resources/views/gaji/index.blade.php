<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Data Gaji Karyawan</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-b from-green-400 to-green-100 font-sans">

<header class="bg-white shadow-md flex justify-between items-center px-1 py-1 border-b border-gray-200">
    <h1 class="text-2xl font-bold text-gray-800 px-12">
      <span class="text-gray-700">Z.</span><span class="text-green-600">Corporate</span>
    </h1>

    <div class="flex items-center gap-4 mr-2">
      {{-- Logo: simpan file di public/assets/logo.png --}}
      <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-14 px-10 mr-0">

      {{-- Logout: sementara arahkan ke /logout (nanti bisa ganti ke route auth Laravel) --}}
    <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit"
            class="flex items-center gap-2 text-black px-4 py-2 mt-5 rounded-lg text-sm font-semibold">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
        </svg>
        Keluar
    </button>
</form>
    </div>
  </header>

<main class="flex-1 px-4 md:px-10 py-10">
  <div class="w-full max-w-6xl mx-auto">

    {{-- flash --}}
    @if(session('ok'))
      <div class="mb-4 rounded border border-green-300 bg-green-50 text-green-800 px-4 py-2">
        {{ session('ok') }}
      </div>
    @endif

    {{-- error --}}
    @if($errors->any())
      <div class="mb-4 rounded border border-red-300 bg-red-50 text-red-800 px-4 py-2 text-sm">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-6">
      <a href="{{ route('dashboard') }}"
         class="w-full sm:w-auto text-center bg-black text-white px-5 py-2 font-semibold hover:bg-gray-800 rounded-sm">
        Kembali
      </a>
      <a href="{{ route('karyawan.index') }}"
         class="w-full sm:w-auto text-center bg-white text-green-600 border border-green-600 px-5 py-2 font-semibold hover:bg-green-50 rounded-sm">
        Data Karyawan
      </a>
    </div>

    <div class="bg-white shadow-lg w-full p-6 rounded-md">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h2 class="text-lg font-bold tracking-widest text-gray-800 border-b pb-2">KELOLA GAJI KARYAWAN</h2>
        <button onclick="openModalTambah()" class="bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2 rounded-full shadow">
          Add Gaji
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm gaji-table">
          <thead>
          <tr class="bg-gray-100 text-gray-700 border-b font-bold">
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Nama</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">No. Telp</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Periode</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Gaji Pokok</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Tunjangan</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Potongan</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Total Gaji</th>
            <th class="py-2 px-2 w-1/12 text-center">Action</th>
          </tr>
          </thead>
          <tbody>
          @foreach($gajis as $gaji)
            @php
              $total = max(0, (float)$gaji->gaji_pokok + (float)$gaji->tunjangan - (float)$gaji->potongan);
            @endphp
            <tr class="border-b hover:bg-gray-50">
              <td class="py-2 px-2 whitespace-nowrap truncate">{{ $gaji->karyawan->nama ?? '-' }}</td>
              <td class="py-2 px-2 whitespace-nowrap truncate">{{ $gaji->karyawan->no_telp ?? '-' }}</td>
              <td class="py-2 px-2 whitespace-nowrap">{{ $gaji->bulan }} {{ $gaji->tahun }}</td>
              <td class="py-2 px-2 whitespace-nowrap">Rp. {{ number_format((float)$gaji->gaji_pokok, 2, ',', '.') }}</td>
              <td class="py-2 px-2 whitespace-nowrap">Rp. {{ number_format((float)$gaji->tunjangan, 2, ',', '.') }}</td>
              <td class="py-2 px-2 whitespace-nowrap">Rp. {{ number_format((float)$gaji->potongan, 2, ',', '.') }}</td>
              <td class="py-2 px-2 whitespace-nowrap font-semibold">Rp. {{ number_format($total, 2, ',', '.') }}</td>
              <td class="py-2 px-2 text-center whitespace-nowrap">
                <button
                  onclick='openModalEdit(
                    {{ $gaji->id_gaji }},
                    {{ $gaji->id_karyawan }},
                    @json($gaji->bulan),
                    {{ $gaji->tahun }},
                    {{ (float)$gaji->gaji_pokok }},
                    {{ (float)$gaji->tunjangan }},
                    {{ (float)$gaji->potongan }}
                  )'
                  class="text-green-600 hover:text-green-800 mx-1">
                  <i class="ri-edit-2-fill text-xl"></i>
                </button>
                <button onclick="hapusData({{ $gaji->id_gaji }})"
                        class="text-red-600 hover:text-red-800 mx-1">
                  <i class="ri-delete-bin-5-fill text-xl"></i>
                </button>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>
</main>

<footer class="mt-10 text-gray-600 text-sm mb-6 text-center">
  © 2025 Intern. All rights reserved.
</footer>

{{-- Modal Tambah --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md rounded-lg shadow-lg overflow-hidden">
    <div class="bg-green-600 text-white text-center py-3 text-lg font-semibold">Tambah Data Gaji</div>
    <form id="formTambah" class="p-6 space-y-4">
      @csrf
      <div>
        <label for="id_karyawan" class="block font-semibold mb-1">Nama Karyawan</label>
        <select id="id_karyawan" name="id_karyawan" class="border border-gray-400 rounded w-full px-3 py-2" required onchange="isiOtomatisTambah()">
          <option value="">-- Pilih Karyawan --</option>
          @foreach($karyawans as $k)
            <option value="{{ $k->id }}" data-no="{{ $k->no_telp }}">{{ $k->nama }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block font-semibold mb-1">No. Telepon</label>
        <input id="no_telp_tambah" type="text"
               class="border border-gray-400 rounded w-full px-3 py-2 bg-gray-100"
               readonly placeholder="No Telepon otomatis terisi">
      </div>
      <div class="flex gap-2">
        <div class="flex-1">
          <label for="bulan" class="block font-semibold mb-1">Bulan</label>
          <select id="bulan" name="bulan" required class="border border-gray-400 rounded w-full px-3 py-2">
            @foreach($bulanList as $b)
              <option value="{{ $b }}">{{ $b }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex-1">
          <label for="tahun" class="block font-semibold mb-1">Tahun</label>
          <input id="tahun" name="tahun" type="number" min="2000" max="2100" value="{{ date('Y') }}"
                 class="border border-gray-400 rounded w-full px-3 py-2" required>
        </div>
      </div>
      <div>
        <label for="gaji_pokok" class="block font-semibold mb-1">Gaji Pokok</label>
        <input id="gaji_pokok" name="gaji_pokok" type="number" min="0" step="0.01" value="0"
               class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>
      <div>
        <label for="tunjangan" class="block font-semibold mb-1">Tunjangan</label>
        <input id="tunjangan" name="tunjangan" type="number" min="0" step="0.01" value="0"
               class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>
      <div>
        <label for="potongan" class="block font-semibold mb-1">Potongan</label>
        <input id="potongan" name="potongan" type="number" min="0" step="0.01" value="0"
               class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>
      <div class="flex justify-end space-x-3 mt-4">
        <button type="button" onclick="closeModalTambah()" class="bg-black text-white px-4 py-2 rounded-full">Kembali</button>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-full">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md rounded-lg shadow-lg overflow-hidden">
    <div class="bg-green-600 text-white text-center py-3 text-lg font-semibold">Edit Data Gaji</div>
    <form id="formEdit" class="p-6 space-y-4">
      @csrf
      @method('PUT')
      <input type="hidden" id="edit_id_gaji">

      <div>
        <label class="block font-semibold mb-1">Nama Karyawan</label>
        <input id="edit_nama_karyawan" type="text"
               class="border border-gray-400 rounded w-full px-3 py-2 bg-gray-100" readonly>
      </div>
      <div>
        <label class="block font-semibold mb-1">No. Telepon</label>
        <input id="no_telp_edit" type="text"
               class="border border-gray-400 rounded w-full px-3 py-2 bg-gray-100" readonly>
      </div>

      <div class="flex gap-2">
        <div class="flex-1">
          <label for="edit_bulan" class="block font-semibold mb-1">Bulan</label>
          <select id="edit_bulan" name="bulan" required
                  class="border border-gray-400 rounded w-full px-3 py-2">
            @foreach($bulanList as $b)
              <option value="{{ $b }}">{{ $b }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex-1">
          <label for="edit_tahun" class="block font-semibold mb-1">Tahun</label>
          <input id="edit_tahun" name="tahun" type="number" min="2000" max="2100" value="{{ date('Y') }}"
                 class="border border-gray-400 rounded w-full px-3 py-2" required>
        </div>
      </div>

      <div>
        <label for="edit_gaji_pokok" class="block font-semibold mb-1">Gaji Pokok</label>
        <input id="edit_gaji_pokok" name="gaji_pokok" type="number" min="0" step="0.01"
               class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>
      <div>
        <label for="edit_tunjangan" class="block font-semibold mb-1">Tunjangan</label>
        <input id="edit_tunjangan" name="tunjangan" type="number" min="0" step="0.01"
               class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>
      <div>
        <label for="edit_potongan" class="block font-semibold mb-1">Potongan</label>
        <input id="edit_potongan" name="potongan" type="number" min="0" step="0.01"
               class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>

      <div class="flex justify-end space-x-3 mt-4">
        <button type="button" onclick="closeModalEdit()" class="bg-black text-white px-4 py-2 rounded-full">Kembali</button>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-full">Update</button>
      </div>
    </form>
  </div>
</div>

{{-- form delete hidden --}}
<form id="formDelete" method="POST" class="hidden">
  @csrf
  @method('DELETE')
</form>

<script>
  function isiOtomatisTambah() {
    const select = document.getElementById('id_karyawan');
    const opt = select.options[select.selectedIndex];
    document.getElementById('no_telp_tambah').value = opt ? (opt.dataset.no || '') : '';
  }

  function openModalTambah() {
    document.getElementById('modalTambah').classList.remove('hidden');
  }
  function closeModalTambah() {
    document.getElementById('modalTambah').classList.add('hidden');
  }

  function showEditModal() {
    document.getElementById('modalEdit').classList.remove('hidden');
  }

  function closeModalEdit() {
    document.getElementById('modalEdit').classList.add('hidden');
  }


  // ===== Tambah (AJAX) =====
  document.getElementById('formTambah').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    try {
      const res = await fetch("{{ route('gaji.store') }}", {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
      });

      if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        if (err.errors) {
          const firstKey = Object.keys(err.errors)[0];
          alert(err.errors[firstKey][0]);
        } else {
          alert('Terjadi kesalahan saat menambah data.');
        }
        return;
      }

      const data = await res.json();
      alert(data.message || 'Data gaji berhasil ditambahkan.');
      closeModalTambah();
      location.reload();
    } catch (err) {
      console.error(err);
      alert('Error: ' + err);
    }
  });

  // ===== Buka modal edit & isi field =====
 function openModalEdit(id_gaji, id_karyawan, bulan, tahun, gaji_pokok, tunjangan, potongan) {
    const form = document.getElementById('formEdit');
    document.getElementById('edit_id_gaji').value = id_gaji;

    // cari baris tabel yang punya tombol edit dengan id_gaji ini
    const rows = document.querySelectorAll('tbody tr');
    let nama = '', no_telp = '';
    rows.forEach(tr => {
      const btn = tr.querySelector('button[onclick*="' + id_gaji + '"]');
      if (btn) {
        nama    = tr.children[0].innerText.trim(); // kolom Nama
        no_telp = tr.children[1].innerText.trim(); // kolom No Telp
      }
    });

    document.getElementById('edit_nama_karyawan').value = nama;
    document.getElementById('no_telp_edit').value       = no_telp;
    document.getElementById('edit_bulan').value         = bulan;
    document.getElementById('edit_tahun').value         = tahun;
    document.getElementById('edit_gaji_pokok').value    = gaji_pokok;
    document.getElementById('edit_tunjangan').value     = tunjangan;
    document.getElementById('edit_potongan').value      = potongan;

    // simpan URL update di data attribute
     form.dataset.updateUrl = "{{ url('/gaji') }}/" + id_gaji;

     showEditModal();
  }

  // ===== Edit (AJAX) =====
document.getElementById('formEdit').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = this;
    const url  = form.dataset.updateUrl;

    const formData = new FormData(form);
    // karena route update pakai PUT, kita spoof method via _method
    formData.append('_method', 'PUT');

    try {
      const res = await fetch(url, {
        method: 'POST', // tetap POST, Laravel baca _method=PUT
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
      });

      if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        if (err.errors) {
          const firstKey = Object.keys(err.errors)[0];
          alert(err.errors[firstKey][0]);
        } else {
          alert('Terjadi kesalahan saat update data.');
        }
        return;
      }

      const data = await res.json();
      alert(data.message || 'Data gaji berhasil diperbarui.');
      closeModalEdit();
      location.reload();
    } catch (err) {
      console.error(err);
      alert('Error: ' + err);
    }
  });

  // ===== Hapus (AJAX) =====
  async function hapusData(id_gaji) {
    if (!confirm('Apakah Anda yakin ingin menghapus gaji ini?')) return;

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'DELETE');

    try {
      const res = await fetch("{{ url('/gaji') }}/" + id_gaji, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
      });

      const data = await res.json().catch(() => ({}));
      if (data.status === 'success') {
        alert(data.message || 'Data gaji berhasil dihapus.');
        location.reload();
      } else {
        alert('Gagal menghapus data.');
      }
    } catch (err) {
      console.error(err);
      alert('Error: ' + err);
    }
  }
</script>

</body>
</html>
