<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Data Karyawan</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
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

    {{-- flash message --}}
    @if(session('ok'))
      <div class="mb-4 rounded border border-green-300 bg-green-50 text-green-800 px-4 py-2">
        {{ session('ok') }}
      </div>
    @endif

    {{-- error form (kalau error dari submit non-AJAX) --}}
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
         class="w-full sm:w-auto text-center bg-black text-white px-5 py-2 font-semibold hover:bg-gray-800 transition rounded-sm">
        Kembali
      </a>
      <a href="{{ route('gaji.index') }}"
         class="w-full sm:w-auto text-center bg-white text-green-600 border border-green-600 px-5 py-2 font-semibold hover:bg-green-50 transition rounded-sm">
        Gaji Karyawan
      </a>
    </div>

    <div class="bg-white shadow-lg w-full p-8 rounded-md">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h2 class="text-lg font-bold tracking-widest text-gray-800 border-b pb-2">
          KELOLA DATA KARYAWAN
        </h2>
        <button onclick="openModalTambah()"
                class="bg-green-600 text-white px-5 py-2 rounded-full font-semibold hover:bg-green-700 transition">
          Add Data
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left karyawan-table">
          <thead>
          <tr class="bg-gray-100 text-gray-700 border-b font-bold">
            <th class="py-3 px-4 font-semibold">No</th>
            <th class="py-3 px-4 font-semibold">Nama</th>
            <th class="py-3 px-4 font-semibold">Jabatan</th>
            <th class="py-3 px-4 font-semibold">Alamat</th>
            <th class="py-3 px-4 font-semibold">No Telp</th>
            <th class="py-3 px-4 text-center font-semibold">Action</th>
          </tr>
          </thead>
          <tbody class="text-gray-800">
          @php $no = 1; @endphp
          @foreach($karyawans as $karyawan)
            <tr class="border-b hover:bg-gray-50 transition">
              <td class="py-2 px-3 font-semibold">{{ $no++ }}</td>
              <td class="py-2 px-3">{{ $karyawan->nama }}</td>
              <td class="py-2 px-3">{{ $karyawan->jabatan }}</td>
              <td class="py-2 px-3">{{ $karyawan->alamat }}</td>
              <td class="py-2 px-3">{{ $karyawan->no_telp }}</td>
              <td class="py-2 px-3 text-center">
                <button
                  onclick="editData({{ $karyawan->id }}, '{{ e($karyawan->nama) }}', '{{ e($karyawan->jabatan) }}', '{{ e($karyawan->alamat) }}', '{{ e($karyawan->no_telp) }}')"
                  class="text-green-600 hover:text-green-800 mx-1">
                  <i class="ri-edit-2-fill text-xl"></i>
                </button>

                <button onclick="hapusData({{ $karyawan->id }})"
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

<footer class="text-center text-gray-700 text-sm py-4">
  © 2025 Intern. All rights reserved.
</footer>

{{-- Modal Tambah Data --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md rounded-xl shadow-xl">
    <div class="bg-green-600 text-white text-center py-3 rounded-t-xl text-lg font-semibold shadow-md shadow-green-300">
      Tambah Data Karyawan
    </div>

    <form id="formTambah" class="p-6 space-y-4 flex flex-col"
          method="POST" action="{{ route('karyawan.store') }}">
      @csrf
      <div>
        <label class="block font-semibold mb-1 text-black">Nama
          <input type="text" name="nama"
                 class="border border-green-400 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                 required>
        </label>
      </div>
      <div>
        <label class="block font-semibold mb-1 text-black">Jabatan
          <input type="text" name="jabatan"
                 class="border border-green-400 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                 required>
        </label>
      </div>
      <div>
        <label class="block font-semibold mb-1 text-black">Alamat
          <input type="text" name="alamat"
                 class="border border-green-400 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                 required>
        </label>
      </div>
      <div>
        <label class="block font-semibold mb-1 text-black">No Telp
          <input type="text" name="no_telp"
                 class="border border-green-400 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                 placeholder="628xxxxxxxxxx" required>
        </label>
      </div>

      <div class="flex justify-end space-x-3 mt-2">
        <button type="button" onclick="closeModalTambah()"
                class="px-4 py-2 bg-black text-white rounded-full font-semibold">
          Kembali
        </button>
        <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded-full">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit Data --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md shadow-lg overflow-hidden rounded-xl">
    <div class="bg-green-600 text-white text-center py-3 text-lg font-semibold shadow-md">
      Edit Data Karyawan
    </div>

    <form id="formEdit" class="p-6 space-y-4 flex flex-col" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" id="edit_id">

      <div>
        <label class="block font-semibold mb-1 text-black">Nama
          <input type="text" name="nama" id="edit_nama"
                 class="border border-green-400 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                 required>
        </label>
      </div>
      <div>
        <label class="block font-semibold mb-1 text-black">Jabatan
          <input type="text" name="jabatan" id="edit_jabatan"
                 class="border border-green-400 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                 required>
        </label>
      </div>
      <div>
        <label class="block font-semibold mb-1 text-black">Alamat
          <input type="text" name="alamat" id="edit_alamat"
                 class="border border-green-400 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                 required>
        </label>
      </div>
      <div>
        <label class="block font-semibold mb-1 text-black">No Telp
          <input type="text" name="no_telp" id="edit_no_telp"
                 class="border border-green-400 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                 placeholder="628xxxxxxxxxx" required>
        </label>
      </div>

      <div class="flex justify-end space-x-3 mt-2">
        <button type="button" onclick="closeModalEdit()"
                class="px-4 py-2 bg-black text-white rounded-full font-semibold">
          Kembali
        </button>
        <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded-full">
          Update
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Form delete tersembunyi (kalau mau pakai submit biasa) --}}
<form id="formDelete" method="POST" class="hidden">
  @csrf
  @method('DELETE')
