<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    /**
     * Download original guidance document.
     */
    public function download(Request $request, Document $document): BinaryFileResponse
    {
        Gate::authorize('download', $document);

        $path = storage_path('app/' . $document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        return response()->download($path, $document->original_name);
    }

    /**
     * Download specific document revision version.
     */
    public function downloadVersion(Request $request, DocumentVersion $version): BinaryFileResponse
    {
        $document = $version->document;
        Gate::authorize('download', $document);

        $path = storage_path('app/' . $version->file_path);

        if (!file_exists($path)) {
            abort(404, 'File versi revisi tidak ditemukan.');
        }

        $filename = pathinfo($document->original_name, PATHINFO_FILENAME) . "_v{$version->version_number}." . pathinfo($document->original_name, PATHINFO_EXTENSION);

        return response()->download($path, $filename);
    }
}
