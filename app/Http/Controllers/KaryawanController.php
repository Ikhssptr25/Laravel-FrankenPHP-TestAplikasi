<?php

namespace App\Http\Controllers;

use App\Models\karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        // pakai helper dari model
        $karyawans = Karyawan::getAllOrdered();

        return view('karyawan.index', compact('karyawans'));
    }

    public function store(Request $request)
    {
        // VALIDASI pakai rules & messages di model
        $data = $request->validate(
            Karyawan::rulesStore(),
            Karyawan::messages()
        );

        $karyawan = Karyawan::createNew($data);

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Data karyawan berhasil ditambahkan.',
                'data'    => $karyawan,
            ]);
        }

        return redirect()
            ->route('karyawan.index')
            ->with('ok', 'Data karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $data = $request->validate(
            Karyawan::rulesUpdate($karyawan->id),
            Karyawan::messages()
        );

        $karyawan->updateExisting($data);

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Data karyawan berhasil diperbarui.',
                'data'    => $karyawan,
            ]);
        }

        return redirect()
            ->route('karyawan.index')
            ->with('ok', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Request $request, Karyawan $karyawan)
    {
        $karyawan->deleteSafely();

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Data karyawan berhasil dihapus.',
            ]);
        }

        return redirect()
            ->route('karyawan.index')
            ->with('ok', 'Data karyawan berhasil dihapus.');
    }
}