</form>

<script>
  function openModalTambah() {
    document.getElementById('modalTambah').classList.remove('hidden');
  }
  function closeModalTambah() {
    document.getElementById('modalTambah').classList.add('hidden');
  }

  function openModalEditModal() {
    document.getElementById('modalEdit').classList.remove('hidden');
  }
  function closeModalEdit() {
    document.getElementById('modalEdit').classList.add('hidden');
  }

  // ===== Tambah Data (AJAX) =====
  document.getElementById('formTambah').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = this;
    const noTelp = form.no_telp.value.trim();
    if (!/^628\d{7,10}$/.test(noTelp)) {
      alert("Nomor telepon harus diawali dengan 628 dan diikuti 10-13 digit angka.");
      return;
    }

    const formData = new FormData(form);

    try {
      const res = await fetch("{{ route('karyawan.store') }}", {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: formData
      });

      const data = await res.json().catch(() => ({}));

      if (res.ok && data.status === 'success') {
        alert(data.message || 'Data berhasil ditambahkan!');
        closeModalTambah();
        location.reload();
      } else if (data.errors) {
        const firstKey = Object.keys(data.errors)[0];
        alert(data.errors[firstKey][0]);
      } else {
        alert('Gagal menambah data.');
      }
    } catch (err) {
      console.error(err);
      alert('Terjadi error saat menambah data.');
    }
  });

  // ===== Isi Modal Edit & simpan URL update =====
  function editData(id, nama, jabatan, alamat, no_telp) {
    const form = document.getElementById('formEdit');

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_jabatan').value = jabatan;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_no_telp').value = no_telp;

    form.dataset.updateUrl = "{{ url('/karyawan') }}/" + id;

    openModalEditModal();
  }

  // ===== Edit Data (AJAX) =====
  document.getElementById('formEdit').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = this;
    const noTelp = form.no_telp.value.trim();
    if (!/^628\d{7,10}$/.test(noTelp)) {
      alert("Nomor telepon harus diawali dengan 628 dan diikuti 10-13 digit angka.");
      return;
    }

    const url = form.dataset.updateUrl;
    const formData = new FormData(form);
    formData.append('_method', 'PUT'); // spoof method PUT

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: formData
      });

      const data = await res.json().catch(() => ({}));

      if (res.ok && data.status === 'success') {
        alert(data.message || 'Data berhasil diperbarui!');
        closeModalEdit();
        location.reload();
      } else if (data.errors) {
        const firstKey = Object.keys(data.errors)[0];
        alert(data.errors[firstKey][0]);
      } else {
        alert('Gagal memperbarui data.');
      }
    } catch (err) {
      console.error(err);
      alert('Terjadi error saat mengupdate data.');
    }
  });

  // ===== Hapus Data (AJAX) =====
  async function hapusData(id) {
    if (!confirm('Yakin ingin menghapus data ini?')) return;

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'DELETE');

    try {
      const res = await fetch("{{ url('/karyawan') }}/" + id, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: formData
      });

      const data = await res.json().catch(() => ({}));

      if (res.ok && data.status === 'success') {
        alert(data.message || 'Data berhasil dihapus!');
        location.reload();
      } else {
        alert('Gagal menghapus data.');
      }
    } catch (err) {
      console.error(err);
      alert('Terjadi error saat menghapus data.');
    }
  }
</script>

</body>
</html>
