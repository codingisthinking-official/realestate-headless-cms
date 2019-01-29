<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="TaxonomyCategory")
 */
class TaxonomyCategory
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
     * @ORM\OneToMany(targetEntity="TaxonomyCategoryField", mappedBy="taxonomyCategory")
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

    public function getFields()
    {
        return $this->fields;
    }

    public function getVisibleFields()
    {
        return array_filter($this->getFields()->toArray(), function($field) {
            return $field->isVisible();
        });
    }

    public function getSearchableFields()
    {
        return array_filter($this->getFields()->toArray(), function($field) {
            return $field->isSearchable();
        });
    }
}
