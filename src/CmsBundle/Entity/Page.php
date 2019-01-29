<?php

namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Page")
 */
class Page
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $language;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $photo;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $shortDescription;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $longDescription;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $metaDescription;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    public function getLongDescription()
    {
        return $this->longDescription;
    }

    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    public function toJson()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'slug' => $this->getSlug(),
            'language' => $this->getLanguage(),
            'photo' => $this->getPhoto(),
            'short_description' => $this->getShortDescription(),
            'long_description' => $this->getLongDescription(),
            'meta_description' => $this->getMetaDescription(),
        ];
    }
}
