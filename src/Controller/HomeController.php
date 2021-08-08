<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PaiementRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


class HomeController extends AbstractController
{
    private PaiementRepository $paiementRepository;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private TranslatorInterface $translator;

    /**
     * @param PaiementRepository $paiementRepository
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param TranslatorInterface $translator
     */
    public function __construct(PaiementRepository $paiementRepository, EntityManagerInterface $em, UserRepository $userRepository, TranslatorInterface $translator)
    {
        $this->paiementRepository = $paiementRepository;
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    public function getRecurrentPaiements(User $user) {
        return $this->paiementRepository->createQueryBuilder('p')
            ->andWhere('p.isRecurrent = :isRecurrent')
            ->setParameter('isRecurrent', true)
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }
    public function getCurrentMonthPaiements(User $user, $lessMonth = null) {
        $now = new DateTime();
        if($lessMonth) {
            $now->modify('-' . $lessMonth . ' month');
        }
        $currentMonth = $now->format('m');
        $currentYear = $now->format('Y');
        $currentMonthIncrement = $now->modify('+1 month')->format('m');
        dump($currentMonthIncrement);
        return $this->paiementRepository->createQueryBuilder('p')
            ->andWhere('p.createdAt > :dateMin')
            ->setParameter('dateMin', $currentYear . '-' . (strlen(intval($currentMonth)) < 2 ? '0' . number_format($currentMonth) : number_format($currentMonth)) . '-' . '01' . ' 00:00:00')
            ->andWhere('p.createdAt < :dateMax')
            ->setParameter('dateMax', $currentYear . '-' . $currentMonthIncrement . '-' . '01' . ' 00:00:00')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }
    public function getPaiements(array $recurrentPaiements, array $currentMonthpaiements) {
        $paiements = [];

        foreach($recurrentPaiements as $p) {
            $paiements[] = $p;
        }

        foreach ($currentMonthpaiements as $p) {
            if(!in_array($p, $paiements)) {
                $paiements[] = $p;
            }
        }
        return $paiements;
    }
    public function getTotal(array $userPaiements) {
        $total = 0;
        foreach ($userPaiements as $p) {
            $total += $p->getAmount();
        }
        return $total;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        $results = [];
        $usersPaiements = [];
        foreach ($users as $user) {
            $paiementsRecurrent = $this->getRecurrentPaiements($user);
            $paiementsMonth = $this->getCurrentMonthPaiements($user);
            $paiements = $this->getPaiements($paiementsRecurrent, $paiementsMonth);
            $total = $this->getTotal($paiements);
            $usersPaiements[$user->getEmail()] = $paiements;
            $results[$user->getPseudo()] = $total;
        }
        $max = max($results);
        $min = min($results);
        foreach ($results as $email => $total) {
            if ($total === max($results)) {
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
            'maxP' => $max,
            'results' =>$results,
            'usersPaiements' => $usersPaiements
        ]);
    }

    #[Route('/month/{less}', name: 'home_month')]
    public function indexMonth(int $less): Response
    {
        $users = $this->userRepository->findAll();
        $results = [];
        $usersPaiements = [];
        foreach ($users as $user) {
            $paiementsRecurrent = $this->getRecurrentPaiements($user);
            $paiementsMonth = $this->getCurrentMonthPaiements($user, $less);
            $paiements = $this->getPaiements($paiementsRecurrent, $paiementsMonth);
            $total = $this->getTotal($paiements);
            $usersPaiements[$user->getEmail()] = $paiements;
            $results[$user->getPseudo()] = $total;
        }
        $max = max($results);
        $min = min($results);
        foreach ($results as $email => $total) {
            if ($total === max($results)) {
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
            'maxP' => $max,
            'results' =>$results,
            'usersPaiements' => $usersPaiements
        ]);
    }
}
