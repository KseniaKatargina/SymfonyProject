<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Service\MenuItemService;
use App\Service\OrderItemService;
use App\Service\OrderService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MenuItemController extends AbstractController
{
    private Security $security;
    private UserService $userService;

    private MenuItemService $menuItemService;

    private OrderItemService $orderItemService;

    private OrderService $orderService;

    /**
     * @param Security $security
     * @param UserService $userService
     * @param MenuItemService $menuItemService
     * @param OrderItemService $orderItemService
     * @param OrderService $orderService
     */
    public function __construct(Security $security, UserService $userService, MenuItemService $menuItemService, OrderItemService $orderItemService, OrderService $orderService)
    {
        $this->security = $security;
        $this->userService = $userService;
        $this->menuItemService = $menuItemService;
        $this->orderItemService = $orderItemService;
        $this->orderService = $orderService;
    }

    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return new JsonResponse(['redirect' => $this->generateUrl('app_login')], 401);
        }
        $data = json_decode($request->getContent(), true);
        $dishId = $data['dishId'];
        $user = $this->security->getUser();

        $order = $this->userService->getOrCreateOrder($user);


        $dish = $this->menuItemService->find($dishId);

        $existingOrderItem = $order->getOrderItems()->filter(
            fn (OrderItem $orderItem) => $orderItem->getMenuItem() === $dish
        )->first();

        if ($existingOrderItem) {

            $existingOrderItem->setQuantity($existingOrderItem->getQuantity() + 1);
            return new JsonResponse(['message' => 'Блюдо успешно добавлено в заказ','quantity' => $existingOrderItem->getQuantity() ]);
        } else {

            $orderItem = new OrderItem();
            $orderItem->setUserOrder($order);
            $orderItem->setMenuItem($dish);
            $orderItem->setQuantity(1);

            $order->addOrderItem($orderItem);
        }
        $orderItems = $order->getOrderItems();

        foreach ($orderItems as $item) {
            $entityManager->persist($item);
        }
        $totalPrice = 0;
        foreach ($order->getOrderItems() as $item) {
            $menuItem = $item->getMenuItem();
            $totalPrice += $item->getQuantity() * $menuItem->getPrice();
        }

        $order->setTotalPrice($totalPrice);
        $entityManager->persist($order);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Блюдо успешно добавлено в заказ', 'quantity' => 1]);

    }

    #[Route('/update-quantity', name: 'update_quantity', methods: ['POST'])]
    #[IsGranted("ROLE_USER")]
    public function updateQuantity(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $orderItemId = $request->request->get('orderItemId');

        $action = $request->request->get('action');

        $orderItem = $this->orderItemService->findOrderItemById($orderItemId);

        if ($action === 'increase') {
            $orderItem->setQuantity($orderItem->getQuantity() + 1);
        } elseif ($action === 'decrease') {
            if ($orderItem->getQuantity() > 1) {
                $orderItem->setQuantity($orderItem->getQuantity() - 1);
            }
        } else {
            return new JsonResponse(['error' => 'Invalid action'], 400);
        }

        $order = $this->setTotalPrice($entityManager);

        return new JsonResponse(['quantity' => $orderItem->getQuantity(),
            'totalPrice' => $order->getTotalPrice()]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/remove-from-order', name: 'remove_from_order', methods: ['POST'])]
    public function removeFromOrder(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $orderItemId = $data['orderItemId'];
        $orderItem = $this->orderItemService->findOrderItemById($orderItemId);

        if ($orderItem) {
            $entityManager->remove($orderItem);
            $entityManager->flush();

            $order = $this->setTotalPrice($entityManager);
            return new JsonResponse(['success' => true, 'totalPrice' => $order->getTotalPrice()]);
        } else {
            return new JsonResponse(['success' => false, 'message' => 'Элемент заказа не найден.']);
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return float|int|mixed|string|null
     */
    public function setTotalPrice(EntityManagerInterface $entityManager): mixed
    {
        $user = $this->security->getUser();
        $email = $user->getUserIdentifier();
        $id = $this->userService->findByEmail($email);
        $order = $this->orderService->findByUserId($id);

        $totalPrice = 0;

        foreach ($order->getOrderItems() as $item) {
            $menuItem = $item->getMenuItem();
            $totalPrice += $item->getQuantity() * $menuItem->getPrice();
        }

        $order->setTotalPrice($totalPrice);
        $entityManager->persist($order);
        $entityManager->flush();
        return $order;
    }
}