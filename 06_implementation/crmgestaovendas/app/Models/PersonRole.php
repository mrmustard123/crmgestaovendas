<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonRole extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'person_role'; // <-- ¡Añade esta línea!

    // También es una buena práctica especificar la clave primaria si no es 'id'
    protected $primaryKey = 'person_role_id';

    // Si tu primary key no es auto-incremental (aunque la tuya sí lo es, es bueno saberlo)
    // public $incrementing = false;

    // Si tu primary key es de tipo 'tinyint' o 'string'
    // protected $keyType = 'string'; // o 'int' por defecto para tinyint

    // Si no usas los timestamps created_at y updated_at, desactívalos
    // public $timestamps = true; // Por defecto es true, así que no es necesario añadirlo si los usas

    // Campos que se pueden asignar masivamente (para create/update)
    protected $fillable = [
        'role_name',
        // 'created_at', // Eloquent los maneja automáticamente
        // 'updated_at', // Eloquent los maneja automáticamente
    ];    
    
    
}
