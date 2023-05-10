<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FormInscription;
use App\Security\UserAuthenticator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function inscription(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator): Response
    {

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('contacts');
        }

        $user = new User();

        $form = $this->createForm(FormInscription::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $userRepository->save($user, true);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('inscription/inscription.html.twig', [
            'form' => $form,
        ]);
    }
}
