<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/crud/commentaire')]
class CrudCommentaireController extends AbstractController
{
    #[Route('/', name: 'app_crud_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('crud_commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }
    //
    #[Route('/{user}', name: 'user_commentaire', methods: ['GET'])]
    public function index_user(CommentaireRepository $commentaireRepository, $user): Response
    {
        $criteria = [
            'utilisateur' => $user
        ];
        return $this->render('crud_commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findBy($criteria),
        ]);
    }

    #[Route('/new/{idArticle}/{idUser}', name: 'app_crud_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $idArticle, $idUser, CommentaireRepository $commentaireRepository, ManagerRegistry $manager): Response
    {
        //Récupération de l'article
        $repository = $manager->getRepository(Article::class);
        $article = $repository->find($idArticle);
        //Récupération de l'utilisateur
        $repository = $manager->getRepository(Utilisateur::class);
        $utilisateur = $repository->find($idUser);
        //Récupération de tous les commentaires de l'article
        $repository = $manager->getRepository(Commentaire::class);
        $commentaires = $repository->findBy(['idArticle' => $idArticle]);

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setIdArticle($article);
            $commentaire->setUtilisateur($utilisateur);
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('acceuil', [], Response::HTTP_SEE_OTHER);
        } else {

            return $this->renderForm('crud_commentaire/new.html.twig', [
                'commentaire' => $commentaire,
                'form' => $form,
                'article' => $article,
                'commentaires' => $commentaires
            ]);
        }
    }

    #[Route('/{id}', name: 'app_crud_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('crud_commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_crud_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire, true);
        }

        return $this->redirectToRoute('app_crud_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
