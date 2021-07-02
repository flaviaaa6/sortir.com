<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Entity\Lieu;
use App\Repository\VilleRepository;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminVilleController extends AbstractController
{
    /**
     * @Route("/admin/ville", name="admin_ville")
     */
    public function index(): Response
    {
        return $this->render('admin/gestionVille.html.twig', [

        ]);
    }

    /**
     * @Route("/gestion_ville", name="admin_ville")
     * @return Response
     */
    public function gererVille(
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $recherche = $request->get("search");
        $id="";

        /** @var VilleRepository $villeRep */
        $villeRep=$em->getRepository(Ville::class);
        if($recherche) {
            $villes = $villeRep->rechercheVille($recherche);
        }else{
            $villes = $villeRep->findAll();
        }

        $newVille = $request->get("ville");
        $newCode = $request->get("code_postal");
        if($newVille){
            $ville = new Ville();
            $ville->setNom($newVille);
            $ville->setCodePostal($newCode);

            $em->persist($ville, $newCode);
            $em->flush();
            return $this->redirectToRoute('admin_ville');
            $this->addFlash('success', 'La ville '.$ville->getNom().' a bien été ajoutée.');
        }


        return $this->render('admin/gestionVille.html.twig', [
            'villes' => $villes,
            'id'=>$id,
        ]);
    }

    /**
     * @Route("/delete-ville/{id}", name="delete_ville")
     * @return Response
     */
    public function deleteVille(
        $id,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {

        $lieuRep = $em->getRepository(Lieu::class);
        $lieu = $lieuRep->find($id);

        $villeRep=$em->getRepository(Ville::class);
        $ville = $villeRep->find($id);

        if ($ville && !$lieu){
            $em->remove($ville);
            $em->flush();

            $this->addFlash('success', 'La ville '.$ville->getNom().' a bien été supprimée.');
        }else{
            $this->addFlash('danger', 'La ville '.$ville->getNom().' a des lieux associés. Vous ne pouvez pas la supprimer');
        }

        return $this->redirectToRoute('admin_ville');

    }

    /**
     * @Route("/update-ville/{id}", name="update_ville")
     * @return Response
     */
    public function updateVille(
        $id,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $lieuRep = $em->getRepository(Lieu::class);
        $lieu = $lieuRep->find($id);

        $villeRep=$em->getRepository(Ville::class);
        $villes = $villeRep->findAll();

        return $this->render('admin/gestionVille.html.twig', [
            'villes' => $villes,
            'id'=>$id,
            'lieu' => $lieu,
        ]);

    }

    /**
     * @Route("/validate-update-ville/{id}", name="validate_update_ville")
     * @return Response
     */
    public function validationUpdateVille(
        $id,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {

        $villeRep = $em->getRepository(Ville::class);
        $ville = $villeRep->find($id);
        $nom = $request->get("nom");
        $cp = $request->get("codePostal");

        if($nom) {
            $ville->setNom($nom);
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'la ville ' . $ville->getNom() . ' a bien été modifiée.');

        }elseif ($cp){
            $ville->setCodePostal($cp);
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'le code postal ' . $ville->getCodePostal() . ' a bien été modifié.');
        }
        return $this->redirectToRoute('admin_ville');
    }
}
