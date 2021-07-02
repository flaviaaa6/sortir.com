<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use App\Entity\Participant;
use App\Form\ModifType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{

    /**
     * @Route("/profil/{id}", name="profil", methods={"GET"})
     */
    public function profil($id, EntityManagerInterface $em, Request $request): Response
    {
        /** @var ParticipantRepository $participantRep */
        $participantRep = $em->getRepository(Participant::class);
        $participant = $participantRep->find($id);
        return $this->render('profil/profil.html.twig',
            [
                'participant' => $participant
            ]
        );
    }


    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     * @Route("/modif/{id}", name="profil_modif")
     */
    public function modifierProfil($id, EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder):Response
    {
        /** @var ParticipantRepository $participantRepository */
        $participant = $em->getRepository(Participant::class)->find($id);
        $modifForm = $this->createForm(ModifType::class, $participant);
        $modifForm->handleRequest($request);

        if($participant) {
            if ($modifForm->isSubmitted() && $modifForm->isValid()) {
                $participant = $modifForm->getData();
                $participant->setPassword($passwordEncoder->encodePassword($participant, $modifForm->get('password')->getData()));
                $em->persist($participant);
                $em->flush();

                $this->addFlash('success', 'Votre profil a bien été modifié');

                return $this->redirectToRoute('profil', ["id"=>$participant->getId()]);
            }
        }

        return $this->render('profil/modification.html.twig',
            [
                'modifForm' => $modifForm->createView()
            ]);
    }


}
