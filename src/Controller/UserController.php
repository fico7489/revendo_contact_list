<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ContactForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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

    #[Route('/users/{id}', requirements: ['id' => "\d+"], name: 'users.show', methods: ['GET'])]
    public function show(EntityManagerInterface $em, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/users/create', name: 'users.create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(ContactForm::class, $user, ['method' => 'POST']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($user instanceof User) {
                $user->setFavorite(false);
                $em->persist($user);
                $em->flush();
            }

            $this->addFlash('success', 'Contact successfully created!');

            return $this->redirectToRoute('users.index');
        }

        return $this->render('users/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/users/{id}/edit', name: 'users.edit', methods: ['GET', 'PATCH'])]
    public function edit(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        $form = $this->createForm(ContactForm::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($user instanceof User) {
                $em->persist($user);
                $em->flush();
            }

            $this->addFlash('success', 'Contact successfully updated!');

            return $this->redirectToRoute('users.index');
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/users/{id}', name: 'users.delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }

        $this->addFlash('success', 'Contact successfully deleted!');

        return $this->redirectToRoute('users.index');
    }
}
