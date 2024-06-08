<?php

namespace App\Command;

use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-subscription',
    description: 'Creation des abonnements pour l\'application',
)]
class CreateSubscriptionCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Création des abonnements');

        $subscriptions = [
            [
                'title' => 'Gratuit',
                'price' => 0,
                'pdf_limit' => 2,
                'description' => 'Abonnement gratuit pour utiliser l\'application',
                'media' => 'media/abonnement-gratuit.png',
            ],
            [
                'title' => 'Simple',
                'price' => 10,
                'pdf_limit' => 10,
                'description' => 'Abonnement simple pour utiliser l\'application',
                'media' => 'media/abonnement-simple.png',
            ],
            [
                'title' => 'Premium',
                'price' => 30,
                'pdf_limit' => 100,
                'description' => 'Abonnement premium pour utiliser l\'application',
                'media' => 'media/abonnement-premium.png',
            ],
        ];

        foreach ($subscriptions as $subscription) {
            $io->section($subscription['title']);
            $subs = (new Subscription())
                ->setTitle($subscription['title'])
                ->setPrice($subscription['price'])
                ->setPdfLimit($subscription['pdf_limit'])
                ->setDescription($subscription['description'])
                ->setMedia($subscription['media'] ?? null);
            ;
            $this->entityManager->persist($subs);
        }
        $this->entityManager->flush();

        $io->success('Abonnements créés avec succès');

        return Command::SUCCESS;
    }
}
