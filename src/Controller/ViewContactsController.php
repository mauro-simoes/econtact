<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\AddContactForm;
use App\Repository\UserRepository;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ViewContactsController extends AbstractController
{
    #[Route('/contacts', name: 'contacts')]
    public function index(Request $request, ContactRepository $contactRepository, UserRepository $userRepository): Response
    {
        $error = null;
        // si un utilisateur n'est pas connecté
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $user = $userRepository->findOneBy(array('email' => $user->getUserIdentifier()));

        $idContacts = $contactRepository->findBy(array('id_nom' => $user->getId()));

        $contacts = [];
        foreach ($idContacts as $id) {
            array_push($contacts, $userRepository->findOneBy(array('id_nom' => $id->getIdContact())));
        }

        // Créer le formulaire Symfony pour la classe Contact
        $addform = $this->createForm(AddContactForm::class);

        $addform->handleRequest($request);
        if ($addform->isSubmitted() && $addform->isValid()) {
            // Récupérer les données du formulaire
            $resultatFormulaire = $addform->getData();

            $userAjouter = null;

            if ($resultatFormulaire['email'] != '') {
                $userAjouter = $userRepository->findOneBy(['email' => $resultatFormulaire['email']]);
            } else if ($resultatFormulaire['num'] != '') {
                $userAjouter = $userRepository->findOneBy(['num' => $resultatFormulaire['num']]);
            } else {
                $error = "Veuillez rentrer l'email d'un utilisateur ou son numero";
                return $this->render('view_contacts/index.html.twig', [
                    'contacts' => $contacts,
                    'addform' => $addform->createView(),
                    'error' => $error
                ]);
            }

            if (!$userAjouter) {
                // L'utilisateur n'existe pas, rediriger l'utilisateur vers la page des contacts pour cet id_nom
                $error = "L'utilisateur n'a pas été trouvé";
                return $this->render('view_contacts/index.html.twig', [
                    'contacts' => $contacts,
                    'addform' => $addform->createView(),
                    'error' => $error
                ]);
            }

            $contact = new Contact();
            $contact->setIdNom($user->getId());
            $contact->setIdContact($userAjouter->getId());
            // Ajouter le contact à la base de données
            $contactRepository->save($contact, true);
            return $this->redirectToRoute('contacts');
        }
        return $this->render('view_contacts/index.html.twig', [
            'contacts' => $contacts,
            'addform' => $addform->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/contacts/delete/{id}", name="delete_contact")
     */
    public function delete(Int $id, ContactRepository $contactRepository, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $user = $userRepository->findOneBy(array('email' => $user->getUserIdentifier()));

        $contact =  $contactRepository->findOneBy(array('id_nom' => $user->getId(), 'id_contact' => $id));

        $contactRepository->remove($contact, true);

        return $this->redirectToRoute('contacts');
    }
}
