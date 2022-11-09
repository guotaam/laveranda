<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminCrudProduitController extends AbstractController
{
    #[Route('/admin/crud/produit', name: 'app_admin_crud_produit')]
    public function index(): Response
    {
     //   $colonnes = $manager->getClassMetadata(Produit::class)->getFieldNames();
       // $prods = $repo->findAll();
       // dd($prods);
        return $this->render('admin_crud_produit/index.html.twig');
            
         //   'colonnes' => $colonnes,
           // 'prods' => $prods
    
    }

    #[Route('/admin/crud/produit/new', name: 'admin_crud_produit_new')]
    
    #[Route('/admin/crud/produit/edit/{id}', name: 'admin_crud_produit_edit')]
    public function form(Produit $produit = null, Request $rq,SluggerInterface $slugger, EntityManagerInterface $manager,ProduitRepository $repo)
    {
        $prods = $repo->findAll();
      
             
           
        if (!$produit) {
            $produit = new Produit;
            $produit->setCreatedAt(new \DateTime());
        }
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($rq);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('photo')->getData())
            {
              $file=$form->get('photo')->getData();
                
              $fileName = $slugger->slug($produit->getId()).uniqid().'.'.$file->guessExtension();

               
               try{
                 $file->move($this->getParameter('photo'),$fileName);
               }catch(FileException $e)
               {
     
               }
               $produit->setPhoto($fileName);
            }
            $manager->persist($produit);
            $manager->flush();
            $this->addFlash('success', 'Le produit a bien été enregistré !');

            return $this->redirectToRoute('app_admin');
        }
        return $this->renderForm('admin_crud_produit/index.html.twig', [
            'form' => $form,
            'editMode' => $produit->getId() != NULL,
            'prods'=>$prods
        ]);
    }
    #[Route('/admin/crud/produit/delete/{id}', name:'admin_crud_produit_delete' )]
    public function delete(Produit $produit = null, EntityManagerInterface $manager): Response
    {
        if ($produit) {
            $manager->remove($produit);
            $manager->flush();
            $this->addFlash('success', 'Le produit a bien été supprimé !');
           
        }
        return $this->redirectToRoute('admin_crud_produit_new');

    } 
 
}
