<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InstallController extends Controller
{
    /**
     * @Route("/install")
     */
    public function numberAction()
    {
        $number = mt_rand(0, 100);

        return $this->render('install/installer.html.twig', array(
            'number' => $number,
        ));

    }
}

