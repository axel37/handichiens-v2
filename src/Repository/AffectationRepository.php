<?php

namespace App\Repository;

use App\Entity\Affectation;
use App\Entity\Disponibilite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Affectation>
 *
 * @method Affectation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Affectation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Affectation[]    findAll()
 * @method Affectation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffectationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectation::class);
    }

    public function add(Affectation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Affectation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Renvoie les affectations correspondant à une disponibilité
     *
     * Étant donné qu'il n'existe aucune relation entre une disponibilité et une affectation,
     * la correspondance est établie de la manière suivante :
     *
     * - L'affectation et la disponibilité sont liées à la même famille
     * - Les dates de l'affectation sont comprises dans les limites de la disponibilité
     *
     *
     * @param Disponibilite $disponibilite
     * @return array
     */
    public function findByDisponibilite(Disponibilite $disponibilite): array
    {
        return $this->createQueryBuilder('affectation')
            ->andWhere('affectation.famille = :disp_famille')
            ->andWhere('affectation.debut >= :disp_debut')
            ->andWhere('affectation.fin <= :disp_fin')
            ->setParameter('disp_famille', $disponibilite->getFamille())
            ->setParameter('disp_debut', $disponibilite->getDebut())
            ->setParameter('disp_fin', $disponibilite->getFin())

            ->getQuery()
            ->getResult()
        ;

    }

//    /**
//     * @return Affectation[] Returns an array of Affectation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Affectation
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
