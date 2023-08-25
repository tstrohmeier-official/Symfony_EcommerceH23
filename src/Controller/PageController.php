<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/contacts', name: 'route_contacts')]
    public function indexRoute(Request $request): Response
    {
        return $this->render('page/contacts.html.twig');
    }
}
