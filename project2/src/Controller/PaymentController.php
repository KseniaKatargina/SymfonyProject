<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Service\OrderService;
use App\Service\UserService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PaymentController extends AbstractController
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

    #[IsGranted("ROLE_USER")]
    #[Route('/payment', name: 'app_payments', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $email = $user->getUserIdentifier();
        $id = $this->userService->findByEmail($email);
        
        $totalPrice = $request->query->get('totalPrice');

        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->security->getUser();
            $email = $user->getUserIdentifier();
            $id = $this->userService->findByEmail($email);
            $order = $this->orderService->findByUserId($id);
            $order->setStatus('paid');
            $order->setTime(new DateTime());
            $order->setTotalPrice($totalPrice);
            $entityManager->persist($order);
            $entityManager->flush();
            return $this->render('order/success.html.twig');
        }

        return $this->render('order/payment.html.twig', [
            'totalPrice' => $totalPrice,
            'form' => $form->createView(),
        ]);
    }
}