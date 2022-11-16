<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCrudCategoriesController extends AbstractController
{
    #[Route('/admin/crud/categories', name: 'app_admin_crud_categories')]
    public function index(): Response
    {
        return $this->render('admin_crud_categories/index.html.twig', [
            'controller_name' => 'AdminCrudCategoriesController',
        ]);
    }
    #[Route('/admin/crud/categories/new', name: 'admin_crud_categories_new')]
    #[Route('/admin/crud/categories/edit/{id}', name: 'admin_crud_categories_edit')]
    public function formCategories(Categories $categories = null,Request $rq, CategoriesRepository $repo, EntityManagerInterface $manager)
   {
    $categorie = $repo->findAll();
    if (!$categories) {
            
    $categories = new Categories;
    //dd($categories);
    }
    $form = $this->createForm(CategoriesType::class, $categories);
    $form->handleRequest($rq);
   // dd($form);
    
   if ($form->isSubmitted() && $form->isValid()) {
      
        $manager->persist($categories);
        $manager->flush();
        
       $this->addFlash('success', 'la categorie a bien été enregistré !');
       return $this->redirectToRoute('admin_crud_categories_new');
       
   }
   return $this->renderForm('admin_crud_categories/index.html.twig',[
    'categorieall' => $categorie,
    'formCategories'=> $form,
    'editMode'=> $categories->getId() !=NULL
     

   ]);
   }
   #[Route('/admin/crud/categories/delete/{id}', name:'admin_crud_categories_delete' )]
    public function delete(Categories $categories = null, EntityManagerInterface $manager): Response
    {
        if ($categories) {
            $manager->remove($categories);
            $manager->flush();
            $this->addFlash('success', 'La catégorie a bien été supprimé !');
           
        }
        return $this->redirectToRoute('admin_crud_categories_new');

    } 
}