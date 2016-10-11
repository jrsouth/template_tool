<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CreateController extends Controller
{
    /**
     * @Route("/", name="create-template")
     * @Route("/create/{id}/{downloadName}", name="create-template", requirements={"id": "\d+"})
     */
    public function numberAction($id = 0, $downloadName = null)
    {

        if ($id === 0) {
            $colors = $this->get('dbutils')->getColors();
            return($this->render('default/index.html.twig', ['colors' => $colors]));
        } else {
            return($this->render('create/select.html.twig'));
        }

    }
}

