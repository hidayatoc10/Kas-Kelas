<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nama_id');
    }
    public function bulan()
    {
        return $this->belongsTo(BulanPembayaran::class, 'bulan_id'); // sesuaikan
    }
}
