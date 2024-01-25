<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\DBException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
* @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, User::class);
        $this->logger = $logger;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @throws DBException
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        try {
            if (!$user instanceof User) {
                throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
            }

            $user->setPassword($newHashedPassword);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Error in UserRepository, upgradePassword: ' . $exception->getMessage());
            throw new DBException('Database error during password upgrade', 0, $exception);
        }
    }

    /**
     * @throws DBException
     */
    public function findUserIdByEmail(string $email)
    {
        try {
            return $this->createQueryBuilder('u')
                ->select('u.id')
                ->where('u.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (\Exception $exception) {
            $this->logger->error('Error in UserRepository, findUserIdByEmail: ' . $exception->getMessage());
            throw new DBException('Database error during findUserIdByEmail method', 0, $exception);

        }
    }

    /**
     * @throws DBException
     */
    public function findByEmail(string $email)
    {
        try {
            return $this->createQueryBuilder('u')
                ->where('u.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (\Exception $exception) {
            $this->logger->error('Error in UserRepository, findByEmail: ' . $exception->getMessage());
            throw new DBException('Database error during findByEmail method', 0, $exception);
        }
    }

}
