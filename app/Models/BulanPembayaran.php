<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulanPembayaran extends Model
{
    use HasFactory;
    protected $table = 'bulan_pembayaran';
    protected $guarded = [
        'id'
    ];
    public function transaksi()
    {
        return $this->hasMany(TransaksiMasuk::class, 'bulan_id'); // sesuaikan nama foreign key
    }
}
