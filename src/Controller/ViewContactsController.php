<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Repository\UserRepository;
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
        // si un utilisateur n'est pas connecté
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        $user = $userRepository->findOneBy(array('email' => $user->getUserIdentifier()));

        $contacts = $contactRepository->findBy(array('id_nom' => $user->getId()));
        $contact = new Contact();

        // Créer le formulaire Symfony pour la classe Contact
        $addform = $this->createForm(ContactType::class, $contact);

        if ($addform->isSubmitted() && $addform->isValid()) {
            // Récupérer les données du formulaire
            $contact = $addform->getData();

            // Rechercher l'utilisateur avec l'id_nom correspondant à l'id_contact saisi dans le formulaire
            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['id_nom' => $contact->getIdContact()]);

            if (!$user) {
                // L'utilisateur n'existe pas, rediriger l'utilisateur vers la page des contacts pour cet id_nom
                return $this->redirectToRoute('contacts', ['idNom' => $idNom]);
            }

            // Ajouter le contact à la base de données
            $contact->setIdNom($idNom);
            $entityManager->persist($contact);
            $entityManager->flush();
        }
        return $this->render('view_contacts/index.html.twig', [
            'contacts' => $contacts,
            'addform' => $addform->createView(),
        ]);
    }
    /**
     * @Route("/contacts/delete/{id}", name="delete_contact", methods={"DELETE"})
     */
    public function delete(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($contact);
        $entityManager->flush();

        return $this->redirectToRoute('contacts', ['idNom' => $contact->getIdNom()]);
    }
}
