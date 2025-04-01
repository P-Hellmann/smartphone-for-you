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
    #[Route('/smartphone', name: 'app_add_smartphone')]
    public function addSmartphone(Request $request, EntityManagerInterface $entityManager): Response
    {
        $smartphone = new Smartphone();
        $form = $this->createForm(SmartphoneType::class, $smartphone);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $smartphone = $form->getData();
            $entityManager->persist($smartphone);
            $entityManager->flush();
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
}
