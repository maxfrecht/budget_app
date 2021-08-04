<?php

namespace App\Controller;

use App\Repository\PaiementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private PaiementRepository $paiementRepository;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    /**
     * @param PaiementRepository $paiementRepository
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     */
    public function __construct(PaiementRepository $paiementRepository, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->paiementRepository = $paiementRepository;
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $paiements = $this->paiementRepository->findAll();
        $users = $this->userRepository->findAll();
        return $this->render('home/index.html.twig', [
            'paiements' => $paiements,
            'users' => $users
        ]);
    }
}
