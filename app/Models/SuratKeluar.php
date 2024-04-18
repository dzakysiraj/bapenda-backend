<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'surat_keluar';

    protected $fillable = [
        'nosurat',
        'tglsurat',
        'perihal' ,
        'isiringkas',
        'kepada',
        'namafile'   
    ];
}
