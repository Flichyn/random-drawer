<?php

namespace App\Controller;

use App\Entity\Crew;
use App\Form\CrewType;
use App\Repository\CrewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/crew")
 */
class CrewController extends AbstractController
{
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $crew = new Crew();
        $form = $this->createForm(CrewType::class, $crew);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($crew);
            $entityManager->flush();

            $this->addFlash('success', $crew->getName() . ' has been added to the list.');

            return $this->redirectToRoute('crew_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crew/new.html.twig', [
            'crew' => $crew,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crew_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Crew $crew, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CrewType::class, $crew);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', $crew->getName() . ' has been updated.');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crew/edit.html.twig', [
            'crew' => $crew,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crew_delete", methods={"POST"})
     */
    public function delete(Request $request, Crew $crew, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$crew->getId(), $request->request->get('_token'))) {
            $entityManager->remove($crew);
            $entityManager->flush();

            $this->addFlash('danger', 'Le membre "' . $crew->getName() . '" a bien été supprimé.');
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
