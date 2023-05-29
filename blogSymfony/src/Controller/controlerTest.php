<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\DependencyInjection\Security\UserProvider\EntityFactory;

//#[Route('/route1', name: 'route1')]
class controlerTest extends AbstractController
{
    #[Route('/test/{x}/{y}', name: 'addition')]
    public function addition($x = 10, $y = 10)
    {
        if (!empty($x) && !empty($y)) {
            $result = $x + $y;
        }
        return new Response("l'addition de " . $x . ' + ' . $y . 'est égale à ' . $result);
    }

    #[Route('/acceuil', name: 'acceuil')]
    public function acceuil(ManagerRegistry $manager)
    {
        //Récupération des articles pour les afficher en pages d'acceuil
        $repository = $manager->getRepository(Article::class);
        $articles = $repository->findAll();
        return $this->render('/index.html.twig', ['articles' => $articles]);
    }

    #[Route('/connexion', name: 'connexion')]
    public function connexion()
    {
        return $this->render('/connexion.html.twig');
    }
}
