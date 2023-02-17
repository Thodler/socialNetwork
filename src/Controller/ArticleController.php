<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig');
    }
}
