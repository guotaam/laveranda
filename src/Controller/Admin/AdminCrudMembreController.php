<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCrudMembreController extends AbstractController
{
    #[Route('/admin/crud/membre', name: 'admin_crud_membre')]
    public function index(MembreRepository $repo, EntityManagerInterface $manager): Response
    {
        $colonnes = $manager->getClassMetadata(Membre::class)->getFieldNames();

        $membres = $repo->findAll();

        return $this->render('admin_crud_membre/index.html.twig', [
            'membres' => $membres,
            'colonnes' =>$colonnes
        ]);
      
    }

    #[Route('/admin/crud/membre/delete/{id}', name:'admin_crud_membre_delete' )]
    public function delete(Membre $membre = null, EntityManagerInterface $manager): Response
    {
        if ($membre) {
            $manager->remove($membre);
            $manager->flush();
            $this->addFlash('success', 'Le membre a bien été supprimé !');
           
        }
        return $this->redirectToRoute('app_admin_crud_membre');
    }

}
