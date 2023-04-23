<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Post;
use App\Repository\EvenementRepository;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
    


    #[Route('/afficherEvenement', name: 'affichage')]
    public function afficher(ManagerRegistry $doctrine, Request $request, EvenementRepository $EvenementsRepository, PaginatorInterface $paginator): Response
    {

        $repository=$doctrine->getRepository(Evenement::class);
            $event=$repository->findAll() ;


            $event = $paginator->paginate(
                $event, /* query NOT result */
                $request->query->getInt('page', 1),
                3
            );

        return $this->render('evenement/affichageEvenement.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/afficherEvenementAdmin', name: 'app_evenementAdmin')]
    public function afficherEventAdmin(ManagerRegistry $doctrine): Response
    {

        $repository=$doctrine->getRepository(Evenement::class);
            $event=$repository->findAll() ;


        return $this->render('admin\adminEvenement\EvenementAdmin.html.twig',  [
            'event' => $event,
        ]);
    }

    #[Route('/creerEvenement', name: 'creer_evenement')]
public function ajouter(ManagerRegistry $mr, Request $request): Response
{

    
    $event = new Evenement;
    $form = $this->createForm(EvenementType::class,$event);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $mr->getManager();
        $selectedCountry = $form->get('lieu')->getData();
        $selectedCountryName = Countries::getName($selectedCountry);


        // Set the selected country name on the entity
        $event->setLieu($selectedCountryName);
        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('affichage');
    }

    return $this->render('evenement\ajouterEvenement.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/supprimerEvenement/{idevent}', name: 'supprimerEvenement')]
        public function supprimerEvenement($idevent, ManagerRegistry $doctrine): Response
        {
            //Trouver Evenement
            $repo = $doctrine->getRepository(Evenement::class);
            $evenement= $repo->find($idevent);
            //Utiliser Manager pour supprimer l'event trouvé
            $em= $doctrine->getManager();
            $em->remove($evenement);
            $em->flush();
            return $this->redirectToRoute('app_evenementAdmin');
        }

        
      
        #[Route('/modifierEvenement', name: 'modifierEvenement')]
        public function  modifierEvenement(ManagerRegistry $doctrine , Request $request)
        {

            $idevent = $request->query->get('event');      
            $idp=((int)$idevent);

            $evenement= $doctrine  -> getRepository(Evenement::class)-> find($idp) ;
            $form = $this->createForm(EvenementType::class, $evenement);
            $form->handleRequest($request);
            
            if ($form->isSubmitted()) {
                $em = $doctrine ->getManager();
                
                $em->flush();
                return $this->redirectToRoute('app_evenementAdmin'); 
            }
            return $this->render('admin\adminEvenement\modifierEvenementAdmin.html.twig', [
                'form' => $form->createView(),
            ]);
        }


       

}
