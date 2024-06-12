<?php

namespace App\Service\Pdf;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;

class PdfWatermarker
{

    /**
     * @throws CrossReferenceException
     * @throws PdfReaderException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws FilterException
     */
    public function generateWatermark(string $pdfPath): void
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($pdfPath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);

            $pdf->SetFont('Arial', 'I', 30);
            $pdf->SetTextColor(256, 0, 0);
            $pdf->SetXY(60, 150);
            $pdf->Write(0, 'GRATUIT');
        }

        $pdf->Output('F', $pdfPath);
    }
}
