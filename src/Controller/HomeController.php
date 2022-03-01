<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(TrickRepository $trickRepository, CategoryRepository $categoryRepository): Response
    {
        $tricks = $trickRepository->findAll();
        $categories = $categoryRepository->findAll();
        $tricks_categories = $trickRepository->findBy(array('category' => $categories), array()
        );
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
            'categories' => $tricks_categories
        ]);
    }
}
