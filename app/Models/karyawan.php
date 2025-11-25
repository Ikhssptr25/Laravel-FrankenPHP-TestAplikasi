<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Models\Gaji;

class Karyawan extends Model
{
    protected $table = 'data_karyawan';   // nama tabel di DB
    protected $fillable = ['nama','jabatan','alamat','no_telp'];
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */
    public function gajis()
    {
        return $this->hasMany(Gaji::class, 'id_karyawan', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY HELPER
    |--------------------------------------------------------------------------
    */

    // untuk index()
    public static function getAllOrdered()
    {
        return static::orderBy('id')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */

    // rules untuk STORE (tambah)
    public static function rulesStore(): array
    {
        return [
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
                'unique:data_karyawan,no_telp',
            ],
        ];
    }

    // rules untuk UPDATE (edit)
    public static function rulesUpdate(int $id): array
    {
        return [
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
                Rule::unique('data_karyawan', 'no_telp')->ignore($id, 'id'),
            ],
        ];
    }

    // pesan error
    public static function messages(): array
    {
        return [
            'nama.regex'    => 'Nama hanya boleh huruf dan spasi.',
            'jabatan.regex' => 'Jabatan hanya boleh huruf dan spasi.',
            'alamat.regex'  => 'Alamat tidak valid, minimal 3 karakter dan hanya boleh huruf, angka, spasi, titik, koma, minus, slash /, atau #.',
            'no_telp.regex' => 'Nomor telepon harus diawali dengan 628 dan diikuti 10-13 digit angka.',
            'no_telp.unique'=> 'Nomor telepon sudah terdaftar.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD HELPER
    |--------------------------------------------------------------------------
    */

    public static function createNew(array $data): self
    {
        return static::create($data);
    }

    public function updateExisting(array $data): bool
    {
        return $this->update($data);
    }

    public function deleteSafely(): bool
    {
        // kalau nanti mau cek relasi dulu, bisa tambahin logic di sini
        return $this->delete();
    }
}
