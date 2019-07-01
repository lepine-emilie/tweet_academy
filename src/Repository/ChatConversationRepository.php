<?php

namespace App\Repository;

use App\Entity\ChatConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChatConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatConversation[]    findAll()
 * @method ChatConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatConversationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChatConversation::class);
    }

    // /**
    //  * @return ChatConversation[] Returns an array of ChatConversation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChatConversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
