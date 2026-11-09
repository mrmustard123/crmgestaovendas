<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdServOpp extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prod_serv_opp';


    public $incrementing = false;

    public $timestamps = true;


    /**
     * Define las relaciones si las necesitas
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function opportunity()
    {
        return $this->belongsTo(\App\Models\Opportunity::class, 'opportunity_id', 'opportunity_id');
    }

    /**
     * Define las relaciones si las necesitas
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productService()
    {
        return $this->belongsTo(\App\Models\ProductService::class, 'product_service_id', 'product_service_id');
    }

    // Opcional: Sobreescribir el método setKeysForSaveQuery para que Eloquent sepa cómo encontrar el registro
    // al actualizar o eliminar si los valores de la PK son modificables.
    // Para tablas pivot puras sin auto-incremento, no suele ser estrictamente necesario,
    // pero es una buena práctica si los atributos de la PK pueden cambiar o si necesitas métodos como update/delete.
    protected function setKeysForSaveQuery($query)
    {
        $query->where('opportunity_id', $this->getAttribute('opportunity_id'))
              ->where('product_service_id', $this->getAttribute('product_service_id'));

        return $query;
    }
}