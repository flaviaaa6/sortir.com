<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\EditSortieType;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/add/{id}", name="add_sortie")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function add(
        Request $request,
        $id = null,
        EntityManagerInterface $entityManager): Response
    {
        //si on a un id, on modifie la sortie
        if ($id){
            $sortieRepository = $entityManager->getRepository(Sortie::class);
            $sortie = $sortieRepository->find($id);
        }else{
            // Créer une nouvelle instance de Sortie et Lieu
            $sortie = new Sortie();
        }
        $lieu = new Lieu();
        
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        
        // On écoute et analyse la requête
        $sortieForm->handleRequest($request);
        $lieuForm->handleRequest($request);
    
        //recuperer l'utilisateur connecté
        /** @var ParticipantRepository $participantRepository */
        $participantRepository=$entityManager->getRepository(Participant::class);
        $user= $this->getUser();
        //transformer le user en participant
        $participant = $participantRepository->findOneBy(['email'=>$user->getUsername()]);
        $site = $participant->getSite();
        
        // Vérifier si le formulaire est bien envoyé et validé
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){

            
            if ($sortieForm->get('enregistrer')->isClicked()) {
                $etat = $entityManager->getRepository(Etat::class)->findOneBy(
                    ['libelle' => 'Créée']
                );
                $sortie->setEtat($etat);
                
                $this->addFlash('success', "La sortie a été créée avec succès!" . "\n"
                    . "Attention: Nombre de personnes limité par rapport à l'urgence sanitaire" . "\n"
                    . "Veuillez respecter les gestes barrières. Merci!");
            }
            elseif($sortieForm->get('publier')->isClicked()) {
                $etat = $entityManager->getRepository(Etat::class)->findOneBy(
                    ['libelle' => 'Ouverte']
                );
                $sortie->setEtat($etat);
    
                $this->addFlash('success', "La sortie a été publiée avec succès!\n"
                    . "Attention: Nombre de personnes limité par rapport à l'urgence sanitaire\n"
                    . "Veuillez respecter les gestes barrières. Merci!");
            
            }
    
            $organisateur = $entityManager->getRepository(Participant::class)
                                          ->find($this->getUser()->getId());
            $sortie->setOrganisateur($organisateur);
            $organisateur->addSortieInscrit($sortie);
            $sortie->setSite($site);
            
            // Besoin de doctrine pour enregistrer dans la bdd
            $entityManager->persist($sortie);
            $entityManager->flush();
            
            return $this->redirectToRoute('home');
        }
        
        return $this->render('sortie/add.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView(),
            //'sortie' => $sortie,
            'id' => $id
        ]);
    }

    
    /**
     * @Route("/sortie/remove/{id}", name="remove_sortie")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function remove(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sortie = $entityManager->getRepository(Sortie::class)->find($request->get('id'));
        
        $entityManager->remove($sortie);
        $entityManager->flush();
        
        $this->addFlash('success', 'L\'inscription a été supprimée correctement !');
        
        return $this->redirectToRoute('home');
    }
    

    
}
