<?php

namespace VetCabinet\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('VetCabinetFrontendBundle::welcome.html.twig');
    }
}
