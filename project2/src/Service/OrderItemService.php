<?php

namespace App\Service;

use App\Repository\OrderItemRepository;
use Psr\Log\LoggerInterface;

class OrderItemService
{
    private OrderItemRepository $orderItemRepository;
    private LoggerInterface $logger;

    /**
     * @param OrderItemRepository $orderItemRepository
     * @param LoggerInterface $logger
     */
    public function __construct(OrderItemRepository $orderItemRepository, LoggerInterface $logger)
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->logger = $logger;
    }

    public function findOrderItemById($id)
    {
        try {
            return $this->orderItemRepository->find($id);
        } catch (\Exception $exception) {
            $this->logger->error('Error in find method: ' . $exception->getMessage());
        }
        return null;
    }

}