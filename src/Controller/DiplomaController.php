<?php

namespace App\Controller;

use App\Entity\Diploma;
use App\Form\DiplomaType;
use App\Repository\DiplomaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/diploma")
 */
class DiplomaController extends AbstractController
{
    /**
     * @Route("/", name="diploma_index", methods={"GET"})
     */
    public function index(DiplomaRepository $diplomaRepository): Response
    {
        return $this->render('diploma/index.html.twig', [
            'diplomas' => $diplomaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="diploma_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $diploma = new Diploma();
        $form = $this->createForm(DiplomaType::class, $diploma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diploma->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($diploma);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('diploma/new.html.twig', [
            'diploma' => $diploma,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="diploma_show", methods={"GET"})
     */
    public function show(Diploma $diploma): Response
    {
        return $this->render('diploma/show.html.twig', [
            'diploma' => $diploma,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="diploma_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Diploma $diploma): Response
    {
        $form = $this->createForm(DiplomaType::class, $diploma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('diploma/edit.html.twig', [
            'diploma' => $diploma,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="diploma_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Diploma $diploma): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diploma->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($diploma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin');
    }
}
