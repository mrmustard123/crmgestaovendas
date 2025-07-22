<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
//      The table associated with the model.
//      @var string
//     
    protected $table = 'document';
    protected $primaryKey = 'document_id';     
}
