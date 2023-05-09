<?php

namespace App\Controller;

use App\Entity\Contact;
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
    public function index(int $idNom, ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findBy(array('id_nom' => $idNom));

        return $this->render('view_contacts/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    public function newContact(Request $request, EntityManagerInterface $entityManager, int $idNom): Response
    {
        $contact = new Contact();

        // Créer le formulaire Symfony pour la classe Contact
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set the ID of the related nom
            $contact->setIdNom($idNom);

            // Save the new contact to the database
            $entityManager->persist($contact);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page d'affichage des contacts liés à ce nom
            return $this->redirectToRoute('contacts', ['idNom' => $idNom]);
        }

        // Passer le formulaire à la vue Twig
        return $this->render('view_contacts/index.html.twig', [
            'form' => $form->createView(),
            'contacts' => $contact,
        ]);
    }
}
