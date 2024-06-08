<?php

namespace App\Controller;

use App\Form\PdfFileType;
use App\Form\PdfHtmlType;
use App\Form\PdfUrlType;
use App\HttpClient\PdfServiceHttpClient;
use App\Service\Pdf\PdfLimiter;
use App\Service\Pdf\PdfRegister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route('/membre/pdf')]
class PdfController extends AbstractController
{
    private PdfServiceHttpClient $pdfServiceHttpClient;
    private PdfRegister $pdfRegister;
    private PdfLimiter $pdfLimiter;
    private string $publicTempAbsoluteDirectory;

    public function __construct(
        PdfServiceHttpClient $pdfServiceHttpClient,
        PdfRegister $pdfRegister,
        PdfLimiter $pdfLimiter,
        string $publicTempAbsoluteDirectory
    ) {
        $this->pdfServiceHttpClient = $pdfServiceHttpClient;
        $this->pdfRegister = $pdfRegister;
        $this->pdfLimiter = $pdfLimiter;
        $this->publicTempAbsoluteDirectory = $publicTempAbsoluteDirectory;
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
    #[Route('/url/create', name: 'pdf_create_url')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(PdfUrlType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->pdfLimiter->hasReachedLimit($this->getUser())) {
                $this->addFlash('error', 'Vous avez atteint la limite de génération de PDF');
                return $this->redirectToRoute('pdf_index');
            }

            $pdfData = $form->getData();

            $response = $this->pdfServiceHttpClient->post(
                'pdf/generate/url',
                [
                    'body' => [
                        'url' => $pdfData['url'],
                    ],
                ]
            );

            $this->pdfRegister->registerPdf($this->getUser(), $pdfData);

            return new Response($response, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('inline; filename="%s"', $pdfData['title'] . '.pdf'),
            ]);
        }

        return $this->render('pdf/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/html/create', name: 'pdf_create_html')]
    public function createHtml(Request $request): Response
    {
        $form = $this->createForm(PdfHtmlType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->pdfLimiter->hasReachedLimit($this->getUser())) {
                $this->addFlash('error', 'Vous avez atteint la limite de génération de PDF');
                return $this->redirectToRoute('pdf_index');
            }

            $pdfData = $form->getData();

            $filename = uniqid('pdf_', true) . '.html';
            $filePath = $this->publicTempAbsoluteDirectory . '/' . $filename;
            file_put_contents($filePath, $pdfData['html']);

            $response = $this->pdfServiceHttpClient->post(
                'pdf/generate/file',
                [
                    'body' => [
                        'file' => fopen($filePath, 'r'),
                    ],
                ]
            );
            unlink($filePath); // Supprimer le fichier après utilisation

            $this->pdfRegister->registerPdf($this->getUser(), $pdfData);

            return new Response($response, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('inline; filename="%s"', $pdfData['title'] . '.pdf'),
            ]);
        }

        return $this->render('pdf/create_html.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/file/create', name: 'pdf_create_file')]
    public function createFile(Request $request): Response
    {
        $form = $this->createForm(PdfFileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->pdfLimiter->hasReachedLimit($this->getUser())) {
                $this->addFlash('error', 'Vous avez atteint la limite de génération de PDF');
                return $this->redirectToRoute('pdf_index');
            }

            $pdfData = $form->getData();
            $file = $form->get('file')->getData();

            if (!is_dir($this->publicTempAbsoluteDirectory)) {
                mkdir($this->publicTempAbsoluteDirectory, 0777, true);
            }

            $file->move($this->publicTempAbsoluteDirectory, $file->getClientOriginalName());
            $filePath = $this->publicTempAbsoluteDirectory.'/'.$file->getClientOriginalName();

            chmod($filePath, 0777);
            $response = $this->pdfServiceHttpClient->post(
                'pdf/generate/file',
                [
                    'body' => [
                        'file' => fopen($filePath, 'r'),
                    ],
                ]
            );
            unlink($filePath); // Supprimer le fichier après utilisation

            $this->pdfRegister->registerPdf($this->getUser(), $pdfData);

            return new Response($response, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('inline; filename="%s"', $pdfData['title'] . '.pdf'),
            ]);
        }

        return $this->render('pdf/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
