<?php

namespace App\Controller\Admin;
use App\Entity\Membre;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManager;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCrudReservationController extends AbstractController
{
    #[Route('/admin/crud/reservation', name: 'admin_crud_reservation')]
    public function index(ReservationRepository $repo): Response
    {
        $resa = $repo->findAll();
        return $this->render('admin_crud_reservation/index.html.twig', [
            'resa' => $resa
        ]);
    }

    

   
    #[Route('/admin/crud/reservation/edit/{id}', name: 'admin_crud_reservation_edit')]
    public function form(Reservation $reservation = null,Request $rq, EntityManagerInterface $manager,ReservationRepository $repo)
    {
        $resa = $repo->findAll();
      
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($rq);
      // dd($form);
        
       if ($form->isSubmitted() && $form->isValid()) {
           $reservation->setMembre($this->getUser());
            $reservation->setCreatedAt(new \DateTime());
            $manager->persist($reservation);
            $manager->flush();
            
           $this->addFlash('success', 'la modification a bien été enregistré !');
           return $this->redirectToRoute('app_admin');
           
       }
       return $this->renderForm('admin_crud_reservation/index.html.twig',[
        
        'editMode'=> $reservation->getId() !=NULL,
        'resa' => $resa
         
    
       ]);


    }




}
