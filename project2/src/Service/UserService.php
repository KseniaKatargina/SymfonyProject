<?php

namespace App\Service;

use App\Entity\Order;
use App\Exception\DBException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Psr\Log\LoggerInterface;

class UserService
{
    private UserRepository $userRepository;
    private OrderService $orderService;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    /**
     * @param UserRepository $userRepository
     * @param OrderService $orderService
     * @param EntityManager $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(UserRepository $userRepository, OrderService $orderService, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->orderService = $orderService;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function getOrCreateOrder(?\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        try {
            $activeOrder = $this->orderService->findByUserId($user->getId());

            if ($activeOrder === null) {
                $activeOrder = new Order();
                $activeOrder->setCustomer($user);
                $activeOrder->setStatus('active');
                $activeOrder->setTime(new \DateTime());
                $activeOrder->setTotalPrice(0);

                try {
                    $this->entityManager->persist($activeOrder);
                    $this->entityManager->flush();
                } catch (ORMException $e) {
                    $this->logger->error('Error in UserService, getOrCreateOrder method: ' . $e->getMessage());
                }
            }

            return $activeOrder;
        } catch (DBException $e) {
            $this->logger->error('Error in UserService, getOrCreateOrder method: ' . $e->getMessage());
            return null;
        }
    }

    public function findByEmail(string $email)
    {
        try {
            return $this->userRepository->findUserIdByEmail($email);
        } catch (DBException $e) {
            $this->logger->error('Error in UserService, findByEmail method: ' . $e->getMessage());
            return null;
        }
    }

    public function findUserByEmail(string $email)
    {
        try {
            return $this->userRepository->findByEmail($email);
        } catch (DBException $e) {
            $this->logger->error('Error in UserService, findUserByEmail method: ' . $e->getMessage());
            return null;
        }
    }

    public function getAllUsers()
    {
        try {
            return $this->userRepository->findAll();
        } catch (Exception $e) {
            $this->logger->error('Error in UserService, getAllUsers method: ' . $e->getMessage());
            return [];
        }
    }

    public function find($id)
    {
        try {
            return $this->userRepository->find($id);
        } catch (Exception $e) {
            $this->logger->error('Error in UserService, find method: ' . $e->getMessage());
            return null;
        }
    }
}