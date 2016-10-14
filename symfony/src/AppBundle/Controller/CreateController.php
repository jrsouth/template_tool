<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CreateController extends Controller
{
    /**
     * @Route("/create", name="select-template")
     */
    public function SelectTemplateAction($id = 0, $downloadName = null)
    {
            $templates = $this->get('dbutils')->getTemplates();
            return($this->render('create/select.html.twig', ['templates' => $templates]));
    }
    
    /**
     * @Route("/create/{id}/{downloadName}", name="build-template", requirements={"id": "\d+"})
     */
    public function numberAction($id = 0, $downloadName = null)
    {
            $template = $this->get('dbutils')->getTemplate($id);
            return($this->render('create/build.html.twig', ['template' => $template]));
    }
}

