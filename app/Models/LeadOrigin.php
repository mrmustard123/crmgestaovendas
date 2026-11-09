<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadOrigin extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lead_origin'; // <-- ¡Añade esta línea!
    protected $primaryKey = 'lead_origin_id';
    protected $fillable = [
        'origin',
    ];      
}
