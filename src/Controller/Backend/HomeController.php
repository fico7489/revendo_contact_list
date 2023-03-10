<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'backend.home.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('backend/home/index.html.twig');
    }
}
