<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\UserRepository;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewContactsController extends AbstractController
{

    #[Route('/contacts', name: 'contacts')]
    public function index(UserRepository $userRepository, ContactRepository $contactRepository): Response
    {
        // si un utilisateur n'est pas connecté
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        $user = $userRepository->findOneBy(array('email' => $user->getUserIdentifier()));

        $contacts = $contactRepository->findBy(array('id_nom' => $user->getId()));

        return $this->render('view_contacts/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
