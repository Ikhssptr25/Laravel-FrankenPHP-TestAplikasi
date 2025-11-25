<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'data_karyawan';   // nama tabel lama
    protected $fillable = ['nama','jabatan','alamat','no_telp'];
    public $timestamps = false; // tabel lama tidak punya created_at/updated_at

    public function gajis()
    {
        return $this->hasMany(Gaji::class, 'id_karyawan', 'id');
    }
}
