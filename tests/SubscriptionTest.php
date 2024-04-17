<?php

namespace App\Tests;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{

    public function testSetterAndGetter()
    {
        $subscription = new Subscription();

        $title = 'Test';
        $price = 10.0;
        $description = 'Test description';
        $pdfLimit = 10;
        $media = 'localhost:8000/media/1';
        $user = new User();
        $users = new ArrayCollection([$user]);

        $subscription->setTitle($title)
            ->setPrice($price)
            ->addUser($user)
            ->setDescription($description)
            ->setPdfLimit($pdfLimit)
            ->setMedia($media);

        $this->assertEquals($title, $subscription->getTitle());
        $this->assertEquals($price, $subscription->getPrice());
        $this->assertEquals($users, $subscription->getUsers());
        $this->assertEquals($description, $subscription->getDescription());
        $this->assertEquals($pdfLimit, $subscription->getPdfLimit());
        $this->assertEquals($media, $subscription->getMedia());
    }
}
