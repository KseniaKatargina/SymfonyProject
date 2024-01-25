<?php

namespace App\Controller;

use App\Entity\MenuItem;
use App\Entity\Restaurant;
use App\Form\EditUserType;
use App\Form\MenuItemType;
use App\Form\RestaurantType;
use App\Service\MenuItemService;
use App\Service\RestaurantService;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    private UserService $userService;
    private RestaurantService $restaurantService;

    private MenuItemService $menuItemService;

    /**
     * @param UserService $userService
     * @param RestaurantService $restaurantService
     * @param MenuItemService $menuItemService
     */
    public function __construct(UserService $userService, RestaurantService $restaurantService, MenuItemService $menuItemService)
    {
        $this->userService = $userService;
        $this->restaurantService = $restaurantService;
        $this->menuItemService = $menuItemService;
    }


    #[Route('/admin', name: 'app_admin', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function index(): Response
    {
        $users = $this->userService->getAllUsers();
        return $this->render('admin/index.html.twig', ['users' => $users]);
    }

    #[Route('/admin/edit_profile/{id}', name: 'app_admin_edit')]
    #[IsGranted("ROLE_ADMIN")]
    public function editUser(Request $request,EntityManagerInterface $entityManager, $id, UserPasswordHasherInterface $passwordHasher): \Symfony\Component\HttpFoundation\Response
    {
        $user = $this->userService->find($id);
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            if ($form->get('password')->getData()) {
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
        ]);


    }

    #[Route('/admin/restaurants', name: 'app_admin_restaurants', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function restaurants(): Response
    {
        $restaurants = $this->restaurantService->findAllRestaurants();
        return $this->render('admin/restaurants.html.twig', ['restaurants' => $restaurants]);
    }

    #[Route('/admin/dishes', name: 'app_admin_dishes', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function dishes(): Response
    {
        $dishes = $this->menuItemService->getAll();
        return $this->render('admin/dishes.html.twig', ['dishes' => $dishes]);
    }

    #[Route('/admin/edit_restaurants/{id}', name: 'app_admin_edit_restaurant')]
    #[IsGranted("ROLE_ADMIN")]
    public function editRestaurant(Request $request,EntityManagerInterface $entityManager, $id): \Symfony\Component\HttpFoundation\Response
    {
        $restaurant = $this->restaurantService->find($id);
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($restaurant);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_restaurants');
        }

        return $this->render('admin/edit_restaurant.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/edit_dish/{id}', name: 'app_admin_edit_dish')]
    #[IsGranted("ROLE_ADMIN")]
    public function editDish(Request $request,EntityManagerInterface $entityManager, $id): \Symfony\Component\HttpFoundation\Response
    {
        $dish = $this->menuItemService->find($id);
        $form = $this->createForm(MenuItemType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dish);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_dishes');
        }

        return $this->render('admin/edit_menuItem.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/admin/create-restaurant', name: 'app_admin_create_restaurant')]
    #[IsGranted("ROLE_ADMIN")]
    public function createRestaurant(Request $request, EntityManagerInterface $entityManager): Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($restaurant);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_restaurants');
        }

        return $this->render('admin/create_restaurant.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/create-dish', name: 'app_admin_create_dish')]
    #[IsGranted("ROLE_ADMIN")]
    public function createDish(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dish = new MenuItem();
        $form = $this->createForm(MenuItemType::class, $dish);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dish);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_dishes');
        }

        return $this->render('admin/create_dish.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/delete-restaurant/{id}', name: 'app_admin_delete_restaurant', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteRestaurant(int $id, EntityManagerInterface $entityManager): Response
    {
        $restaurant = $this->restaurantService->find($id);

        $entityManager->remove($restaurant);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_restaurants');
    }

    #[Route('/admin/delete-dish/{id}', name: 'app_admin_delete_dish', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteMenuItem(int $id, EntityManagerInterface $entityManager): Response
    {
        $dish = $this->menuItemService->find($id);


        $entityManager->remove($dish);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_dishes');
    }
}