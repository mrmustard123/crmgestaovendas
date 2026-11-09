<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company';
    protected $primaryKey = 'company_id'; 
//
//      The attributes that are mass assignable.
//      @var array<int, string>

    protected $fillable = [
        'company_id',
        'company_id',
        'fantasy_name',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'cep',
        'address',
        'complement',
        'neighborhood',
        'city',
        'state',         
        'main_phone',
        'main_email',   
        'website',
        'website',         
        'status',
        'comments',                 
    ];     
    
    
}
