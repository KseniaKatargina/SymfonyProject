<?php

namespace App\Service;

use App\Exception\DBException;
use App\Repository\MenuItemRepository;
use Psr\Log\LoggerInterface;

class MenuItemService
{
    private MenuItemRepository $itemRepository;
    private LoggerInterface $logger;

    /**
     * @param MenuItemRepository $itemRepository
     * @param LoggerInterface $logger
     */
    public function __construct(MenuItemRepository $itemRepository, LoggerInterface $logger)
    {
        $this->itemRepository = $itemRepository;
        $this->logger = $logger;
    }

    public function find($dishId)
    {
        try {
            return $this->itemRepository->find($dishId);
        } catch (\Exception $exception) {
            $this->logger->error('Error in MenuItemService, find method: ' . $exception->getMessage());
            return null;
        }
    }

    public function getAll()
    {
        try {
            return $this->itemRepository->findAll();
        } catch (\Exception $exception) {
            $this->logger->error('Error in MenuItemService, getAll method: ' . $exception->getMessage());
            return [];
        }
    }

}