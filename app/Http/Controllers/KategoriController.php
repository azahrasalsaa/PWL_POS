<?php

namespace App\Http\Controllers;

use App\DataTables\KategoriDataTable;
use App\Models\KategoriModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{

    // public function index()
    // {
    //     $data = [
    //         'kategori_kode' => 'SNK',
    //         'kategori_nama' => 'Snack/Makanan Ringan',
    //         'created_at' => now()
    //     ];
    //     DB::table('m_kategori')->insert($data);
    //     return 'Data successfully inserted!';

    //     $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camilan']);
    //     return 'Data successfully updated! ' . $row;

    //     $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
    //     return 'Data successfully deleted! ' . $row;

    //     $data = DB::table('m_kategori')->get();
    //     return view('kategori', ['data' => $data]);
    // }

    public function index(KategoriDataTable $dataTable)
    {
        return $dataTable->render('kategori.index');
    }

    public function create() {
        return view('kategori.create');
    }

    public function store(Request $request): RedirectResponse {
        
        $validated = $request->validateWithBag('category', [
            'kategori_kode' => 'bail|required|unique:m_kategori|max:255',
            'kategori_nama' => 'required'
        ]);

        KategoriModel::create([
            'kategori_kode' => $validated['kategori_kode'],
            'kategori_nama' => $validated['kategori_nama']
        ]);
        
        return redirect('/kategori');
    }

    public function update($id) {
        $data = KategoriModel::find($id);
        return view('kategori.update', ['kategori' => $data]);
    }

    public function update_save(Request $request, $id) {
        $data = KategoriModel::find($id);
        $data->kategori_kode = $request->kodeKategori;
        $data->kategori_nama = $request->namaKategori;
        $data->save();

        return redirect('/kategori');
    }

    public function delete($id) {
        $data = KategoriModel::find($id);
        $data->delete();

        return redirect('/kategori');
    }
}

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\DataTables\KategoriDataTable;
// use App\Models\KategoriModel;
// use Illuminate\Support\Facades\DB;
// use App\Http\Requests\StorePostRequest;
// use Yajra\DataTables\Facades\DataTables;

// class KategoriController extends Controller
// {
//     public function index()
//     {
//         $breadcrumb = (object) [
//             'title' => 'Daftar Kategori',
//             'list' => ['Home', 'Kategori']
//         ];

//         $page = (object) [
//             'title' => 'Daftar Kategori yang terdaftar dalam sistem'
//         ];

//         $activeMenu = 'kategori'; //set menu yang aktif

//         return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
//     }
//     public function list(Request $request)
//     {
//         $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

//         return DataTables::of($kategoris)
//             ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
//             ->addColumn('aksi', function ($kategori) {
//                 $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
//                 $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
//                 $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">' .
//                     csrf_field() . method_field('DELETE') .
//                     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
//                 return $btn;
//             })
//             ->rawColumns(['aksi'])
//             ->make(true);
//     }

//     public function create()
//     {
//         $breadcrumb = (object) [
//             'title' => 'Tambah Kategori',
//             'list' => ['Home', 'Kategori', 'Tambah']
//         ];

//         $page = (object) [
//             'title' => 'Tambah Kategori Baru'
//         ];

//         $activeMenu = 'kategori';

//         return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'kategori_kode' => 'required|string|min:3|max:10|unique:m_kategori,kategori_kode',
//             'kategori_nama' => 'required|string|max:100'
//         ]);
//         KategoriModel::create([
//             'kategori_kode' => $request->kategori_kode,
//             'kategori_nama' => $request->kategori_nama
//         ]);

//         return redirect('/kategori')->with('success', 'Data kategori berhasil ditambahkan');
//     }

//     public function show($id)
//     {
//         $kategori = KategoriModel::find($id);
//         $breadcrumb = (object) [
//             'title' => 'Detail Kategori',
//             'list' => ['Home', 'Kategori', 'Detail']
//         ];

//         $page = (object) [
//             'title' => 'Detail Kategori'
//         ];

//         $activeMenu = 'kategori'; //set menu yang aktif
//         return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
//     }

//     public function edit($id)
//     {
//         $kategori = KategoriModel::find($id);

//         $breadcrumb = (object) [
//             'title' => 'Edit Kategori',
//             'list' => ['Home', 'Kategori', 'Edit']
//         ];
//         $page = (object) [
//             'title' => 'Edit Kategori'
//         ];
//         $activeMenu = 'kategori'; //set menu yang aktif

//         return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
//     }
//     public function update(Request $request, $id)
//     {
//         $request->validate([
//             'kategori_kode' => 'required|string|min:3|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
//             'kategori_nama' => 'required|string|max:100'
//         ]);

//         return redirect('/kategori');
//         KategoriModel::where('kategori_id', $id)->update([
//             'kategori_kode' => $request->kategori_kode,
//             'kategori_nama' => $request->kategori_nama
//         ]);

//         return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
//     }
//     // Menghapus data kategori
//     public function destroy($id)
//     {
//         $check = KategoriModel::find($id);
//         if (!$check) {
//             return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
//         }

//         try {
//             KategoriModel::destroy($id);
//             return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
//         } catch (\Illuminate\Database\QueryException $e) {
//             return redirect('/kategori')->with('error', 'Data kategori gagal dihapus');
//         }
//     }

// }
