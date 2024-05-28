<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArsipgubernurResource;
use App\Models\ArsipGubernur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipgubernurController extends Controller
{
    public function index(Request $request) {
        $query = $request->input('query');

        if($query) {
            $arsip_gubernur = ArsipGubernur::where('nosurat', 'like', "%{$query}%")
                                    ->orWhere('perihal', 'like', "%{$query}%")
                                    ->get();
        } else {
            $arsip_gubernur = ArsipGubernur::all();
        }
        
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

        // $file = null;
        // if($request->file){
        //     $fileName = $this->generateRandomString();
        //     $extension = $request->file->extension();
        //     $file = $fileName.'.'.$extension;
        //     Storage::putFileAs('arsip-gubernur', $request->file, $file);
        // }

        // $request['namafile'] = $file;
        // $arsipGubernur = ArsipGubernur::create($request->all());

        if($request->file('namafile')) {
            $validated['namafile'] = $request->file('namafile')->store('file-arsip-pergub');
        }

        ArsipGubernur::create($validated);

        return new ArsipgubernurResource($validated);
    }

    public function edit($id) {
        $arsip_pergub = ArsipGubernur::findOrFail($id);
        return new ArsipgubernurResource(($arsip_pergub));
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
        if($request->file('namafile')){
            // Hapus gambar lama jika ada
            if($arsipGubernur->namafile){
                Storage::delete('arsip-gubernur/'.$arsipGubernur->namafile);
            }
    
            // Upload gambar baru
            // $fileName = $this->generateRandomString();
            // $extension = $request->file->extension();
            // $file = $fileName.'.'.$extension;
            // Storage::putFileAs('arsip-gubernur', $request->file, $file);

            $file = $request->file('namafile');
            $randomName = $this->generateRandomString();
            $extension = $file->extension();

            $filename = $file->storeAs('/file-arsip=pergub', $randomName.'.'.$extension, ['disk' => 'public']);
    
            // Simpan nama file baru ke database
            $arsipGubernur->update(['namafile' => $filename]);
        }
    
        // Update data lainnya
        $arsipGubernur->update($request->except('namafile'));
        
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
