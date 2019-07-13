<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }
    
    public function getNonFollowedByUser(User $user)
    {
        /*$qb = $this->createQueryBuilder('u');
        return $qb->where($qb->expr()->not($qb->expr()->exists(
            $this->_em->createQueryBuilder()
                ->select('f')
                ->from("App:Follower", "f")
                ->where("f.follower = :follower")
        )))
            ->setParameter("follower", $user)
            ->getQuery()
            ->getResult();*/
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function search_user( string $search_info){
        return $this->createQueryBuilder("u")
            ->where("u.username like :search_info")
            ->orWhere("u.display_name like :search_info")
            ->setParameter("search_info", "%$search_info%")
            ->getQuery()
            ->getResult()
            ;
    }

    public function getCompletionInt($name)
    {
        $QB = $this->createQueryBuilder('u')
            ->select('u.username')
            ->where("u.username LIKE '".$name."%'")
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
        return($QB);
    }
}
