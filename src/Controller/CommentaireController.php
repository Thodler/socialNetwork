<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){}

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush($commentaire);

            return $this->redirectToRoute(
                route: 'app_article_show',
                parameters: [ 'id' => $commentaire->getArticle()->getId()],
                status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'PUT'])]
    public function edit(Request $request, Commentaire $commentaire): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire, [
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush($commentaire);

            return $this->redirectToRoute(
                route:'app_article_show',
                parameters: ['id' => $commentaire->getArticle()->getId()],
                status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/edit.html.twig', compact('form'));
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['DELETE'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($commentaire);
        }

        return $this->redirectToRoute(
            route: 'app_article_show',
            parameters: ['id' => $commentaire->getArticle()->getId()],
            status: Response::HTTP_SEE_OTHER);
    }
}
