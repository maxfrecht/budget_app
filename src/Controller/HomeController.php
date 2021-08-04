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
        $users = $this->userRepository->findAll();
        $results = [];

        foreach ($users as $user) {
            $total = 0;
            foreach($user->getPaiements() as $p) {
                $total += $p->getAmount();
            }
            $results[$user->getEmail()] = $total;
        }
        $max = max($results);
        $min = min($results);
        foreach($results as $email => $total) {
            if($total === max($results)) {
                $maxUser = $email;

            } else {
                $minUser = $email;

            }
        }
        return $this->render('home/index.html.twig', [
            'users' => $users,
            'minUser' => $minUser,
            'maxUser' => $maxUser,
            'minP' => $min,
            'maxP' => $max
        ]);
    }
}
