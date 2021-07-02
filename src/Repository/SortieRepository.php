<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    /**
     * filtre les sorties
     * @param $siteRecherche
     * @param $organisateur
     * @param $inscrit
     * @param $pasInscrit
     * @param $passee
     * @param $utilisateurID
     * @param $search
     * @param $date1
     * @param $date2
     * @return int|mixed|string
     */
    public function sortieFiltre(
       $siteRecherche,
        $organisateur,
        $inscrit,
        $pasInscrit,
        $passee,
        $user,
        $search,
        $date1,
        $date2)
    {
        // création du query builder et jointure des tables participant et etat
            $qb = $this->createQueryBuilder('s');
            $qb->leftJoin('s.participants', 'p');
            $qb->leftJoin('s.etat', 'e');

            //si on a un filtre de site
            if ($siteRecherche) {
                $qb->Join('s.site', 'ss')
                    ->addSelect('ss')
                    ->andWhere('ss.nom = :site')
                    ->setParameter(':site', $siteRecherche);
            };
        //si on a un filtre de search
            if ($search) {
                $qb->andWhere('s.nom LIKE :search')
                    ->setParameter(':search', "%$search%");
            };
        //si on a un filtre de date min
            if ($date1) {
                $qb->andWhere('s.dateHeureDebut > :date1')
                    ->setParameter(':date1', $date1);
            };
        //si on a un filtre de date max
            if ($date2) {
                $qb->andWhere('s.dateHeureDebut < :date2')
                    ->setParameter(':date2', $date2);
            };
        //si on a un filtre d'organisateur'
            if ($organisateur) {
                $qb->andWhere('s.organisateur = :organisateur')
                    ->setParameter(':organisateur', $user);
            };
        //si on a un filtre d'inscription
            if ($inscrit) {
                $qb->andWhere(':participant MEMBER OF s.participants')
                    ->setParameter(':participant', $user);
            };

        //si on a un filtre de non inscription
            if ($pasInscrit) {
                $qb->andWhere(':idNotParticipant NOT MEMBER OF s.participants')
                   // ->andWhere('s.organisateur <> :idNotParticipant')
                   // ->orWhere('p IS NULL')
                    ->setParameter(':idNotParticipant', $user);
            };
        //si on a un filtre d'état passé
            if ($passee) {
                $qb->andWhere('e.libelle = :etat')
                    ->setParameter(':etat', "Passée");
            };
            $qb->orderBy('s.etat', 'ASC');
            $qb->AddOrderBy('s.dateHeureDebut', 'ASC');

               $query=$qb->getQuery();

            $result=$query->getResult();


        return $result;
    }





    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
