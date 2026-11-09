<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersGroup extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_group'; // <-- ¡Añade esta línea!
    protected $primaryKey = 'users_group_id';
    protected $fillable = [
        'user_group_id',
        'group_name',
    ];     
    
}
