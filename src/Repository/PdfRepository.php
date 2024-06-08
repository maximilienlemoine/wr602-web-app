<?php

namespace App\Repository;

use App\Entity\Pdf;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pdf>
 *
 * @method Pdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pdf[]    findAll()
 * @method Pdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PdfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pdf::class);
    }

    /**
     * Récupère les PDFs d'un utilisateur dans la journée
     */
    public function findCountTodayPdfByUser(User $user): int
    {
        $today = new \DateTimeImmutable();
        $startOfDay = $today->setTime(0, 0);
        $endOfDay = $today->setTime(23, 59);

        return $this->createQueryBuilder('pdf')
            ->select('COUNT(pdf)')
            ->where('pdf.user = :user')
            ->andWhere('pdf.createdAt BETWEEN :startOfDay AND :endOfDay')
            ->setParameter('user', $user)
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('pdf')
            ->where('pdf.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
