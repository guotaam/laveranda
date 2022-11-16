<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Membre;
use App\Entity\Slider;
use App\Form\AvisType;
use App\Entity\Categories;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\AvisRepository;
use App\Repository\SliderRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(SliderRepository $repo ,AvisRepository $repoavis): Response
    {    $avis= $repoavis->findAll();
        $sliders = $repo->findAll();
        return $this->render("main/index.html.twig", [
            'photo' => $sliders,
            'avisAll'=>$avis
        ]);
       
      
    }

    #[Route('/main/produits/{id}', name: 'app_produits')]
    public function produits($id,ProduitRepository $repo,Categories $categories,CategoriesRepository $categoriesRepository)
    {  
      //  $test = $categoriesRepository->findAll();
       
          $categories = $categoriesRepository->findBy(['id'=>$id]);
            $produits = $repo->findBy(['categories'=>$categories]);
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
   


    #[Route('/main/contact/new', name: 'app_contact')]
    public function contact()
    {
        return $this->render('main/contact.html.twig');
    }
    #[Route('/main/profil/', name: 'profil')]
    public function profil(ReservationRepository $repo)
    {
        $resevations = $repo->findBy(['membre' => $this->getUser()]);

        return $this->render("main/profil.html.twig", [
            'resa' => $resevations
        ]);
    }

    #[Route('/main/avis/new', name: 'avis_new')]
    #[Route('/main/avis/edit/{id}', name: 'avis_edit')]
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
          
            $this->addFlash('success', 'Merci pour votre avis !');
        
        return $this->redirectToRoute('avis_new');
        }
        return $this->renderForm('main/avis.html.twig',[
        'form' => $form,
        'editMode' => $avis->getId() !=NULL,
        'avisAll'=>$avisAll
        
       ]);
            
    }




    
}
