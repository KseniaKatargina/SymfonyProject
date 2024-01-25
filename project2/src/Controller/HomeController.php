<?php

namespace App\Controller;

use App\Service\RestaurantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private RestaurantService $restaurantService;

    /**
     * @param RestaurantService $restaurantService

     */
    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }


    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        $restaurants = $this->restaurantService->findAllRestaurants();

        $cuisines = $this->restaurantService->findAllCuisines();

        return $this->render('home/index.html.twig', [
            'restaurants' => $restaurants,
            'cuisines' => $cuisines
        ]);
    }
}