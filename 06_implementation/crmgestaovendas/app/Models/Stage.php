<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stage';
    protected $primaryKey = 'stage_id'; 
    protected $fillable = [
        'stage_id',
        'stage_name',
        'description',
        'stage_order',
        'active',
        'color_hex',
    ];     
    
}
