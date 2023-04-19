<?php
namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function save(Contact $contact, bool $flush = false): void
    {
        $this->getEntityManager()->persist($contact);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contact $contact, bool $flush = false): void
    {
        $this->getEntityManager()->remove($contact);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupère tous les contacts associés à un nom spécifique.
     *
     * @param int $idNom L'identifiant du nom
     *
     * @return Contact[] La liste des contacts associés au nom
     */
    public function findByNom(int $idNom): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id_nom = :idNom')
            ->setParameter('idNom', $idNom)
            ->getQuery()
            ->getResult()
        ;
    }
}
