<?php

namespace App\Controller;

use App\Entity\Crew;
use App\Entity\Drawn;
use App\Form\CrewType;
use App\Form\DeleteDrawnListType;
use App\Form\DrawType;
use App\Repository\CrewRepository;
use App\Repository\DrawnRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CrewRepository         $crewRepository,
                          DrawnRepository        $drawnRepository,
                          Request                $request,
                          EntityManagerInterface $entityManager,
                          DrawnController        $drawnController): Response
    {
        $crew = $crewRepository->findAll();
        $drawnList = $drawnRepository->findAll();

        $form = $this->createForm(DrawType::class);
        $form->handleRequest($request);

        // Draw a random member and insert in drawn list
        if ($form->isSubmitted() && $form->isValid()) {

            // Insert member ID in array
            $drawnListArray = [];
            foreach ($drawnList as $drawnMember) {
                $drawnListArray[] = $drawnMember->getCrewId();
            }

            // Compare how many entries there is in the two arrays and redirect if they are equal
            if (count($crew) === count($drawnList)) {
                return $this->redirectToRoute('home');
            }

            // Pick a random entry
            $min = $crew[0]->getId();
            $max = end($crew)->getId();
            $randomId = rand($min, $max);

            while (in_array($randomId, $drawnListArray)) {
                $randomId = rand($min, $max);
            }

            $draw = $crewRepository->findOneBy(['id' => $randomId]);

            $drawnController->new($draw, $entityManager);
        }

        // Delete drawn list
        $deleteDrawnList = $this->createForm(DeleteDrawnListType::class);
        $deleteDrawnList->handleRequest($request);

        if ($deleteDrawnList->isSubmitted() && $deleteDrawnList->isValid()) {
            $drawnRepository->dropTable();
            $this->addFlash('danger', 'The drawn list is now empty.');

            return $this->redirectToRoute('home');
        }

        // Add crew member form
        $newMember = new Crew();
        $addForm = $this->createForm(CrewType::class, $newMember);
        $addForm->handleRequest($request);

        if ($addForm->isSubmitted() && $addForm->isValid()) {
            $entityManager->persist($newMember);
            $entityManager->flush();

            $this->addFlash('success', $newMember->getName() . ' has been added to the list.');

            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', [
            'crew' => $crew ?? null,
            'draw' => $draw ?? null,
            'drawn' => $drawnList ?? null,
            'form' => $form->createView(),
            'addForm' => $addForm->createView(),
            'deleteDrawnList' => $deleteDrawnList->createView(),
            'message' => $message ?? null,
        ]);
    }
}
