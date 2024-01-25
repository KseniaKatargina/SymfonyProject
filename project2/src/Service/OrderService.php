<?php

namespace App\Service;

use App\Entity\MenuItem;
use App\Entity\Order;
use App\Exception\DBException;
use App\Repository\OrderRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class OrderService
{
    private OrderRepository $orderRepository;
    private LoggerInterface $logger;

    /**
     * @param OrderRepository $orderRepository
     * @param LoggerInterface $logger
     */
    public function __construct(OrderRepository $orderRepository, LoggerInterface $logger)
    {

        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
    }


    public function findByUserId(mixed $id)
    {
        try {
            return $this->orderRepository->findByUserId($id);
        } catch (DBException $e) {
            $this->logger->error('Error in OrderService, findByUserId method: ' . $e->getMessage());
        }
        return null;
    }

    public function getAllUserOrders()
    {
        try {
            return $this->orderRepository->findAllUserOrders();
        } catch (DBException $e) {
            $this->logger->error('Error in OrderService, getAllUserOrders method: ' . $e->getMessage());
        }
        return [];
    }

    public function find($orderId)
    {
        try {
            return $this->orderRepository->find($orderId);
        } catch (\Exception $exception) {
            $this->logger->error('Error in OrderService, find method: ' . $exception->getMessage());
            return [];
        }
    }
}