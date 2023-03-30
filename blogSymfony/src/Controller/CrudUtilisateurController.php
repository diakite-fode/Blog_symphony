<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud/utilisateur')]
class CrudUtilisateurController extends AbstractController
{
    #[Route('/', name: 'app_crud_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('crud_utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_crud_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UtilisateurRepository $utilisateurRepository, RoleRepository $roleRepository): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setRole($roleRepository->find(1));
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_crud_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('crud_utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_crud_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur, true);
        }

        return $this->redirectToRoute('app_crud_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
