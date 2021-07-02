<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    /**
     * inscrit un utilisateur a une sortie
     * @Route("/inscription/{id}", name="inscription")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function inscription(
         $id,
         EntityManagerInterface $entityManager

    ): Response
    {
        //recuperer la sortie
        /** @var SortieRepository $sortieRepository */
        $sortieRepository=$entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        $nbParticpants = $sortie->getParticipants()->count();
        //recuperer l'utilisateur connecté
        /** @var ParticipantRepository $participantRepository */
        $participantRepository=$entityManager->getRepository(Participant::class);
        $user= $this->getUser();
        //transformer le user en participant
        $participant = $participantRepository->findBy(['email'=>$user->getUsername()]);

        //verifier qu'il peut s'inscrire
        if(($nbParticpants < $sortie->getNbrInscriptionMax()) and ($participant[0]->getIsActif() == true)) {

            //inscrire le participant
            $sortie->addParticipant($participant[0]);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'vous êtes bien inscrit a la sortie '.$sortie->getNom().'.');

        } elseif($participant[0]->getIsActif() == false){
            $this->addFlash('danger', 'Inscription impossible car vous êtes inactif.');
        }else{
            $this->addFlash('danger', 'le nombre de participants a la sortie '.$sortie->getNom().' est au maximum.');
        }
        return $this->redirectToRoute('home');

    }


    /**
     * desinscrit un participant
     * @Route("/desister{id}", name="desister")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function desister(
         $id,
         EntityManagerInterface $entityManager

    ): Response
    {
        //recuperer la sortie
        /** @var SortieRepository $sortieRepository */
        $sortieRepository=$entityManager->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        //recuperer l'utilisateur connecté
        /** @var ParticipantRepository $participantRepository */
        $participantRepository=$entityManager->getRepository(Participant::class);
        $user= $this->getUser();

        //transformer le user en participant
        $participant = $participantRepository->findBy(['email'=>$user->getUsername()]);

        //désinscrire le participant
        $sortie->removeParticipant($participant[0]);
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('home');

    }



}
