<?php
namespace AppBundle\Utils;

use Doctrine\ORM\EntityManager;

class DBUtils
{
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }
    
    
    
    public function getColors()
    {
        $repo = $this->em->getRepository('AppBundle:Color');
        $result = $repo->findAll();

        return($result);
    }
    
    public function getColor($id)
    {
        $repo = $this->em->getRepository('AppBundle:Color');
        $result = $repo->find($id);
        
        return($result);
    }
    
    public function getFonts()
    {
        $repo = $this->em->getRepository('AppBundle:Font');
        $result = $repo->findAll();

        return($result);
    }
    
    public function getFont($id)
    {
        $repo = $this->em->getRepository('AppBundle:Font');
        $result = $repo->find($id);
        
        return($result);
    }
    
    public function getTemplates($active = true, $tags = '')
    {
        $repo = $this->em->getRepository('AppBundle:Template');
        $result = $repo->findAll();
 
        return($result);
    }
    
    public function getTemplate($id)
    {
        $repo = $this->em->getRepository('AppBundle:Template');
        $result = $repo->find($id);
        
        return($result);
    }
}
