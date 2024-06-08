<?php

namespace App\Service\Pdf;

use App\Entity\Pdf;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class PdfRegister
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function registerPdf(User $user, array $pdfData): void
    {
        $registration = (new Pdf())
            ->setUser($user)
            ->setTitle($pdfData['title'])
            ->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($registration);
        $this->entityManager->flush();
    }
}
