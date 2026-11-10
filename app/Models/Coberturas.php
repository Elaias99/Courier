<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coberturas extends Model
{
    //
    use HasFactory;

    protected $table = 'coberturas';

    protected $fillable = ['comuna','operador','proveedor_id'];

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class);
    }

}
