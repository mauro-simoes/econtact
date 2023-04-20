<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Form\UserLoginFormType;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{
    #[Route('/user', name: 'app_home')]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();

        $form = $this->createForm(UserLoginFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $user = $userRepository->findOneBy(array('email' => $user->getEmail(), 'num' => $user->getNum()));

            if ($user != null)
                return $this->redirectToRoute('contacts', array('idNom' => $user->getId()));
        }

        return $this->render('home/index.html.twig', [
            'form' => $form,
        ]);
    }
}
