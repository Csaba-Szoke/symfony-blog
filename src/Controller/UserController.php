<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/profile/{id?}", name="user_profile")
     */
    public function profile($id)
    {
        if ($id) {
            $user = $this->userRepo->find($id);

            if (!$user) {
                throw $this->createNotFoundException(
                    'No user found for id ' . $id
                );
            }
        } else {
            $user = $this->getUser();
        }

        return $this->render('user/profile.html.twig', ['user' => $user]);
    }
}
