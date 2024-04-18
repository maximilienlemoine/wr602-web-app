<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\PdfType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pdf')]
class PdfController extends AbstractController
{

    #[Route('/', name: 'pdf_index')]
    public function index(): Response
    {
        return $this->render('pdf/index.html.twig', [
            'controller_name' => 'PdfController',
        ]);
    }

    #[Route('/create', name: 'pdf_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(PdfType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pdf = $form->getData();

            $this->addFlash('success', 'Le PDF a bien été créé.');

            return $this->redirectToRoute('pdf_index');
        }

        return $this->render('pdf/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
