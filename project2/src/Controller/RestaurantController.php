<?php

namespace App\Controller;

use App\Service\OrderService;
use App\Service\RestaurantService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RestaurantController extends AbstractController
{
    private RestaurantService $restaurantService;
    private Security $security;

    private UserService $userService;

    private OrderService $orderService;

    /**
     * @param RestaurantService $restaurantService
     * @param Security $security
     * @param UserService $userService
     * @param OrderService $orderService
     */
    public function __construct(RestaurantService $restaurantService, Security $security, UserService $userService, OrderService $orderService)
    {
        $this->restaurantService = $restaurantService;
        $this->security = $security;
        $this->userService = $userService;
        $this->orderService = $orderService;
    }


    #[Route('/restaurant/{id}', name: 'app_restaurant_detail', methods: ['GET'])]
    public function showRestaurantDetail($id, RestaurantService $restaurantService, Security $security): \Symfony\Component\HttpFoundation\Response
    {
        $user = $this->security->getUser();

        $restaurant = $restaurantService->find($id);

        $dishes = $restaurant->getMenuItems();
        if($user){
            $email = $user->getUserIdentifier();
            $userId = $this->userService->findByEmail($email);
            $order = $this->orderService->findByUserId($userId);
            return $this->render('home/restaurant.html.twig', [
                'restaurant' => $restaurant,
                'dishes' => $dishes,
                'order' => $order
            ]);
        } else {
            return $this->render('home/restaurant.html.twig', [
                'restaurant' => $restaurant,
                'dishes' => $dishes,
                'order' => 'no order'
            ]);
        }

    }

    #[Route('/load-restaurants', name: 'load_restaurants', methods: ['GET'])]
    public function loadRestaurants(Request $request): JsonResponse
    {
        $cuisine = $request->query->get('cuisine');


        if ($cuisine === 'Все рестораны') {
            $restaurants = $this->restaurantService->findAllRestaurants();
        } else {
            $restaurants = $this->restaurantService->findByCuisine($cuisine);
        }


        $data = [];
        foreach ($restaurants as $restaurant) {
            $data[] = [
                'name' => $restaurant->getName(),
                'description' => $restaurant->getDescription(),
                'rating' => $restaurant->getRating(),
                'imagePath' => $restaurant->getImagePath(),
                'url' => $this->generateUrl('app_restaurant_detail', ['id' => $restaurant->getId()]),
            ];
        }

        return new JsonResponse($data);
    }

}