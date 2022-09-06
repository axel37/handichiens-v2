<?php

namespace App\Repository;

use App\Entity\Disponibilite;
use App\Entity\Famille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @extends ServiceEntityRepository<Famille>
 *
 * @method Famille|null find($id, $lockMode = null, $lockVersion = null)
 * @method Famille|null findOneBy(array $criteria, array $orderBy = null)
 * @method Famille[]    findAll()
 * @method Famille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Famille::class);
    }

    public function add(Famille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Famille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Renvoie les familles disponibles sur le créneau indiqué.
     * Si l'un des paramètres est *null* les familles seront celles qui :`
     * - Ont une/des disponibilités commençant après $début
     * - Ont une/des disponibilités se terminant avant $fin
     * @param \DateTimeImmutable|null $debut
     * @param \DateTimeImmutable|null $fin
     * @return array
     */
    public function findByDisponibilite(?\DateTimeImmutable $debut, ?\DateTimeImmutable $fin): array
    {
        // TODO : Gérer si l'un des paramètres est null !

        if (!isset($debut) && !isset($fin)) {
            return [];
        }

        $subQuery = $this->_em->createQueryBuilder()
            ->select('dispo')
            ->from('App:Disponibilite', 'dispo')
            ->andWhere('dispo.famille = f')
//            ->andWhere('dispo.debut <= :debut')
//            ->andWhere('dispo.fin >= :fin')
        ;

        $queryBuilder = $this->createQueryBuilder('f')
//            ->setParameter('debut', $debut)
//            ->setParameter('fin', $fin)
        ;

        if (isset($debut)) {
            $subQuery->andWhere('dispo.debut <= :debut');
            $queryBuilder->setParameter('debut', $debut);
        }
        if (isset($fin)) {
            $subQuery->andWhere('dispo.fin >= :fin');
            $queryBuilder->setParameter('fin', $fin);
        }

        // Au moins une disponibilité doit correspondre à la condition
        $queryBuilder->andWhere($queryBuilder->expr()->exists($subQuery->getDQL()));

        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return Famille[] Returns an array of Famille objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Famille
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
