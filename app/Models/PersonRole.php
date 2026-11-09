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


    protected $fillable = [
        'role_name',
        // 'created_at', // Eloquent los maneja automáticamente
        // 'updated_at', // Eloquent los maneja automáticamente
    ];    
    
    
}
