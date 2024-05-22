<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\PdfType;
use App\HttpClient\PdfServiceHttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route('/pdf')]
class PdfController extends AbstractController
{
    private PdfServiceHttpClient $pdfServiceHttpClient;

    public function __construct(PdfServiceHttpClient $pdfServiceHttpClient)
    {
        $this->pdfServiceHttpClient = $pdfServiceHttpClient;
    }

    #[Route('/', name: 'pdf_index')]
    public function index(): Response
    {
        return $this->render('pdf/index.html.twig', [
            'controller_name' => 'PdfController',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/create', name: 'pdf_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(PdfType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pdf = $form->getData();
            $file = $form->get('file')->getData();

            if ($pdf['url']) {
                $response = $this->pdfServiceHttpClient->post(
                    'pdf/generate/url',
                    [
                        'body' => [
                            'url' => $pdf['url'],
                        ],
                    ]
                );
            } elseif ($file) {
                $multipartStream = new MultipartStream;
                $body = new FormDataPart([
                    'html' => fopen($file->getPathname(), 'r'),
                ]);
                $response = $this->pdfServiceHttpClient->post(
                    'pdf/generate/html',
                    [
                        'headers' => [
                            'Content-Type' => 'multipart/form-data',
                        ],
                        'body' => $body->bodyToIterable(),
                    ]
                );
            } else {
                throw new \Exception('Invalid form data');
            }

            return new Response($response, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('inline; filename="%s"', $pdf['title'].'.pdf'),
            ]);
        }

        return $this->render('pdf/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
