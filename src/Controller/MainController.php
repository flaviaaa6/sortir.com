<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * affiche les sorties créées et les filtre
     * @Route("/", name="home")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function index(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        //requete pour recuperer toutes les sorties
        /** @var SortieRepository $sortieRepository */
        $sortieRepository = $entityManager->getRepository(Sortie::class);

        //requete pour recuperer tous les sites
        /** @var SiteRepository $siteRepository */
        $siteRepository = $entityManager->getRepository(Site::class);
        $sites = $siteRepository->findAll();

        //recuperer la personne connectée
        $user = $this->getUser();

        //si on est connecté
        if ($user) {
            //recupérer les données des filtres
            $search = $request->get("search");
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");
            $organisateur = $request->get("organisateur");
            $inscrit = $request->get("inscrit");
            $pasInscrit = $request->get("pasInscrit");
            $passee = $request->get("passee");

            //recupérer les donnés du filtre des sites
            $siteRecherche = $request->request->get('site');

            //appelle la fonction sortieFiltre() et
            // on envoi en parametre a la classe Repository les données des filtres utilisés
            $sorties = $sortieRepository->sortieFiltre($siteRecherche, $organisateur,
                $inscrit, $pasInscrit, $passee, $user, $search, $date1, $date2);

        } else//si on est pas connecté
        {
            $sorties = $sortieRepository->findAll();
            $siteRecherche = "";
            $search = "";
        }
        //si iln'y a pas de sorties correspondante, on affiche un message flash sur le navigateur
        if(!$sorties){
            $this->addFlash("warning","Il n'y a aucune sortie correspondante");
        }
        //on renvoi a la page twig correspondante”
        return $this->render('main/index.html.twig', [
            'sorties' => $sorties,
            'sites' => $sites,
            'siteRecherche' => $siteRecherche,
            'search' => $search,
        ]);
    }

}
