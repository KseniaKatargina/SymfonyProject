<?php

namespace App\Service;

use App\Exception\DBException;
use App\Repository\RestaurantRepository;
use Psr\Log\LoggerInterface;

class RestaurantService
{
    private RestaurantRepository $restaurantRepository;
    private LoggerInterface $logger;

    /**
     * @param RestaurantRepository $restaurantRepository
     */
    public function __construct(RestaurantRepository $restaurantRepository, LoggerInterface $logger)
    {
        $this->restaurantRepository = $restaurantRepository;
        $this->logger = $logger;
    }


    public function findAllRestaurants()
    {
        try {
            return $this->restaurantRepository->findAll();
        } catch (\Exception $exception) {
            $this->logger->error('Error in RestaurantService, findAllRestaurants method: ' . $exception->getMessage());
            return [];
        }
    }

    public function find($id)
    {
        try {
            return $this->restaurantRepository->find($id);
        } catch (\Exception $exception) {
            $this->logger->error('Error in RestaurantService, find method: ' . $exception->getMessage());
            return null;
        }

    }

    public function findAllCuisines()
    {
        try {
            return $this->restaurantRepository->findAllCuisines();
        } catch (DBException $e) {
            $this->logger->error('Error in RestaurantService, findAllCuisines method: ' . $e->getMessage());
        }
        return [];
    }

    public function findByCuisine( $cuisine)
    {
        try {
            return $this->restaurantRepository->findByCuisine($cuisine);
        } catch (DBException $e) {
            $this->logger->error('Error in RestaurantService, findByCuisine method: ' . $e->getMessage());
        }
        return [];
    }

    public function findRestaurantsByName($query)
    {
        try {
            return $this->restaurantRepository->findByName($query);
        } catch (DBException $e) {
            $this->logger->error('Error in RestaurantService, findRestaurantsByName method: ' . $e->getMessage());
        }
        return [];
    }
}