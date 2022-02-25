<?php

namespace App\Controller;

use App\Entity\Crew;
use App\Entity\Drawn;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/drawn")
 */
class DrawnController extends AbstractController
{
    public function new(Crew $draw, EntityManagerInterface $entityManager)
    {
        $drawn = new Drawn();
        $drawn->setName($draw->getName());
        $drawn->setCrewId($draw->getId());
        $entityManager->persist($drawn);
        $entityManager->flush();
    }
}
