<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\karyawan;

class Gaji extends Model
{
    protected $table = 'gaji_karyawan';
    protected $primaryKey = 'id_gaji';
    public $timestamps = false;

    protected $fillable = [
        'id_karyawan',
        'bulan',
        'tahun',
        'gaji_pokok',
        'tunjangan',
        'potongan',
        'total_gaji',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id');
    }

    // ============
    // SCOPE QUERY
    // ============

    public function scopeSorted($query)
    {
        return $query
            ->with('karyawan')
            ->orderBy('tahun', 'desc')
            ->orderByRaw("
                FIELD(bulan,
                'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember')
            ");
    }

    // cek duplikat periode (tambah)
    public static function sudahAdaPeriode($idKaryawan, $bulan, $tahun)
    {
        return self::where('id_karyawan', $idKaryawan)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();
    }

    // cek duplikat periode saat edit
    public static function duplikatSaatUpdate($idKaryawan, $bulan, $tahun, $idGaji)
    {
        return self::where('id_karyawan', $idKaryawan)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('id_gaji', '!=', $idGaji)
            ->exists();
    }

    // hitung total gaji
    public static function hitungTotal($gajiPokok, $tunjangan, $potongan): float
    {
        return round(max(0, $gajiPokok + $tunjangan - $potongan), 2);
    }

    // helper untuk view
    public function getPeriodeAttribute()
    {
        return "{$this->bulan} {$this->tahun}";
    }
}
