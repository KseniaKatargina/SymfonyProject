<?php

namespace App\Repository;

use App\Entity\Restaurant;
use App\Exception\DBException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Restaurant>
 *
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Restaurant::class);
        $this->logger = $logger;
    }

    /**
     * @throws DBException
     */
    public function findAllCuisines()
    {
        try {
            return $this->createQueryBuilder('r')
                ->select('DISTINCT r.cuisine')
                ->getQuery()
                ->getResult();
        } catch (\Exception $exception) {
            $this->logger->error('Error in RestaurantRepository, findAllCuisines: ' . $exception->getMessage());
            throw new DBException('Database error during findAllCuisines method', 0, $exception);

        }
    }

    /**
     * @throws DBException
     */
    public function findByCuisine($cuisine)
    {
        try {
            return $this->createQueryBuilder('r')
                ->andWhere('r.cuisine = :cuisine')
                ->setParameter('cuisine', $cuisine)
                ->getQuery()
                ->getResult();
        } catch (\Exception $exception) {
            $this->logger->error('Error in RestaurantRepository, findByCuisine: ' . $exception->getMessage());
            throw new DBException('Database error during findByCuisine method', 0, $exception);
        }
    }

    /**
     * @throws DBException
     */
    public function findByName($query)
    {
        try {
            return $this->createQueryBuilder('r')
                ->where('lower(r.name) LIKE lower(:name)')
                ->setParameter('name', '%' . $query . '%')
                ->getQuery()
                ->getResult();
        } catch (\Exception $exception) {
            $this->logger->error('Error in RestaurantRepository, findByName: ' . $exception->getMessage());
            throw new DBException('Database error during findByName method', 0, $exception);
        }
    }

}
