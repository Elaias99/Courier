<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    //
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = ['rut','razon_social','nombre_operacional'];

    public function cobertura()
    {
        return $this->hasMany(Coberturas::class, 'proveedor_id');
    }

    public function repartidor()
    {
        return $this->hasMany(Repartidores::class, 'proveedor_id');
    }



}
