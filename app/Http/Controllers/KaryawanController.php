<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::orderBy('id')->get();

        return view('karyawan.index', compact('karyawans'));
    }

    public function store(Request $request)
    {
        // VALIDASI INPUT (setara dengan tambah_karyawan.php)
        $data = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                // hanya huruf dan spasi
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'jabatan' => [
                'required',
                'string',
                'max:100',
                // hanya huruf dan spasi
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'alamat' => [
                'required',
                'string',
                'max:255',
                // minimal 3 karakter, huruf, angka, spasi, titik, koma, minus, slash, #
                'regex:/^[a-zA-Z0-9\s\.,\-\/#]{3,}$/',
            ],
            'no_telp' => [
                'required',
                // harus diawali 628 dan 10–13 digit
                'regex:/^628\d{7,10}$/',
                // tidak boleh duplikat
                'unique:data_karyawan,no_telp',
            ],
        ], [
            // pesan error custom biar mirip versi native
            'nama.regex'    => 'Nama hanya boleh huruf dan spasi.',
            'jabatan.regex' => 'Jabatan hanya boleh huruf dan spasi.',
            'alamat.regex'  => 'Alamat tidak valid, minimal 3 karakter dan hanya boleh huruf, angka, spasi, titik, koma, minus, slash /, atau #.',
            'no_telp.regex' => 'Nomor telepon harus diawali dengan 628 dan diikuti 10-13 digit angka.',
            'no_telp.unique'=> 'Nomor telepon sudah terdaftar.',
        ]);

        $karyawan = Karyawan::create($data);

        // Jika request dari AJAX (fetch), balikan JSON
        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Data karyawan berhasil ditambahkan.',
                'data'    => $karyawan,
            ]);
        }

        // fallback kalau submit form biasa
        return redirect()->route('karyawan.index')->with('ok', 'Data karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        // VALIDASI INPUT (setara edit_karyawan.php, termasuk cek no_telp tidak duplikat selain dirinya sendiri)
        $data = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'jabatan' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'alamat' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\.,\-\/#]{3,}$/',
            ],
            'no_telp' => [
                'required',
                'regex:/^628\d{7,10}$/',
                // unique tapi ignore id karyawan yang sedang di-edit
                Rule::unique('data_karyawan', 'no_telp')->ignore($karyawan->id, 'id'),
            ],
        ], [
            'nama.regex'    => 'Nama hanya boleh huruf dan spasi.',
            'jabatan.regex' => 'Jabatan hanya boleh huruf dan spasi.',
            'alamat.regex'  => 'Alamat tidak valid, minimal 3 karakter dan hanya boleh huruf, angka, spasi, titik, koma, minus, slash /, atau #.',
            'no_telp.regex' => 'Nomor telepon harus diawali dengan 628 dan diikuti 10-13 digit angka.',
            'no_telp.unique'=> 'Nomor telepon sudah terdaftar.',
        ]);

        // Karyawan sudah dipastikan ada oleh route-model binding, jadi tidak perlu cek lagi pakai SELECT 1

        $karyawan->update($data);

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Data karyawan berhasil diperbarui.',
                'data'    => $karyawan,
            ]);
        }

        return redirect()->route('karyawan.index')->with('ok', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Request $request, Karyawan $karyawan)
    {
        // Route model binding sudah memastikan id valid, kalau tidak akan 404 otomatis
        $karyawan->delete();

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Data karyawan berhasil dihapus.',
            ]);
        }

        return redirect()->route('karyawan.index')->with('ok', 'Data karyawan berhasil dihapus.');
    }
}

