<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GajiController extends Controller
{
    protected array $bulanList = [
        'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember',
    ];

    public function index()
    {
        return view('gaji.index', [
            'gajis'      => Gaji::sorted()->get(),
            'karyawans'  => Karyawan::orderBy('nama')->get(),
            'bulanList'  => $this->bulanList
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_karyawan' => ['required', 'exists:data_karyawan,id'],
            'bulan'       => ['required', Rule::in($this->bulanList)],
            'tahun'       => ['required', 'integer', 'between:2000,2100'],
            'gaji_pokok'  => ['required', 'numeric', 'min:0'],
            'tunjangan'   => ['required', 'numeric', 'min:0'],
            'potongan'    => ['required', 'numeric', 'min:0'],
        ]);

        // cek duplikat periode → Model
        if (Gaji::sudahAdaPeriode($data['id_karyawan'], $data['bulan'], $data['tahun'])) {
            return $this->validationError($request, [
                'periode' => ['Gaji untuk periode ini sudah ada.'],
            ]);
        }

        $data['total_gaji'] = Gaji::hitungTotal(
            $data['gaji_pokok'], 
            $data['tunjangan'], 
            $data['potongan']
        );

        $gaji = Gaji::create($data);

        return $request->ajax()
            ? response()->json(['status' => 'success', 'message' => 'Berhasil menambah data', 'data' => $gaji])
            : redirect()->route('gaji.index')->with('ok', 'Berhasil menambah data');
    }

    public function update(Request $request, Gaji $gaji)
    {
        $data = $request->validate([
            'bulan'       => ['required', Rule::in($this->bulanList)],
            'tahun'       => ['required', 'integer', 'between:2000,2100'],
            'gaji_pokok'  => ['required', 'numeric', 'min:0'],
            'tunjangan'   => ['required', 'numeric', 'min:0'],
            'potongan'    => ['required', 'numeric', 'min:0'],
        ]);

        // cek duplikat saat update → Model
        if (Gaji::duplikatSaatUpdate($gaji->id_karyawan, $data['bulan'], $data['tahun'], $gaji->id_gaji)) {
            return $this->validationError($request, [
                'periode' => ['Gaji untuk periode ini sudah ada.'],
            ]);
        }

        $data['total_gaji'] = Gaji::hitungTotal(
            $data['gaji_pokok'],
            $data['tunjangan'],
            $data['potongan']
        );

        $gaji->update($data);

        return $request->ajax()
            ? response()->json(['status' => 'success', 'message' => 'Berhasil update data'])
            : redirect()->route('gaji.index')->with('ok', 'Berhasil update data');
    }

    public function destroy(Request $request, Gaji $gaji)
    {
        $gaji->delete();

        return $request->ajax()
            ? response()->json(['status' => 'success', 'message' => 'Berhasil hapus data'])
            : redirect()->route('gaji.index')->with('ok', 'Berhasil hapus data');
    }

    protected function validationError(Request $request, array $errors)
    {
        return $request->ajax()
            ? response()->json(['status' => 'error', 'errors' => $errors], 422)
            : back()->withErrors($errors)->withInput();
    }
}
