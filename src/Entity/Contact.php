<?php

// src/Entity/Contact.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 * @ORM\Table(name="contact", schema="econtact")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id_contact;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_nom;

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
