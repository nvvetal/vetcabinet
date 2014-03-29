<?php

namespace VetCabinet\AsseticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VetCabinetAsseticBundle:Default:index.html.twig', array('name' => $name));
    }
}
