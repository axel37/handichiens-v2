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
     * Renvoie les familles disponibles pour accueillir un chien sur le créneau indiqué.
     *
     * Si l'un des paramètres est *null* les familles seront celles qui :
     * - Ont une/des disponibilités commençant *avant* $début
     * - Ont une/des disponibilités se terminant *après* $fin
     * @param \DateTimeImmutable|null $debut
     * @param \DateTimeImmutable|null $fin
     * @return array
     */
    public function findByDisponibilite(?\DateTimeImmutable $debut, ?\DateTimeImmutable $fin): array
    {

        // Si aucune date n'est fournie, ne rien renvoyer
        if (!isset($debut) && !isset($fin)) {
            return [];
        }

        // Sous-requête sélectionnant les disponibilités correspondant au créneau pour une famille
        // les conditions sont ajoutées ci-dessous (if isset($debut)...)
        $subQuery = $this->_em->createQueryBuilder()
            ->select('dispo')
            ->from('App:Disponibilite', 'dispo')
            ->andWhere('dispo.famille = f')
        ;

        // Requête sélectionnant les familles ayant au moins une disponibilité correspondant à $subQuery
        $queryBuilder = $this->createQueryBuilder('f');

        // Si le paramètre "début" est non-null, sélectionner les disponibilités commençant avant cette date
        if (isset($debut)) {
            $subQuery->andWhere('dispo.debut <= :debut');
            $queryBuilder->setParameter('debut', $debut);
        }
        // Si le paramètre "fin" est non-null, sélectionner les disponibilités commençant avant cette date
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
