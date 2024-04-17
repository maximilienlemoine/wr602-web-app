<?php

namespace App\Tests;

use App\Entity\Pdf;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    public function testSetterAndGetter()
    {
        $pdf = new Pdf();

        $title = 'Test';
        $createdAt = new \DateTimeImmutable();
        $user = new User();

        $pdf->setTitle($title)
            ->setCreatedAt($createdAt)
            ->setUser($user);

        $this->assertEquals($title, $pdf->getTitle());
        $this->assertEquals($createdAt, $pdf->getCreatedAt());
        $this->assertEquals($user, $pdf->getUser());
    }
}
