<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ){}

    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $visites = $articleRepository->findMostVisiteArticles();
        $articles = $articleRepository->findAll();
        return $this->render('article/index.html.twig', compact('articles', 'visites'));
    }

    #[Route('/{id<\d+>}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig',compact('article'));
    }

    #[Route('/app/article', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article->setAuteur($this->getUser());
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre article a été enregistré.');
            return $this->redirectToRoute('app_article_index');
        }

        return $this->render('article/new.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/app/article/edit/{id<\d+>}', name: 'app_article_edit', methods: ['GET', 'PUT'])]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre article a été mise à jour.');
            return $this->redirectToRoute('app_article_index');
        }

        return $this->render('article/new.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/app/article/vue/{id<\d+>}', name: 'app_article_visited', methods: ['GET', 'POST'])]
    public function onVisited(Article $article): Response
    {
        if($article->getVisiteurs()->contains($this->getUser())){
            $article->removeVisiteur($this->getUser());
        }else{
            $article->addVisiteur($this->getUser());
        }

        $this->entityManager->flush();
        return $this->redirectToRoute('app_article_index');
    }

    #[Route('/app/article/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
            $this->addFlash('success', "Votre article a bien été supprimé.");
        }

        return $this->redirectToRoute('app_user_show', [], Response::HTTP_SEE_OTHER);
    }
}
