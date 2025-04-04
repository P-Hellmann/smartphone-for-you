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
    // Route to add a new smartphone
    #[Route('/smartphone/add', name: 'app_add_smartphone')]
    public function addSmartphone(Request $request, EntityManagerInterface $entityManager): Response
    {
        $smartphone = new Smartphone(); // Create a new smartphone entity
        $form = $this->createForm(SmartphoneType::class, $smartphone); // Create a form for smartphone

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $smartphone = $form->getData(); // Get form data
            $entityManager->persist($smartphone); // Prepare entity for database storage
            $entityManager->flush(); // Save entity to database

            $this->addFlash('success', 'Your product is added!'); // Flash message confirmation

            return $this->redirectToRoute('app_list_smartphone'); // Redirect to smartphone list
        }
        return $this->render('smartphone/index.html.twig', [
            'form' => $form, // Pass form to template
        ]);
    }

    // Route to list all smartphones
    #[Route('/smartphone/list', name: 'app_list_smartphone')]
    public function listSmartphones(EntityManagerInterface $entityManager): Response
    {
        $smartphones = $entityManager->getRepository(Smartphone::class)->findAll(); // Retrieve all smartphones
        return $this->render('smartphone/smartphones-show.html.twig', [
            'smartphones' => $smartphones, // Pass data to template
        ]);
    }

    // Route to show details of a specific smartphone
    #[Route('/smartphone/show/{id}', name: 'app_show_smartphone')]
    public function show(Smartphone $smartphone, Request $request, EntityManagerInterface $entityManager): Response
    {
        $smartphone = $entityManager->getRepository(Smartphone::class)->find($smartphone->getId()); // Find smartphone by ID
        return $this->render('smartphone/show.html.twig', [
            'smartphone' => $smartphone, // Pass smartphone data to template
        ]);
    }

    // Route to delete a smartphone
    #[Route('/smartphone/delete/{id}', name: 'app_delete_smartphone')]
    public function delete(Smartphone $smartphone, Request $request, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($smartphone); // Remove entity from database
        $entityManager->flush(); // Apply deletion

        $this->addFlash('success', 'Successfully deleted!'); // Flash message confirmation

        return $this->redirectToRoute('app_list_smartphone'); // Redirect to smartphone list
    }

    // Route to confirm smartphone deletion
    #[Route('/smartphone/delete/confirmation/{id}/', name: 'app_delete_smartphone_confirm')]
    public function deleteConfirmation(Smartphone $smartphone, Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('smartphone/delete.html.twig', [
            'smartphone' => $smartphone, // Pass smartphone to confirmation template
        ]);
    }

    // Route to edit a smartphone
    #[Route('/smartphone/edit/{id}', name: 'app_edit_smartphone')]
    public function edit(Smartphone $smartphone, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SmartphoneType::class, $smartphone); // Create edit form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $smartphone = $form->getData(); // Get updated data
            $entityManager->persist($smartphone); // Prepare entity for saving
            $entityManager->flush(); // Save changes to database

            $this->addFlash('success', 'Your changes were saved!'); // Flash message confirmation

            return $this->redirectToRoute('app_list_smartphone'); // Redirect to smartphone list
        }
        return $this->render('smartphone/edit.html.twig', [
            'form' => $form, // Pass form to template
        ]);
    }
}
