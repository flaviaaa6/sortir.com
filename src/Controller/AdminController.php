<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  @Route("/admin", name="admin_")
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/gestion-participant", name="gestion_participant")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function gererParticipants(
        EntityManagerInterface $entityManager,
        Request $request): Response
    {
        // Récupérer tous les participants
        /** @var ParticipantRepository $participantRepository */
        $participants = $entityManager->getRepository(Participant::class)
            ->findAll();


        return $this->render('admin/gestion_participant.html.twig', [
            'participants' => $participants
        ]);
    }




    /**
     * @Route("/gestion-site", name="gestion_site")
     * @return Response
     */
    public function gererSite(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        //récupérer les donnes du filtre de recherche
        $search = $request->get("search");
        $id="";

        //recuperer tous les sites
        /** @var SiteRepository $siteRepository */
        $siteRepository=$entityManager->getRepository(Site::class);
       if($search) {
           $sites = $siteRepository->siteSearch($search);
       }else{
           $sites = $siteRepository->findAll();
       }
        $siteAjout = $request->get("site");
       if($siteAjout){
           $site = new Site();
           $site->setNom($siteAjout);

           $entityManager->persist($site);
           $entityManager->flush();
           return $this->redirectToRoute('admin_gestion_site');
       }


        return $this->render('admin/gestion_site.html.twig', [
            'sites' => $sites,
            'id'=>$id,
        ]);
    }


    /**
     * @Route("/delete-site/{id}", name="delete_site")
     * @return Response
     */
    public function deleteSite(
        $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $siteRepository=$entityManager->getRepository(Site::class);
        $site = $siteRepository->find($id);

        $participantRep = $entityManager->getRepository(Participant::class);
        $lieu = $participantRep->find($id);
        if($site && !$lieu) {
            $entityManager->remove($site);
            $entityManager->flush();

            //ajouter un message flash
            $this->addFlash('success', 'le site ' . $site->getNom() . ' a bien été supprimé.');
        }else{
            $this->addFlash('success', 'le site ' . $site->getNom() . ' ne peut etre supprimé car il est rattaché a des utilisateurs.');

        }
            //rediriger vers une nouvelle route
            return $this->redirectToRoute('admin_gestion_site');

    }

    /**
     * @Route("/update-site/{id}", name="update_site")
     * @return Response
     */
    public function updateSite(
        $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $siteRepository=$entityManager->getRepository(Site::class);
        $sites = $siteRepository->findAll();

        return $this->render('admin/gestion_site.html.twig', [
            'sites' => $sites,
            'id'=>$id,
        ]);

    }

    /**
     * @Route("/validate-update-site/{id}", name="validate_update_site")
     * @return Response
     */
    public function validateUpdateSite(
        $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $siteRepository=$entityManager->getRepository(Site::class);
        $site = $siteRepository->find($id);
        $nom = $request->get("nom");

        if($nom) {
            $site->setNom($nom);
            $entityManager->persist($site);
            $entityManager->flush();

            //ajouter un message flash
            $this->addFlash('success', 'le site ' . $site->getNom() . ' a bien été modifié.');
        }
            //rediriger vers une nouvelle route
            return $this->redirectToRoute('admin_gestion_site');




    }
    
    
    /**
     * @Route("/delete/{id}", name="participant_delete")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     */
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager,
        $id): Response
    {
        $participant = $entityManager->getRepository(Participant::class)->find($id);
        
        if($participant == null) {
            throw $this->createNotFoundException('Le participant n\'existe pas ou a été supprimé');
        }
        $entityManager->remove($participant);
        $entityManager->flush();
        
        $this->addFlash('success', 'Le participant a été supprimé correctement');
        
        return $this->redirectToRoute('admin_option');
    }
    
    /**
     * @Route("/desactiver/{id}", name="participant_desactiver")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     */
    public function desactiver(
        Request $request,
        EntityManagerInterface $entityManager,
        $id): Response {
        
        $participant = $entityManager->getRepository(Participant::class)->find($id);
        
        if($participant == null) {
            throw $this->createNotFoundException('Le participant est inconnu ou déjà désactivé');
        }
        
        // Attribuer la valeur 0 pour désactiver un participant
        $participant->setIsActif(0);
        
        // Besoin de entity manager pour renseigner la bdd
        $entityManager->persist($participant);
        $entityManager->flush();
        
        return $this->redirectToRoute('admin_option');
    }
    
    /**
     * @Route("/activer/{id}", name="participant_activer")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     */
    public function activer(
        Request $request,
        EntityManagerInterface $entityManager,
        $id): Response
    {
        $participant = $entityManager->getRepository(Participant::class)->find($id);
        
        if($participant == null) {
            throw $this->createNotFoundException('Le participant est inconnu ou déjà désactivé');
        }
        
        // Attribuer la valeur 1 pour désactiver un participant
        $participant->setIsActif(1);
        
        //sauvegarder les données dans la base
        $entityManager->persist($participant);
        $entityManager->flush();
        
        return $this->redirectToRoute('admin_option');
    }
}
