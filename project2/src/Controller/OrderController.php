<?php

namespace App\Controller;

use App\Service\OrderService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class OrderController extends AbstractController
{
    private Security $security;
    private OrderService $orderService;

    private UserService $userService;

    /**
     * @param Security $security
     * @param OrderService $orderService
     * @param UserService $userService
     */
    public function __construct(Security $security, OrderService $orderService, UserService $userService)
    {
        $this->security = $security;
        $this->orderService = $orderService;
        $this->userService = $userService;
    }


    #[Route('/order', name: 'app_order', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function displayUserOrder(): Response
    {

        $user = $this->security->getUser();
        $email = $user->getUserIdentifier();
        $id = $this->userService->findByEmail($email);
        $order = $this->orderService->findByUserId($id);

        $orderItems = [];


        if ($order) {
            $orderItems = $order->getOrderItems();
        }

        $totalPrice = 0;
        foreach ($orderItems as $item) {
            $menuItem = $item->getMenuItem();

            $totalPrice += $item->getQuantity() * $menuItem->getPrice();
        }
        if($order){
            $order->setTotalPrice($totalPrice);
        }

        return $this->render('order/user_order.html.twig', [
            'email' => $email,
            'id' => $id,
            'order' => $order,
            'items' => $orderItems
        ]);
    }

    #[Route('/orders', name: 'user_orders', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function myOrders(): Response
    {
        $orders = $this->orderService->getAllUserOrders();

        return $this->render('order/user_orders.html.twig', ['orders' => $orders]);
    }

    #[Route('/order/{orderId}', name: 'order_details', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function details($orderId): Response
    {
        $order = $this->orderService->find($orderId);

        $items = $order->getOrderItems();

        return $this->render('order/details.html.twig', ['order' => $order, 'items'=>$items]);
    }

    #[Route('/update-order-status/{orderId}/{status}', name: 'update_order_status', methods: ['POST'])]
    #[IsGranted("ROLE_USER")]
    public function updateOrderStatus(OrderService $orderService, $orderId, $status, EntityManagerInterface $entityManager): JsonResponse
    {

        $order = $orderService->find($orderId);

        switch ($status) {
            case 'delivered':
                $order->setStatus('delivered');
                break;
            case 'cancelled':
                $order->setStatus('cancelled');
                break;
            default:
                return $this->json(['error' => 'Invalid status'], 400);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json(['status' => $order->getStatus()]);
    }

}