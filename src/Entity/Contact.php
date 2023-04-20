<?php

// src/Entity/Contact.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 * @ORM\Table(name="contact", schema="econtact")
 */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'contact')]
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id_contact;

    /**
     * @ORM\Column(type="integer")
     */
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
}
