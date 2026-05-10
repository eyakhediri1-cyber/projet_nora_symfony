<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * Retourne les 6 prochains événements publiés
     * @return Evenement[]
     */
    public function findUpcoming(int $limit = 6): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.statut = :statut')
            ->andWhere('e.dateDebut >= :now')
            ->setParameter('statut', 'publie')
            ->setParameter('now', new \DateTime())
            ->orderBy('e.dateDebut', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Evenement[] Returns an array of Evenement objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Evenement
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByFilters(?string $titre, ?string $categorie, ?string $ville, ?\App\Entity\TagEvenement $tag)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.lieu', 'l')
            ->addSelect('l');

        if ($titre) {
            $qb->andWhere('e.titre LIKE :titre')
                ->setParameter('titre', '%' . $titre . '%');
        }

        if ($categorie) {
            $qb->andWhere('e.categorie = :cat')
                ->setParameter('cat', $categorie);
        }

        if ($ville) {
            $qb->andWhere('l.ville LIKE :ville')
                ->setParameter('ville', '%' . $ville . '%');
        }

        if ($tag) {
            $qb->innerJoin('e.tags', 't')
                ->andWhere('t = :tag')
                ->setParameter('tag', $tag);
        }

        return $qb->orderBy('e.dateDebut', 'ASC')
            ->getQuery();
    }

}
