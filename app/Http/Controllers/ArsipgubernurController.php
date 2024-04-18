<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArsipgubernurResource;
use App\Models\ArsipGubernur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipgubernurController extends Controller
{
    public function index() {
        $arsip_gubernur = ArsipGubernur::all();
        // return response()->json(['data' => $arsip_gubernur]);
        return ArsipgubernurResource::collection($arsip_gubernur);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nosurat' => 'required',
            'tglsurat' => 'required|date',
            'perihal' => 'required',
            'isiringkas' => 'required|max:500',
            'namafile',
            
        ]);

        $file = null;
        if($request->file){
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $file = $fileName.'.'.$extension;
            Storage::putFileAs('arsip-gubernur', $request->file, $file);
        }

        $request['namafile'] = $file;
        $arsipGubernur = ArsipGubernur::create($request->all());


        return new ArsipgubernurResource($arsipGubernur);
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'nosurat' => 'required',
            'tglsurat' => 'required|date',
            'perihal' => 'required',
            'isiringkas' => 'required|max:500',
            'namafile',
            
        ]);
    
        $arsipGubernur = ArsipGubernur::findOrFail($id);
    
        // Jika ada file baru yang diupload
        if($request->file){
            // Hapus gambar lama jika ada
            if($arsipGubernur->namafile){
                Storage::delete('arsip-gubernur/'.$arsipGubernur->namafile);
            }
    
            // Upload gambar baru
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $file = $fileName.'.'.$extension;
            Storage::putFileAs('arsip-gubernur', $request->file, $file);
    
            // Simpan nama file baru ke database
            $arsipGubernur->update(['namafile' => $file]);
        }
    
        // Update data lainnya
        $arsipGubernur->update($request->except('file'));
        
        return new ArsipgubernurResource($arsipGubernur);
    }

    public function destroy($id){
        $arsipGubernur = ArsipGubernur::findOrFail($id);
        if($arsipGubernur->namafile) {
            Storage::delete('arsip-gubernur/'.basename($arsipGubernur->namafile));
        }
        $arsipGubernur->delete();
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
