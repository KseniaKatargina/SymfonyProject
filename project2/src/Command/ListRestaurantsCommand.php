<?php

namespace App\Command;

use App\Service\RestaurantService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListRestaurantsCommand extends Command
{
    private RestaurantService $restaurantService;

    /**
     * @param RestaurantService $restaurantService
     */
    public function __construct(RestaurantService $restaurantService)
    {
        parent::__construct();
        $this->restaurantService = $restaurantService;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:list-restaurants')
            ->setDescription('List all restaurants with basic information');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $restaurants = $this->restaurantService->findAllRestaurants();

        $output->writeln('<info>List of Restaurants:</info>');

        foreach ($restaurants as $restaurant) {
            $output->writeln([
                sprintf('<comment>ID:</comment> %d', $restaurant->getId()),
                sprintf('<comment>Name:</comment> %s', $restaurant->getName()),
                sprintf('<comment>Description:</comment> %s', $restaurant->getDescription()),
                sprintf('<comment>Address:</comment> %s', $restaurant->getAddress()),
                sprintf('<comment>Rating:</comment> %d', $restaurant->getRating()),
                sprintf('<comment>Cuisine:</comment> %s', $restaurant->getCuisine()),
                '-----------------------------------',
            ]);
        }

        return Command::SUCCESS;
    }

}