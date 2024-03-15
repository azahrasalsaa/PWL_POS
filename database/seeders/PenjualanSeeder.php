<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create 10 sales transactions for penjualan table which have columns penjualan_id, user_id, pembeli, penjualan_kode, penjualan_tanggal
        $data = [
            ['id_penjualan' => 1, 'user_id' => 3, 'pembeli' => 'Ferin', 'penjualan_kode' => 'PK1', 'penjualan_tanggal' => '2024-02-02'],
            ['id_penjualan' => 2, 'user_id' => 3, 'pembeli' => 'Zahra', 'penjualan_kode' => 'PK2', 'penjualan_tanggal' => '2024-02-02'],
            ['id_penjualan' => 3, 'user_id' => 3, 'pembeli' => 'Atha', 'penjualan_kode' => 'PK3', 'penjualan_tanggal' => '2024-02-02'],
            ['id_penjualan' => 4, 'user_id' => 3, 'pembeli' => 'Shofa', 'penjualan_kode' => 'PK4', 'penjualan_tanggal' => '2024-02-02'],
            ['id_penjualan' => 5, 'user_id' => 3, 'pembeli' => 'Shofwah', 'penjualan_kode' => 'PK5', 'penjualan_tanggal' => '2024-02-02'],
            ['id_penjualan' => 6, 'user_id' => 3, 'pembeli' => 'Kania', 'penjualan_kode' => 'PK6', 'penjualan_tanggal' => '2024-02-02'],
            ['id_penjualan' => 7, 'user_id' => 3, 'pembeli' => 'Lenka', 'penjualan_kode' => 'PK7', 'penjualan_tanggal' => '2024-02-02'],
            ['id_penjualan' => 8, 'user_id' => 3, 'pembeli' => 'Zze', 'penjualan_kode' => 'PK8', 'penjualan_tanggal' => '2024-02-02'],
            ['id_penjualan' => 9, 'user_id' => 3, 'pembeli' => 'Downy', 'penjualan_kode' => 'PK9', 'penjualan_tanggal' => '2024-02-02'],
            ['id_penjualan' => 10, 'user_id' => 3, 'pembeli' => 'Cici', 'penjualan_kode' => 'PK10', 'penjualan_tanggal' => '2024-02-02'],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}