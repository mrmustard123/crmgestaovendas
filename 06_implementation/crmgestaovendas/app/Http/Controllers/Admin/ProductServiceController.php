<?php

/*
File: create
Author: Leonardo G. Tellez Saucedo
Created on: 4 jul. de 2025 10:36:32
Email: leonardo616@gmail.com
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\ProductService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ProductServiceController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Muestra el formulario para crear un nuevo producto/servicio.
     */
    public function create()
    {
        return view('admin.products_services.create');
    }

    /**
     * Almacena un nuevo producto/servicio en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            // --- INICIO DE MANEJO DE FORMATO DE NÚMEROS BRASILEÑO ---
            // Los campos 'price' y 'tax_rate' vienen como string con coma decimal.
            // Los transformamos a float con punto decimal para PHP y la DB.
            if ($request->has('price') && $request->price !== null && $request->price !== '') {
                $request->merge([
                    'price' => (float) str_replace(',', '.', str_replace('.', '', $request->price))
                ]);
            }

            if ($request->has('tax_rate') && $request->tax_rate !== null && $request->tax_rate !== '') {
                $request->merge([
                    'tax_rate' => (float) str_replace(',', '.', str_replace('.', '', $request->tax_rate))
                ]);
            }
            // --- FIN DE MANEJO DE FORMATO DE NÚMEROS BRASILEÑO ---

            // 1. Validar los datos del formulario
            $request->validate([
                'name'        => ['required', 'string', 'max:255', 'unique:' . ProductService::class . ',name'],
                'description' => ['nullable', 'string'],
                'type'        => ['nullable', 'in:product,service'],
                'category'    => ['nullable', 'string', 'max:100'],
                'price'       => ['nullable', 'numeric', 'min:0'], // Ahora price ya es un float con punto
                'unit'        => ['nullable', 'string', 'max:50'],
                'tax_rate'    => ['nullable', 'numeric', 'min:0', 'max:100'], // Ahora tax_rate ya es un float con punto
                'sku'         => ['nullable', 'string', 'max:12', 'unique:' . ProductService::class . ',sku'],
                'is_active'   => ['nullable', 'boolean'],
                'is_tangible' => ['nullable', 'boolean'],
            ], [
                'name.required'    => 'O nome do produto/serviço é obrigatório.',
                'name.unique'      => 'Já existe um produto/serviço com este nome.',
                'type.in'          => 'O tipo deve ser "Produto" ou "Serviço".',
                'price.numeric'    => 'O preço deve ser um valor numérico.',
                'price.min'        => 'O preço não pode ser negativo.',
                'tax_rate.numeric' => 'A taxa de imposto deve ser um valor numérico.',
                'tax_rate.min'     => 'A taxa de imposto não pode ser negativa.',
                'tax_rate.max'     => 'A taxa de imposto não pode ser superior a 100.',
                'sku.unique'       => 'Este SKU já está em uso.'
            ]);

            // 2. Crear una nueva instancia de la entidad ProductService
            $productService = new ProductService();
            $productService->setName($request->name);
            $productService->setDescription($request->description);
            $productService->setType($request->type);
            $productService->setCategory($request->category);
            $productService->setPrice($request->price); // Ya es float gracias a la transformación
            $productService->setUnit($request->unit);
            $productService->setTaxRate($request->tax_rate); // Ya es float gracias a la transformación
            $productService->setSku($request->sku);

            // Los checkboxes envían '1' si están marcados, o no existen en el request si no lo están.
            // Convertimos esto a booleano.
            $productService->setIsActive($request->has('is_active'));
            $productService->setIsTangible($request->has('is_tangible'));

            // 3. Persistir el objeto con Doctrine
            $this->entityManager->persist($productService);
            $this->entityManager->flush();

            // 4. Redirigir con un mensaje de éxito
            return redirect()->route('admin.products-services.create')->with('success', 'Produto/Serviço registrado com sucesso!');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao registrar produto/serviço: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao registrar o produto/serviço. Por favor, tente novamente.')->withInput();
        }
    }
}