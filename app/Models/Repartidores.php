<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repartidores extends Model
{
    //
    use HasFactory;

    protected $table = 'repartidores';

    protected $fillable = ['operador','nombre','proveedor_id'];

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class);
    }

}
