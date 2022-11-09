<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Membre;
use App\Form\AvisType;
use App\Entity\Reservation;

use App\Form\ReservationType;
use App\Repository\AvisRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    #[Route('/main/produits', name: 'app_produits')]
    public function produits(ProduitRepository $repo,)
    {
        $produits = $repo->findAll();
        return $this->render('main/produits.html.twig',[
            'produits'=>$produits
        ]);
      }

    #[Route('/main/reservation/new', name: 'app_new_reservation')]
    #[Route('/main/reservation/edit/{id}', name: 'app_reservation_edit')]
    public function new_reservation( EntityManagerInterface $manager, Request $rq, Reservation $reservation = null)
    {
        if (!$reservation)
        {
            $reservation = new Reservation;
           
        }   
            $form = $this->createForm(ReservationType::class, $reservation);
           // dd($form);
             $form->handleRequest($rq);
            // dd($form);
            if ($form->isSubmitted() && $form->isValid())
             {
                $reservation->setMembre($this->getUser());
                $reservation->setCreatedAt(new \DateTime());
              
                $manager->persist($reservation);
                $manager->flush();
                $this->addFlash('success', 'Votre reservation a bien été enregistrée !');
                return $this->redirectToRoute('app_main');
                
            }  
               
           
        
             return $this->renderForm('main/reservation.html.twig', [
                'form' => $form,
                'editMode'=> $reservation->getId() !=NULL,           
             ]);

        


    }
    #[Route('/main/avis/new', name: 'app_new_avis')]
    public function avis(AvisRepository $repo, Request $rq,EntityManagerInterface $manager)
    {
    
    
            return $this->render('admin_crud_avis/index.html.twig');
                
    
        }
    #[Route('/main/contact/new', name: 'app_contact')]
    public function contact()
    {
        return $this->render('main/contact.html.twig');
    }

}
