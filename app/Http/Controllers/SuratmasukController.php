<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuratmasukResource;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratmasukController extends Controller
{
    public function index(Request $request) {

        // $surat_masuk = SuratMasuk::all();

        $query = $request->input('query');

        if ($query) {
            $surat_masuk = SuratMasuk::where('nosurat', 'like', "%{$query}%")
                                    ->orWhere('perihal', 'like', "%{$query}%")
                                    ->get();
        } else {
            $surat_masuk = SuratMasuk::all();
        }
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

        // $file = null;
        // if($request->file){
        //     $fileName = $this->generateRandomString();
        //     $extension = $request->file->extension();
        //     $file = $fileName.'.'.$extension;
        //     Storage::putFileAs('surat-masuk', $request->file, $file);
        // }

        // $request['namafile'] = $file;
        // $suratMasuk = SuratMasuk::create($request->all());

        if($request->file('namafile')) {
            $validated['namafile'] = $request->file('namafile')->store('file-surat-masuk');
        }

        suratMasuk::create($validated);


        return new SuratmasukResource($validated);

        // return response()->json(['data' => $validated]);

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Surat masuk berhasil ditambahkan',
        // ]);
    }

        




    public function show($id) {
        $surat_masuk = SuratMasuk::findOrFail($id);
        return new SuratmasukResource($surat_masuk);
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
        if($request->file('namafile')){
            // Hapus gambar lama jika ada
            if($suratMasuk->namafile){
                Storage::delete('file-surat-masuk/'.$suratMasuk->namafile);
            }
    
            // Upload gambar baru
            $file = $request->file('namafile');
            $randomName = $this->generateRandomString();
            $extension = $file->extension();
            // $filename = $randomName.'.'.$extension;
            $filename = $file->storeAs('/file-surat-masuk', $randomName.'.'.$extension, ['disk' => 'public']);

            // Storage::putFileAs('file-surat-masuk', $request->file, $file);
            
            // var_dump($filename);
    
            // Simpan nama file baru ke database
            $suratMasuk->update(['namafile' => $filename]);
        }
    
        // Update data lainnya
        $suratMasuk->update($request->except('namafile'));
        
        return new SuratmasukResource($suratMasuk);
    }

    public function destroy($id){
        $suratMasuk = SuratMasuk::findOrFail($id);
        if($suratMasuk->namafile) {
            Storage::delete('surat-masuk/'.basename($suratMasuk->namafile));
        }
        $suratMasuk->delete();
    }

    public function search($key){

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
