<?php

namespace App\Command;

use App\Service\RestaurantService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteRestaurantCommand extends Command
{
    private RestaurantService $restaurantService;
    private EntityManagerInterface $entityManager;

    /**
     * @param RestaurantService $restaurantService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RestaurantService $restaurantService, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->restaurantService = $restaurantService;
        $this->entityManager = $entityManager;
    }


    protected function configure(): void
    {
        $this
            ->setName('app:delete-restaurant')
            ->setDescription('Delete a restaurant by ID')
            ->addArgument('id', InputArgument::REQUIRED, 'The ID of the restaurant to delete');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $restaurantId = $input->getArgument('id');

        $restaurant = $this->restaurantService->find($restaurantId);
        if (!$restaurant) {
            $output->writeln('<error>Restaurant not found!</error>');
            return Command::FAILURE;
        }
        $this->entityManager->remove($restaurant);
        $this->entityManager->flush();

        $output->writeln('<info>Restaurant deleted successfully!</info>');

        return Command::SUCCESS;
    }
}
