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

    // Deshabilita el auto-incremento para la clave primaria, ya que es compuesta
    public $incrementing = false;

    // Si tu clave primaria compuesta no es numérica y no usas un "id" como nombre,
    // puedes omitir $primaryKey o establecerlo en null.
    // En este caso, como tus claves son numéricas, Eloquent intentará usar 'id' por defecto
    // si no lo especificas. Lo mejor es no definir $primaryKey para claves compuestas
    // o darle un valor nulo, ya que no representa una única columna.
    // protected $primaryKey = null; // No es necesario si no usas los métodos find() directamente con esta PK

    // Indica que no hay timestamps automáticos si no los gestiona Eloquent.
    // En tu caso, sí tienes created_at y updated_at, así que déjalo como está por defecto (true)
    // o explícitamente a true si no están en el nombre estándar.
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