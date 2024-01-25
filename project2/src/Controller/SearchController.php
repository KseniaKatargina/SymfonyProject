<?php

namespace App\Controller;
use App\Service\RestaurantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private RestaurantService $restaurantService;

    /**
     * @param RestaurantService $restaurantService
     */
    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }


    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $query = $request->query->get('query');
        $restaurants = $this->restaurantService->findRestaurantsByName($query);

        $cuisines = $this->restaurantService->findAllCuisines();

        return $this->render('home/index.html.twig', [
            'restaurants' => $restaurants,
            'cuisines' => $cuisines
        ]);
    }


}