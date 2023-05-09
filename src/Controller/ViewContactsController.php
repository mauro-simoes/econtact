<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewContactsController extends AbstractController
{
    #[Route('/contacts/{idNom}', name: 'contacts')]
    public function index(int $idNom, ContactRepository $contactRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $contacts = $contactRepository->findBy(array('id_nom' => $idNom));
        $contact = new Contact();

        // Créer le formulaire Symfony pour la classe Contact
        $addform = $this->createForm(ContactType::class, $contact);

        // Traiter le formulaire
        $addform->handleRequest($request);
        if ($addform->isSubmitted() && $addform->isValid()) {
            $contact ->setIdNom($idNom);

            // Enregistrer le contact dans la base de données
            $entityManager->persist($contact);
            $entityManager->flush();
            return $this->redirectToRoute('contacts', ['idNom' => $idNom]);
        }
        

        return $this->render('view_contacts/index.html.twig', [
            'contacts' => $contacts,
            'addform' => $addform->createView(),
        ]);
    }

    //public function newContact(Request $request, EntityManagerInterface $entityManager, int $idNom): Response
    //{
        
    //}
}
