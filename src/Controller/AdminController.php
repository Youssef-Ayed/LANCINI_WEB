<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Postulation;
use App\Entity\Secteur;
use App\Form\OfferType;
use App\Form\SecteurType;
use App\Repository\OffreRepository;
use App\Repository\PostulationRepository;
use App\Repository\SecteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReclamationRepository;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Entity\Avis;
use App\Form\Avis1Type;
use App\Repository\AvisRepository;

#[Route('/admin')]
class AdminController extends AbstractController

{
    public function __construct(
        private OffreRepository $offreRepository,
        private SecteurRepository $secteurRepository,
        private PostulationRepository $postRepository,
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/offerList', name: 'app_admin_offerList')]
    public function affOffer(ManagerRegistry $mg): Response
    {
        $offer = $mg->getRepository(Offre::class)->findAll();
        return $this->render('admin/offerAdmin/offerList.html.twig', [
            'offers' => $offer,
        ]);
    }
    
    #[Route('/newrecadmin', name: 'app_reclamation_admin', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_recadd', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/reclamationadmin/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    

    #[Route('/showlist', name: 'app_reclamation_recadd', methods: ['GET'])]
    public function admin(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('admin/reclamationadmin/show.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
    #[Route('adminedit/{id}/edit', name: 'app_reclamation_editadmin', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/reclamationadmin/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    #[Route('deleteadmin/{id}', name: 'app_reclamation_deleteadmin', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_show', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/indexavis', name: 'app_avis_admin', methods: ['GET'])]
    public function indexx(AvisRepository $avisRepository): Response
    {
        return $this->render('admin/avisadmin/index.html.twig', [
            'avis' => $avisRepository->findAll(),
        ]);
    }

    #[Route('/newavis', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function newfeedback(Request $request, AvisRepository $avisRepository): Response
    {
        $avi = new Avis();
        $form = $this->createForm(Avis1Type::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avisRepository->save($avi, true);

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/avisadmin/new.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('showavis/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        return $this->render('admin/avisadmin/show.html.twig', [
            'avi' => $avi,
        ]);
    }
    #[Route('editavis/{id}/edit', name: 'app_avis_editavis', methods: ['GET', 'POST'])]
    public function editavis(Request $request, Avis $avi, AvisRepository $avisRepository): Response
    {
        $form = $this->createForm(Avis1Type::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avisRepository->save($avi, true);

            return $this->redirectToRoute('app_avis_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/avisadmin/edit.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('deleteavis/{id}', name: 'app_avis_deleteavis', methods: ['POST'])]
    public function deleteavis(Request $request, Avis $avi, AvisRepository $avisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $avisRepository->remove($avi, true);
        }

        return $this->redirectToRoute('admin/avisadmin/delete   .html.twig', [], Response::HTTP_SEE_OTHER);
    }

}
