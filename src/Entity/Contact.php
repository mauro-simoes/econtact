<?php

// src/Entity/Contact.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactRepository;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'contact')]
class Contact
{
 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   
    private ?int $id_contact = null;
 
    #[ORM\Column]
    private ?int $id_nom;

    public function getIdContact(): ?int
    {
        return $this->id_contact;
    }

    public function getIdNom(): ?int
    {
        return $this->id_nom;
    }

    public function setIdNom(int $id_nom): self
    {
        $this->id_nom = $id_nom;

        return $this;
    }

    public function setIdContact(int $id_contact): self
    {
        $this->id_contact = $id_contact;

        return $this;
    }
}
