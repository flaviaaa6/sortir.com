<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Lieu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieVueController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     */
    public function index(): Response
    {

        return $this->render('sortie/index.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/sortie/vue/{id}", name="sortie_vue")
     */
    public function detailSortie($id, EntityManagerInterface $em, Request $request): Response
    {
        $sortieRep = $em->getRepository(Sortie::class);
        $sortie = $sortieRep->find($id);
        $lieuRep = $em->getRepository(Lieu::class);
        $lieu = $lieuRep->find($id);
        $participantRep = $em->getRepository(Sortie::class);
        $participants = $participantRep->find($id);
        return $this->render('sortie/sortiefiche.html.twig',
            [
                'sortie' => $sortie,
                'lieu' => $lieu,
                'participants' => $participants,
            ]
        );
    }
    //TODO
    //peut-être une 2ème méthode permettan de récupérer les participants avec un query builder dans le repository
    //veiller à la sécurité: faire en sorte que les fonctions ne soient applicables que par l'utilisateur concerné
    //récupérer les données par un autre moyen que l'ID
}
