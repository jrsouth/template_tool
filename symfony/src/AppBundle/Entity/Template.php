<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Template
 *
 * @ORM\Table(name="templates")
 * @ORM\Entity
 */
class Template
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name = "New Template";

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=256, nullable=true)
     */
    private $tags;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf_file", type="string", length=128, nullable=false)
     */
    private $pdfFile;

    /**
     * @var string
     *
     * @ORM\Column(name="permissions", type="string", length=32, nullable=true)
     */
    private $permissions;

    /**
     * @var float
     *
     * @ORM\Column(name="bleed", type="float", precision=10, scale=0, nullable=true)
     */
    private $bleed = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="owner", type="string", length=128, nullable=true)
     */
    private $owner;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="pagecount", type="integer", nullable=true)
     */
    private $pageCount = '1';

    /**
     * @var float
     *
     * @ORM\Column(name="height", type="float", precision=10, scale=0, nullable=true)
     */
    private $height = '297';

    /**
     * @var float
     *
     * @ORM\Column(name="width", type="float", precision=10, scale=0, nullable=true)
     */
    private $width = '210';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set name
     *
     * @param string $name
     *
     * @return Templates
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return Templates
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set pdfFile
     *
     * @param string $pdfFile
     *
     * @return Templates
     */
    public function setPdfFile($pdfFile)
    {
        $this->pdfFile = $pdfFile;

        return $this;
    }

    /**
     * Get pdfFile
     *
     * @return string
     */
    public function getPdfFile()
    {
        return $this->pdfFile;
    }

    /**
     * Set permissions
     *
     * @param string $permissions
     *
     * @return Templates
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * Get permissions
     *
     * @return string
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Set bleed
     *
     * @param float $bleed
     *
     * @return Templates
     */
    public function setBleed($bleed)
    {
        $this->bleed = $bleed;

        return $this;
    }

    /**
     * Get bleed
     *
     * @return float
     */
    public function getBleed()
    {
        return $this->bleed;
    }

    /**
     * Set owner
     *
     * @param string $owner
     *
     * @return Templates
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Templates
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set pagecount
     *
     * @param integer $pagecount
     *
     * @return Templates
     */
    public function setPageCount($pageCount)
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    /**
     * Get pagecount
     *
     * @return integer
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * Set height
     *
     * @param float $height
     *
     * @return Templates
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set width
     *
     * @param float $width
     *
     * @return Templates
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
