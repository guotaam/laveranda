<?php

namespace App\Controller\Admin;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
//use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminCrudNewsletterController extends AbstractController
{
    #[Route('/admin/crud/newsletter', name: 'app_admin_crud_newsletter')]
    public function index(): Response
    {
        return $this->render('admin_crud_newsletter/index.html.twig', [
            'controller_name' => 'AdminCrudNewsletterController',
        ]);
    }
    
    #[Route('/admin/crud/newsletter/new', name: 'admin_crud_newsletter_new')]
    public function new(Newsletter $news = null, Request $rq, EntityManagerInterface $manager,SluggerInterface $slugger)
    {


        if (!$news) {
            $news = new Newsletter;
            $news->setCreatedAt(new \DateTime());
        }
        $form = $this->createForm(NewsletterType::class, $news);

        $form->handleRequest($rq);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('photo')->getData())
            {
              $file=$form->get('photo')->getData();
                
              $fileName = $slugger->slug($news->getTitre()).uniqid().'.'.$file->guessExtension();

               
               try{
                 $file->move($this->getParameter('photo'),$fileName);
               }catch(FileException $e)
               {
     
               }
               $news->setPhoto($fileName);
            }
            $manager->persist($news);
            $manager->flush();
            $this->addFlash('success', 'Newletter a bien été enregistré !');

            return $this->redirectToRoute('app_admin');
        }


        return $this->renderForm('admin_crud_newsletter/index.html.twig',[
            'form'=>$form
        ]);
      
    }

   
}
