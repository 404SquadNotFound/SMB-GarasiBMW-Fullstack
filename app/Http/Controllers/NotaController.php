<?php

namespace App\Http\Controllers;

use App\Http\Services\PdfNota;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    protected PdfNota $pdfNota;

    public function __construct(PdfNota $pdfNota)
    {
        $this->pdfNota = $pdfNota;
    }

    /**
     * Halaman preview nota (HTML) — data jasa dibawa via sessionStorage di browser.
     * Route: GET /antrian-pengerjaan/{id}/nota-preview
     */
    public function previewPage(Request $request, $id)
    {
        return view('pages.antrian_pengerjaan.previewNota', ['id' => $id]);
    }

    /**
     * Download / stream PDF nota.
     * Data jasa dikirim via query string (JSON encoded) karena GET request.
     * Route: GET /antrian-pengerjaan/{id}/nota-pdf
     */
    public function downloadPdf(Request $request, $id)
    {
        $jasaInput = $request->input('jasa_list', '[]');
        $jasaList = is_string($jasaInput) ? json_decode($jasaInput, true) : $jasaInput;
        $jasaList = $jasaList ?? [];

        $metode   = $request->input('metode', '-');
        $download = $request->input('download', '0') === '1';

        return $this->pdfNota->generatePreview($id, $jasaList, $metode, 'both', $download);
    }
}
