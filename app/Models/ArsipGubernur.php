<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipGubernur extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'arsip_pergub';

    protected $fillable = [
        'nosurat',
        'tglsurat',
        'perihal' ,
        'isiringkas',
        'namafile'   
    ];
}
