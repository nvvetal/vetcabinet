<?php

namespace VetCabinet\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VetCabinetApiBundle:Default:index.html.twig', array('name' => $name));
    }
}
