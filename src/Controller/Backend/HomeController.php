<?php

namespace App\Controller\Backend;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'backend.home.index', methods: ['GET'])]
    public function index(EntityManagerInterface $em, ParameterBagInterface $parameters): Response
    {
        return $this->render('backend/home/index.html.twig');
    }
}
