<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function acceuil()
    {
        $userConnected = $this->getUser();
        $data = [
            "user" => $userConnected->getUserIdentifier()
        ];
        return $this->render('/index.html.twig', $data);
    }

    #[Route('/connexion', name: 'connexion')]
    public function connexion()
    {
        return $this->render('/connexion.html.twig');
    }
}
