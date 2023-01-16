<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'users.index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}', name: 'users.show', methods: ['GET'])]
    public function show(EntityManagerInterface $em, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }
}
