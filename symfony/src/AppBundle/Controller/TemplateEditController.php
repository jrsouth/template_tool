<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Template;

class TemplateEditController extends Controller
{
    /**
     * @Route("/edit/{id}", name="edit-template", requirements={"id": "\d+"})
     */
    public function numberAction(Template $t)
    {
        $number = mt_rand(0, 100);

        return $this->render('edit/templateEditor.html.twig', array(
            'template' => $t,
        ));

    }
}

