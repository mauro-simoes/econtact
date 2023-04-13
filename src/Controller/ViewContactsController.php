<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;

class ViewContactsController extends AbstractController
{
    #[Route('/contacts', name: 'app_view_contacts')]
    public function index(): Response
    {
        // Obtenez une instance de la connexion à la base de données
        $conn = $this->getDoctrine()->getConnection();

        // Exécutez une requête SELECT pour récupérer toutes les entrées dans la table "contacts"
        $sql = "SELECT * FROM contacts";
        $stmt = $conn->query($sql);

        // Récupérez les résultats sous forme de tableau associatif
        $results = $stmt->fetchAllAssociative();

        // Parcourez les résultats et affichez-les
        foreach ($results as $row) {
            echo $row['id'] . " : " . $row['name'] . " - " . $row['email'] . "\n";
        }

        return $this->render('view_contacts/index.html.twig', [
            'controller_name' => 'ViewContactsController',
        ]);
    }
}
