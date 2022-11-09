<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\Produit;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCrudAvisController extends AbstractController
{
    #[Route('/admin/crud/avis', name: 'admin_crud_avis')]
    public function index(AvisRepository $repo): Response
    {
        
        return $this->render('admin_crud_avis/index.html.twig', [
            'controller_name' => 'AdminCrudAvisController',
           
        ]);
    }

    #[Route('/admin/crud/avis/new', name: 'admin_crud_avis_new')]
    #[Route('/admin/crud/avis/edit/{id}', name: 'admin_crud_avis_edit')]
    public function form(Avis $avis = null, Request $rq, EntityManagerInterface $manager,AvisRepository $repo)
    {
        $avisAll= $repo->findAll();
       // dd($avisAll);
        if (!$avis)
        {
            $avis = new Avis;
        }
      
           
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($rq);
        if ($form->isSubmitted() && $form->isValid())
        {
            $avis->setMembre($this->getUser());
           $avis->setCreatedAt(new \DateTime());

           $manager->persist($avis);
           $manager->flush();
           $this->addFlash('success', 'Votre avis a bien été enregistrée !');
           return $this->redirectToRoute('app_main');
           

        }

       

        return $this->renderForm('admin_crud_avis/index.html.twig',[
            'form'=>$form,
            'editMode'=> $avis->getId() !=NULL,
            'avisAll'=>$avisAll
            
        ]);
    }

}
