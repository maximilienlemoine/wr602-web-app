<?php

namespace App\Tests;

use App\Entity\Pdf;
use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends TestCase
{
    private UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
    }

    public function testSetterAndGetter()
    {
        $user = new User();

        $email = 'test@test.com';
        $lastname = 'Doe';
        $firstname = 'John';
        $createdAt = new \DateTimeImmutable();
        $password = $this->passwordHasher->hashPassword($user, 'password');
        $subscriptionEndAt = new \DateTimeImmutable();
        $subscription = new Subscription();
        $pdf = new Pdf();
        $pdfs = new ArrayCollection([$pdf]);

        $user->setEmail($email)
            ->setLastname($lastname)
            ->setFirstname($firstname)
            ->setCreatedAt($createdAt)
            ->setPassword($password)
            ->setSubscriptionEndAt($subscriptionEndAt)
            ->setSubscription($subscription)
            ->addPdf($pdf);

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($lastname, $user->getLastname());
        $this->assertEquals($firstname, $user->getFirstname());
        $this->assertEquals($createdAt, $user->getCreatedAt());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($subscriptionEndAt, $user->getSubscriptionEndAt());
        $this->assertEquals($subscription, $user->getSubscription());
        $this->assertEquals($pdfs, $user->getPdf());
    }
}
