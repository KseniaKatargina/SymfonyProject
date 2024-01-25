<?php

namespace App\Repository;

use App\Entity\Order;
use App\Exception\DBException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger,ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
        $this->logger = $logger;
    }

    /**
     * @throws DBException
     */
    public function findByUserId(mixed $id)
    {
        try {
            return $this->createQueryBuilder('o')
                ->where('o.customer = :userId')
                ->andWhere('o.status = :status')
                ->setParameter('userId', $id)
                ->setParameter('status', 'active')
                ->getQuery()
                ->getOneOrNullResult();
        } catch (\Exception $exception) {
            $this->logger->error('Error in OrderRepository, findByUserId: ' . $exception->getMessage());
            throw new DBException('Database error during findByUserId method', 0, $exception);
        }
    }

    /**
     * @throws DBException
     */
    public function findAllUserOrders()
    {
        try {
            return $this->createQueryBuilder('o')
                ->getQuery()
                ->getResult();
        } catch (\Exception $exception) {
            $this->logger->error('Error in OrderRepository, findAllUserOrders: ' . $exception->getMessage());
            throw new DBException('Database error during findAllUserOrders method', 0, $exception);
        }
    }
}
