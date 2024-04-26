<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    use HasFactory;
    // table name, primary key, and other property related to database
    protected $table = 't_penjualan';
    protected $primaryKey = 'id_penjualan';

    // fillable field
    protected $fillable = [
        'id_penjualan',
        'user_id',
        'pembeli',
        'penjualan_kode',
        'penjualan_tanggal'
    ];

    // relationship with user
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    // relationship with detail penjualan
    public function detail_penjualan()
    {
        return $this->hasMany(PenjualanDetailModel::class, 'id_penjualan', 'id_penjualan');
    }
}