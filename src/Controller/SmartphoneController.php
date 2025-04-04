<?php

namespace App\Controller;

use App\Entity\Smartphone;
use App\Form\SmartphoneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SmartphoneController extends AbstractController
{
    #[Route('/smartphone/add', name: 'app_add_smartphone')]
    public function addSmartphone(Request $request, EntityManagerInterface $entityManager): Response
    {
        $smartphone = new Smartphone();
        $form = $this->createForm(SmartphoneType::class, $smartphone);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $smartphone = $form->getData();
            $entityManager->persist($smartphone);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Your product is added!'
            );
            return $this->redirectToRoute('app_list_smartphone');
        }
        return $this->render('smartphone/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/smartphone/list', name: 'app_list_smartphone')]
    public function listSmartphones(EntityManagerInterface $entityManager): Response
    {
        $smartphones = $entityManager->getRepository(Smartphone::class)->findAll();
        return $this->render('smartphone/smartphones-show.html.twig', [
            'smartphones' => $smartphones,
        ]);
    }

    #[Route('/smartphone/show/{id}', name: 'app_show_smartphone')]
    public function show(Smartphone $smartphone, Request $request, EntityManagerInterface $entityManager): Response
    {
        $smartphone = $entityManager->getRepository(Smartphone::class)->find($smartphone->getId());

        return $this->render('smartphone/show.html.twig', [
            'smartphone' => $smartphone,
        ]);
    }

    #[Route('/smartphone/delete/{id}', name: 'app_delete_smartphone')]
    public function delete(Smartphone $smartphone, Request $request, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($smartphone);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Succesfully deleted!'
        );
        return $this->redirectToRoute('app_list_smartphone');
    }
    #[Route('/smartphone/delete/confirmation/{id}/', name: 'app_delete_smartphone_confirm')]
    public function deleteConfirmation(Smartphone $smartphone, Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('smartphone/delete.html.twig', [
            'smartphone' => $smartphone,
        ]);
    }

    #[Route('/smartphone/edit/{id}', name: 'app_edit_smartphone')]
    public function edit(Smartphone $smartphone, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SmartphoneType::class, $smartphone);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $smartphone = $form->getData();
            $entityManager->persist($smartphone);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Your changes were saved!'
            );
            return $this->redirectToRoute('app_list_smartphone');
        }
        return $this->render('smartphone/edit.html.twig', [
            'form' => $form,
        ]);
    }
}