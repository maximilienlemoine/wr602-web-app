<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PdfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HistoryController extends AbstractController
{
    private PdfRepository $pdfRepository;

    public function __construct(PdfRepository $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    }

    #[Route('/history', name: 'history')]
    public function index(): Response
    {
        $pdfs = $this->pdfRepository->findByUser($this->getUser());

        return $this->render('history/index.html.twig', [
            'pdfs' => $pdfs,
        ]);
    }
}
