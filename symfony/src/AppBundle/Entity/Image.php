<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="images", indexes={@ORM\Index(name="template_id", columns={"template_id"})})
 * @ORM\Entity
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(name="template_id", type="integer", nullable=false)
     */
    private $templateId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="x_position", type="float", precision=10, scale=0, nullable=false)
     */
    private $xPosition;

    /**
     * @var float
     *
     * @ORM\Column(name="y_position", type="float", precision=10, scale=0, nullable=false)
     */
    private $yPosition;

    /**
     * @var float
     *
     * @ORM\Column(name="width", type="float", precision=10, scale=0, nullable=false)
     */
    private $width;

    /**
     * @var float
     *
     * @ORM\Column(name="height", type="float", precision=10, scale=0, nullable=false)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="alignment", type="string", length=12, nullable=false)
     */
    private $alignment;

    /**
     * @var integer
     *
     * @ORM\Column(name="page", type="integer", nullable=true)
     */
    private $page = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="hide", type="boolean", nullable=true)
     */
    private $hide;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set templateId
     *
     * @param integer $templateId
     *
     * @return Images
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;

        return $this;
    }

    /**
     * Get templateId
     *
     * @return integer
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Images
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
     * Set xPosition
     *
     * @param float $xPosition
     *
     * @return Images
     */
    public function setXPosition($xPosition)
    {
        $this->xPosition = $xPosition;

        return $this;
    }

    /**
     * Get xPosition
     *
     * @return float
     */
    public function getXPosition()
    {
        return $this->xPosition;
    }

    /**
     * Set yPosition
     *
     * @param float $yPosition
     *
     * @return Images
     */
    public function setYPosition($yPosition)
    {
        $this->yPosition = $yPosition;

        return $this;
    }

    /**
     * Get yPosition
     *
     * @return float
     */
    public function getYPosition()
    {
        return $this->yPosition;
    }

    /**
     * Set width
     *
     * @param float $width
     *
     * @return Images
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
     * Set height
     *
     * @param float $height
     *
     * @return Images
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
     * Set alignment
     *
     * @param string $alignment
     *
     * @return Images
     */
    public function setAlignment($alignment)
    {
        $this->alignment = $alignment;

        return $this;
    }

    /**
     * Get alignment
     *
     * @return string
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * Set page
     *
     * @param integer $page
     *
     * @return Images
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set hide
     *
     * @param boolean $hide
     *
     * @return Images
     */
    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * Get hide
     *
     * @return boolean
     */
    public function getHide()
    {
        return $this->hide;
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
