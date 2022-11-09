<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminCrudSliderController extends AbstractController
{
    #[Route('/admin/crud/slider', name: 'app_admin_crud_slider')]
    public function index(): Response
    {
        return $this->render('admin_crud_slider/index.html.twig', [
            'controller_name' => 'AdminCrudSliderController',
        ]);
    }



    #[Route('/admin/crud/slider/new', name: 'admin_crud_slider_new')]
    #[Route('/admin/crud/slider/edit/{id}', name: 'admin_crud_slider_edit')]

    public function form(Slider $slider = null,SluggerInterface $slugger ,Request $rq, EntityManagerInterface $manager,SliderRepository $repo)
    {
        $sliders = $repo->findAll();
        if (!$slider) {
            $slider = new Slider;
            $slider->setCreatedAt(new \DateTime());
        }
        $form = $this->createForm(SliderType::class, $slider);

        $form->handleRequest($rq);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('file')->getData())
            {
              $file=$form->get('file')->getData();
                
              $fileName = $slugger->slug($slider->getOrdre()).uniqid().'.'.$file->guessExtension();

               
               try{
                 $file->move($this->getParameter('photo'),$fileName);
               }catch(FileException $e)
               {
     
               }
     
              $slider->setPhoto($fileName);
            }
            $manager->persist($slider);
            $manager->flush();
            $this->addFlash('success', 'Le slider a bien été enregistré !');

            return $this->redirectToRoute('app_admin');
        }
        return $this->renderForm('admin_crud_slider/index.html.twig', [
            'form' => $form,
            'editMode' => $slider->getId() != NULL,
            'photo'=>$sliders
        ]);
    }

   
   
   
   #[Route('/admin/crud/slider/delete/{id}', name:'admin_crud_slider_delete' )]
        public function delete(Slider $slider = null, EntityManagerInterface $manager): Response
        {
            if ($slider) {
                $manager->remove($slider);
                $manager->flush();
                $this->addFlash('success', 'Le slider a bien été supprimé !');
               
            }
            return $this->redirectToRoute('admin_crud_slider_new');
    
        } 
    }






