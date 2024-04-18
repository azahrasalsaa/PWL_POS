@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Kategori')
@section('content_header_title', 'Kategori')
@section('content_header_subtitle', 'Update')

{{-- content body main page content --}}
@section('content')
    <div class="container">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Kategori</h3>
            </div>

            <form method="post" action="{{ route('/kategori/save_update', $data->kategori_id)}}">
                @csrf
                @method('PUT')

                <div class='card'></div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="kodeKategori">Kode Kategori</label>
                        <input type="text" name="kodeKategori" id="kodeKategori" class="form-control" value="{{$data->kategori_kode}}">
                    </div>
                    <div class="form-group">
                        <label for="namaKategori">Nama Kategori</label>
                        <input type="text" name="namaKategori" id="namaKategori" class="form-control" value="{{$data->kategori_nama}}">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-ptimary">Edit</button>
                </div>
            </form>
        </div>
    </div>
@endsection