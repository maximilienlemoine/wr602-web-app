<?php

namespace App\Service\Pdf;

use App\Entity\User;
use App\Repository\PdfRepository;

class PdfLimiter
{
    private PdfRepository $pdfRepository;

    public function __construct(PdfRepository $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    }

    public function hasReachedLimit(User $user): bool
    {
        $pdfToday = $this->pdfRepository->findCountTodayPdfByUser($user);

        if ($pdfToday >= $user->getSubscription()->getPdfLimit()) {
            return true;
        }

        return false;
    }

}