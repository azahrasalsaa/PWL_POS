<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserModel;
use App\Models\StokModel;

class PenjualanController extends Controller
{
    // Menampilkan halaman awal penjualan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar penjualan',
            'list' => ['Home', 'penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';

        $users = UserModel::all();

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'user' => $users]);
    }

    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('id_penjualan', 'penjualan_kode', 'user_id', 'pembeli', 'penjualan_tanggal')->with('user');

        if ($request->user_id) {
            $penjualans->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<a href="' . url('/penjualan/' . $penjualan->id_penjualan) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/penjualan/' . $penjualan->id_penjualan . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/penjualan/' . $penjualan->id_penjualan) . '">' .
                    csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah penjualan',
            'list' => ['Home', 'penjualan', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah penjualan'
        ];

        $activeMenu = 'penjualan';

        $users = UserModel::all();
        $barang = BarangModel::all();

        return view('penjualan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'user' => $users, 'barang' => $barang]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'pembeli' => 'required|string|max:100',
        ]);

        $penjualan = new PenjualanModel();
        $penjualan->user_id = $request->user_id;
        $penjualan->pembeli = $request->pembeli;
        $penjualan->penjualan_kode = 'PJ-' . date('YmdHis');
        $penjualan->penjualan_tanggal = date('Y-m-d H:i:s');
        $penjualan->save();

        for ($i = 0; $i < count($request->barang_id); $i++) {
            $detail = new penjualanDetailModel();
            $detail->id_penjualan = $penjualan->id_penjualan;
            $detail->barang_id = $request->barang_id[$i];
            $detail->jumlah = $request->jumlah[$i];

            $barang = BarangModel::find($request->barang_id[$i]);
            $detail->harga = $barang->harga_jual;

            $detail->save();
            // $barang->save();

            // Kurangi stok barang
            StokModel::where('barang_id', $request->barang_id[$i])->decrement('stok_jumlah', $request->jumlah[$i]);
        }

        // dd($request->all());

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
    }

    // Menampilkan detail penjualan
    public function show($id)
    {
        $penjualan = PenjualanModel::with('user')->with('detail_penjualan')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail penjualan',
            'list' => ['Home', 'penjualan', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail penjualan'
        ];

        $activeMenu = 'penjualan'; //set menu yang aktif

        return view('penjualan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu'
        => $activeMenu, 'penjualan' => $penjualan]);
    }

    // Menampilkan halaman form edit penjualan
    public function edit($id)
    {
        $penjualan = PenjualanModel::with('user')->with('detail_penjualan')->find($id);

        $breadcrumb = (object) [
            'title' => 'Edit penjualan',
            'list' => ['Home', 'penjualan', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit penjualan'
        ];

        $activeMenu = 'penjualan'; //set menu yang aktif

        $barang = BarangModel::all();
        $users = UserModel::all();

        return view('penjualan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu'
        => $activeMenu, 'penjualan' => $penjualan, 'barang' => $barang, 'user' => $users]);
    }

    // Menyimpan perubahan data penjualan
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'pembeli' => 'required|string|max:100',
        ]);

        $penjualan = PenjualanModel::find($id);
        $penjualan->user_id = $request->user_id;
        $penjualan->pembeli = $request->pembeli;
        $penjualan->save();

        $penjualan->detail_penjualan()->delete();

        for ($i = 0; $i < count($request->barang_id); $i++) {
            $detail = new penjualanDetailModel();
            $detail->id_penjualan = $penjualan->id_penjualan;
            $detail->barang_id = $request->barang_id[$i];
            $detail->jumlah = $request->jumlah[$i];

            $barang = BarangModel::find($request->barang_id[$i]);
            $detail->harga = $barang->harga_jual;

            $detail->save();
            $barang->save();
        }

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil diubah');
    }

    // Menghapus data penjualan
    public function destroy($id)
    {
        $check = PenjualanModel::find($id);
        if (!$check) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            penjualanDetailModel::where('id_penjualan', $id)->delete();
            PenjualanModel::find($id)->delete();
            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus ' . $e->getMessage());
        }
    }
}