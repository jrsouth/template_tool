<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Template;

class EditController extends Controller
{
    /**
     * @Route("/edit/{id}", name="edit-template", requirements={"id": "\d+"})
     */
    public function templateEditAction(Template $t)
    {
    
        return $this->render('edit/templateEditor.html.twig', array(
            'template' => $t,
        ));

    }
    
    /**
     * @Route("/edit/new", name="edit-new-template")
     */
    public function newTemplateAction()
    {

        return $this->render('edit/templateCreator.html.twig');

    }
}

