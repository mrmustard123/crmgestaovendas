<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunityStatus extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opportunity_status'; // <-- ¡Añade esta línea!
    protected $primaryKey = 'opportunity_status_id';
    protected $fillable = [
        'status',
    ];        
    
    
}
