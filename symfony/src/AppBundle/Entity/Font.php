<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Font
 *
 * @ORM\Table(name="fonts")
 * @ORM\Entity
 */
class Font
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="font_file", type="string", length=64, nullable=false)
     */
    private $fontFile;

    /**
     * @var string
     *
     * @ORM\Column(name="original_file", type="string", length=64, nullable=false)
     */
    private $originalFile;

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
     * @return Fonts
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
     * Set fontFile
     *
     * @param string $fontFile
     *
     * @return Fonts
     */
    public function setFontFile($fontFile)
    {
        $this->fontFile = $fontFile;

        return $this;
    }

    /**
     * Get fontFile
     *
     * @return string
     */
    public function getFontFile()
    {
        return $this->fontFile;
    }

    /**
     * Set originalFile
     *
     * @param string $originalFile
     *
     * @return Fonts
     */
    public function setOriginalFile($originalFile)
    {
        $this->originalFile = $originalFile;

        return $this;
    }

    /**
     * Get originalFile
     *
     * @return string
     */
    public function getOriginalFile()
    {
        return $this->originalFile;
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
