<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="DataSource")
 */
class DataSource
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $label;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $icon;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    protected $url;

    /**
     * @ORM\Column(type="string", length=1024)
     * @Assert\NotBlank()
     */
    protected $fields;

    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getFieldsSplitted()
    {
        return explode(',', $this->fields);
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }
}
