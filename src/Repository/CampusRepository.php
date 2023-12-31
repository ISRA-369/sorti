<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Form\Filter\AdminCampuses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Campus>
 *
 * @method Campus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campus[]    findAll()
 * @method Campus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }

    public function save(Campus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Campus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCampusesByName(AdminCampuses $campusNameFilter){

        $qb = $this->createQueryBuilder('c');

        $qb
            //Jointure avec la table Event
            ->leftJoin('c.events', 'events')
            ->addSelect('events')

            //Jointure avec la table User
            ->leftJoin('c.users', 'users')
            ->addSelect('users');

            //Filtre sur le nom de campus saisi par l'utilisateur
            if($campusNameFilter->getName()){
                $qb->andWhere('c.name LIKE :campus')
                    ->setParameter('campus', '%' . $campusNameFilter->getName() . '%');
            }

        //Renvoie une instance de Query
        $query = $qb->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return Campus[] Returns an array of Campus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Campus
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
