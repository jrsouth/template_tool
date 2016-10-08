<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Field
 *
 * @ORM\Table(name="fields", indexes={@ORM\Index(name="template_id", columns={"template_id"}), @ORM\Index(name="font_id", columns={"font_id"})})
 * @ORM\Entity
 */
class Field
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
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    private $type = 'normal';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="default_text", type="text", length=65535, nullable=false)
     */
    private $defaultText;

    /**
     * @var boolean
     *
     * @ORM\Column(name="force_uppercase", type="boolean", nullable=false)
     */
    private $forceUppercase = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="character_limit", type="integer", nullable=true)
     */
    private $characterLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="font_id", type="integer", nullable=false)
     */
    private $fontId;

    /**
     * @var float
     *
     * @ORM\Column(name="font_size", type="float", precision=10, scale=0, nullable=true)
     */
    private $fontSize;

    /**
     * @var integer
     *
     * @ORM\Column(name="colour_id", type="integer", nullable=false)
     */
    private $colourId = '1';

    /**
     * @var float
     *
     * @ORM\Column(name="x_position", type="float", precision=10, scale=0, nullable=true)
     */
    private $xPosition;

    /**
     * @var float
     *
     * @ORM\Column(name="y_position", type="float", precision=10, scale=0, nullable=true)
     */
    private $yPosition;

    /**
     * @var float
     *
     * @ORM\Column(name="wrap_width", type="float", precision=10, scale=0, nullable=true)
     */
    private $wrapWidth;

    /**
     * @var float
     *
     * @ORM\Column(name="leading", type="float", precision=10, scale=0, nullable=false)
     */
    private $leading = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    private $parent;

    /**
     * @var integer
     *
     * @ORM\Column(name="page", type="integer", nullable=true)
     */
    private $page = '1';

    /**
     * @var float
     *
     * @ORM\Column(name="tracking", type="float", precision=10, scale=0, nullable=true)
     */
    private $tracking = '0';

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
     * @return Fields
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
     * Set type
     *
     * @param string $type
     *
     * @return Fields
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Fields
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
     * Set defaultText
     *
     * @param string $defaultText
     *
     * @return Fields
     */
    public function setDefaultText($defaultText)
    {
        $this->defaultText = $defaultText;

        return $this;
    }

    /**
     * Get defaultText
     *
     * @return string
     */
    public function getDefaultText()
    {
        return $this->defaultText;
    }

    /**
     * Set forceUppercase
     *
     * @param boolean $forceUppercase
     *
     * @return Fields
     */
    public function setForceUppercase($forceUppercase)
    {
        $this->forceUppercase = $forceUppercase;

        return $this;
    }

    /**
     * Get forceUppercase
     *
     * @return boolean
     */
    public function getForceUppercase()
    {
        return $this->forceUppercase;
    }

    /**
     * Set characterLimit
     *
     * @param integer $characterLimit
     *
     * @return Fields
     */
    public function setCharacterLimit($characterLimit)
    {
        $this->characterLimit = $characterLimit;

        return $this;
    }

    /**
     * Get characterLimit
     *
     * @return integer
     */
    public function getCharacterLimit()
    {
        return $this->characterLimit;
    }

    /**
     * Set fontId
     *
     * @param integer $fontId
     *
     * @return Fields
     */
    public function setFontId($fontId)
    {
        $this->fontId = $fontId;

        return $this;
    }

    /**
     * Get fontId
     *
     * @return integer
     */
    public function getFontId()
    {
        return $this->fontId;
    }

    /**
     * Set fontSize
     *
     * @param float $fontSize
     *
     * @return Fields
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;

        return $this;
    }

    /**
     * Get fontSize
     *
     * @return float
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * Set colourId
     *
     * @param integer $colourId
     *
     * @return Fields
     */
    public function setColourId($colourId)
    {
        $this->colourId = $colourId;

        return $this;
    }

    /**
     * Get colourId
     *
     * @return integer
     */
    public function getColourId()
    {
        return $this->colourId;
    }

    /**
     * Set xPosition
     *
     * @param float $xPosition
     *
     * @return Fields
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
     * @return Fields
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
     * Set wrapWidth
     *
     * @param float $wrapWidth
     *
     * @return Fields
     */
    public function setWrapWidth($wrapWidth)
    {
        $this->wrapWidth = $wrapWidth;

        return $this;
    }

    /**
     * Get wrapWidth
     *
     * @return float
     */
    public function getWrapWidth()
    {
        return $this->wrapWidth;
    }

    /**
     * Set leading
     *
     * @param float $leading
     *
     * @return Fields
     */
    public function setLeading($leading)
    {
        $this->leading = $leading;

        return $this;
    }

    /**
     * Get leading
     *
     * @return float
     */
    public function getLeading()
    {
        return $this->leading;
    }

    /**
     * Set parent
     *
     * @param integer $parent
     *
     * @return Fields
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return integer
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set page
     *
     * @param integer $page
     *
     * @return Fields
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
     * Set tracking
     *
     * @param float $tracking
     *
     * @return Fields
     */
    public function setTracking($tracking)
    {
        $this->tracking = $tracking;

        return $this;
    }

    /**
     * Get tracking
     *
     * @return float
     */
    public function getTracking()
    {
        return $this->tracking;
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
