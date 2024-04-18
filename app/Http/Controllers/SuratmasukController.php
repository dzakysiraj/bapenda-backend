<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuratmasukResource;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratmasukController extends Controller
{
    public function index() {
        $surat_masuk = SuratMasuk::all();
        // return response()->json(['data' => $surat_masuk]);
        return SuratmasukResource::collection($surat_masuk);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nosurat' => 'required',
            'tglsurat' => 'required|date',
            'perihal' => 'required',
            'isiringkas' => 'required|max:500',
            'pengirim' => 'required',
            'tglterima' => 'required|date',
            'tglteruskan' => 'required|date',
            'namafile',
            
        ]);

        $file = null;
        if($request->file){
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $file = $fileName.'.'.$extension;
            Storage::putFileAs('surat-masuk', $request->file, $file);
        }

        $request['namafile'] = $file;
        $suratMasuk = SuratMasuk::create($request->all());


        return new SuratmasukResource($suratMasuk);
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'nosurat' => 'required',
            'tglsurat' => 'required|date',
            'perihal' => 'required',
            'isiringkas' => 'required|max:500',
            'pengirim' => 'required',
            'tglterima' => 'required|date',
            'tglteruskan' => 'required|date',
            'namafile',
            
        ]);
    
        $suratMasuk = SuratMasuk::findOrFail($id);
    
        // Jika ada file baru yang diupload
        if($request->file){
            // Hapus gambar lama jika ada
            if($suratMasuk->namafile){
                Storage::delete('surat-masuk/'.$suratMasuk->namafile);
            }
    
            // Upload gambar baru
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $file = $fileName.'.'.$extension;
            Storage::putFileAs('surat-masuk', $request->file, $file);
    
            // Simpan nama file baru ke database
            $suratMasuk->update(['namafile' => $file]);
        }
    
        // Update data lainnya
        $suratMasuk->update($request->except('file'));
        
        return new SuratmasukResource($suratMasuk);
    }

    public function destroy($id){
        $suratMasuk = SuratMasuk::findOrFail($id);
        if($suratMasuk->namafile) {
            Storage::delete('surat-masuk/'.basename($suratMasuk->namafile));
        }
        $suratMasuk->delete();
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
