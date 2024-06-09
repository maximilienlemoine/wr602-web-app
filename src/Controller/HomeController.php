<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(SubscriptionRepository $repository): Response
    {
        $subscriptions = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }
}
