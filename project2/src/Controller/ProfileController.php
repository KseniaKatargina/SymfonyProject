<?php
namespace App\Controller;

use App\Form\ProfileType;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    private UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[IsGranted("ROLE_USER")]
    #[Route("/profile", name: "app_profile", methods: ['GET'])]
    public function index(): Response
    {

        return $this->render('profile/index.html.twig');
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/profile/edit', name: 'app_edit_profile', methods: ['GET, POST'])]
    public function editProfile(Security $security, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {

        $securityUser = $security->getUser();
        $email = $securityUser->getUserIdentifier();
        $user = $this->userService->findUserByEmail($email);


        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
