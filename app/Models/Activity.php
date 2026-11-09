<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity';
    protected $primaryKey = 'activity_id';  
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'description',
        'activity_date',
        
    ];    
    
/*   
//      The table associated with the model.
//      @var string
//     
    protected $table = 'ejemplo';
    protected $primaryKey = 'ejemplo';  
//
//      The attributes that are mass assignable.
//      @var array<int, string>

    protected $fillable = [
        'ejemplo',
        'ejemplo',
        'ejemplo',
        'ejemplo',
        'ejemplo',
        'ejemplo',
         'ejemplo',
        'ejemplo',
        'ejemplo',
        'ejemplo',
        'ejemplo',
        'ejemplo',         
    ];      
*/
    
    
}
