<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewContactsController extends AbstractController
{
    #[Route('/contacts/{idNom}', name: 'contacts')]
    public function index(int $idNom, ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findBy(array('id_nom' => $idNom));

        return $this->render('view_contacts/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
