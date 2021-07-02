<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtatController extends AbstractController
{
    /**
     * @Route("/publier/{id}", name="publier")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function publier(
        $id,
        EntityManagerInterface $entityManager

    ): Response
    {
        //recupérer la sortie
        /** @var SortieRepository $sortieRepository */
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        //recuperer l'état
        /** @var EtatRepository $etatRepository */
        $etatRepository = $entityManager->getRepository(Etat::class);
        $etat = $etatRepository->findBy(['libelle' => 'Ouverte']);
        //dd($etat);

        //désinscrire le participant
        $sortie->setEtat($etat[0]);
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('home');

    }


    /**
     * @Route("/annuler/{id}", name="annuler")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function annuler(
        $id,
        EntityManagerInterface $entityManager,
        Request $request

    ): Response
    {
        //recupérer la sortie
        /** @var SortieRepository $sortieRepository */
        $sortieRepository = $entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        //recuperer l'état
        /** @var EtatRepository $etatRepository */
        $etatRepository = $entityManager->getRepository(Etat::class);
        $etat = $etatRepository->findBy(['libelle' => 'Annulée']);
        $motif = $request->get('motif');

        if ($motif) {
            //désinscrire le participant
            $sortie->setInfoSortie($motif);
            $sortie->setEtat($etat[0]);
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('etat/annuler.html.twig', [
            'sortie'=>$sortie

        ]);

    }

}
