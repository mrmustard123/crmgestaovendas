<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18

*/
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Opportunity; // Cita: uploaded:Opportunity.php
use App\Models\Doctrine\Document; // Importa tu entidad Document
use Illuminate\Support\Facades\Storage; // Para manejar el almacenamiento de archivos
use DateTimeImmutable; // Para los timestamps inmutables

class DocumentController extends Controller
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Muestra el formulario para subir un nuevo documento.
     *
     * @param int $opportunityId El ID de la oportunidad a la que se asociará el documento.
     * @return \Illuminate\View\View
     */
    public function create(int $opportunityId)
    {
        $opportunity = $this->entityManager->getRepository(Opportunity::class)->find($opportunityId);

        if (!$opportunity) {
            abort(404, 'Oportunidad no encontrada.');
        }

        return view('salesperson.documents.create', [
            'opportunity' => $opportunity,
        ]);
    }

    /**
     * Almacena un nuevo documento y su archivo en la base de datos y el disco.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $opportunityId El ID de la oportunidad a la que se asociará el documento.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, int $opportunityId)
    {
        $opportunity = $this->entityManager->getRepository(Opportunity::class)->find($opportunityId);

        if (!$opportunity) {
            return redirect()->back()->with('error', 'Oportunidade não encontrada.');
        }

        // Validar los datos de entrada, incluyendo el archivo
        $validatedData = $request->validate([
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // Max 5MB (5120 KB)
            'description' => 'nullable|string',
        ]);

        $file = $request->file('document_file');
        $originalFileName = $file->getClientOriginalName();
        $fileMimeType = $file->getMimeType();
        $fileSize = $file->getSize(); // Tamaño en bytes

        // Guardar el archivo en el disco 'public' (storage/app/public)
        // Puedes organizar por ID de oportunidad para mayor orden: 'documents/{opportunityId}'
        // El método `putFile` genera un nombre de archivo único para evitar colisiones
        $filePath = Storage::disk('public')->putFile('documents/' . $opportunityId, $file);

        if (!$filePath) {
            return redirect()->back()->with('error', 'Erro ao carregar arquivo.');
        }

        $document = new Document();
        $document->setFileName($originalFileName); // Guardamos el nombre original del archivo
        $document->setFilePath($filePath); // Esta es la ruta relativa al disco (ej. 'documents/123/uniquename.pdf')
        $document->setMimeType($fileMimeType);
        $document->setFileSize($fileSize);
        $document->setDescription($validatedData['description']);
        $document->setOpportunity($opportunity);

        // Los campos created_at, updated_at y uploaded_at se llenarán automáticamente por los Lifecycle Callbacks en la entidad

        $this->entityManager->persist($document);
        $this->entityManager->flush();

        return redirect()->route('salesperson.myopportunities')->with('success', 'Documento carregado com sucesso.');
    }
}
