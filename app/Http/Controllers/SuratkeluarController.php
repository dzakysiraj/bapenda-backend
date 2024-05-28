<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuratkeluarResource;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratkeluarController extends Controller
{
    public function index(Request $request) { //read
        $query = $request->input('query');
        
        if($query) {
            $surat_keluar = SuratKeluar::where('nosurat', 'like', "%{$query}%")
                                    ->orWhere('perihal', 'like', "%{$query}%")
                                    ->get();
        } else {
            $surat_keluar = SuratKeluar::all();
        }
        
        // return response()->json(['data' => $surat_keluar]);
        return SuratkeluarResource::collection($surat_keluar);
    }

    public function store(Request $request) { //create
        $validated = $request->validate([
            'nosurat' => 'required',
            'tglsurat' => 'required|date',
            'perihal' => 'required',
            'isiringkas' => 'required|max:500',
            'kepada' => 'required',
            'namafile',
            
        ]);

        

        // $file = null;
        // if($request->file){
        //     $fileName = $this->generateRandomString();
        //     $extension = $request->file->extension();
        //     $file = $fileName.'.'.$extension;
        //     Storage::putFileAs('surat-keluar', $request->file, $file);
        // }

        // $request['namafile'] = $file;
        // $suratKeluar = SuratKeluar::create($request->all());
        
        if($request->file('namafile')) {
            $validated['namafile'] = $request->file('namafile')->store('file-surat-keluar');
        }

        suratKeluar::create($validated);

        return new SuratkeluarResource($validated);
    }

    public function edit($id) {
        $surat_keluar = SuratKeluar::findOrFail($id);
        return new SuratkeluarResource($surat_keluar);
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'nosurat' => 'required',
            'tglsurat' => 'required|date',
            'perihal' => 'required',
            'isiringkas' => 'required|max:500',
            'kepada' => 'required',
            'namafile',
            
        ]);
    
        $suratKeluar = SuratKeluar::findOrFail($id);
    
        // Jika ada file baru yang diupload
        if($request->file('namafile')){
            // Hapus gambar lama jika ada
            if($suratKeluar->namafile){
                Storage::delete('file-surat-keluar/'.$suratKeluar->namafile);
            }
    
            // Upload gambar baru
            // $fileName = $this->generateRandomString();
            // $extension = $request->file->extension();
            // $file = $fileName.'.'.$extension;
            // Storage::putFileAs('surat-keluar', $request->file, $file);
            $file = $request->file('namafile');
            $randomName = $this->generateRandomString();
            $extension = $file->extension();

            $fileName = $file->storeAs('/file-arsippergub', $randomName.'.'.$extension, ['disk' => 'public']);
    
            // Simpan nama file baru ke database
            $suratKeluar->update(['namafile' => $fileName]);
        }
    
        // Update data lainnya
        $suratKeluar->update($request->except('namafile'));
        
        return new SuratkeluarResource($suratKeluar);
    }

    public function destroy($id){
        $suratKeluar = SuratKeluar::findOrFail($id);
        if($suratKeluar->namafile) {
            Storage::delete('surat-keluar/'.basename($suratKeluar->namafile));
        }
        $suratKeluar->delete();
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
