<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="TaxonomyCategoryField")
 */
class TaxonomyCategoryField
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
    protected $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $required;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $searchable = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $visible = false;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    protected $settings;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyCategory", inversedBy="fields")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $taxonomyCategory;

    public function getId()
    {
        return $this->id;
    }

    public function isRequired()
    {
        return $this->required;
    }

    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
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

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function setTaxonomyCategory(TaxonomyCategory $taxonomyCategory)
    {
        $this->taxonomyCategory = $taxonomyCategory;

        return $this;
    }

    public function getTaxonomyCategory()
    {
        return $this->taxonomyCategory;
    }

    public function getKeyType()
    {
        $types = [
            'taxonomyCategoryFieldsForm.type.string',
            'taxonomyCategoryFieldsForm.type.text',
            'taxonomyCategoryFieldsForm.type.image',
            'taxonomyCategoryFieldsForm.type.gallery',
            'taxonomyCategoryFieldsForm.type.reference',
            'taxonomyCategoryFieldsForm.type.dropdown',
            'taxonomyCategoryFieldsForm.type.yesno',
            'taxonomyCategoryFieldsForm.type.auto',
            'taxonomyCategoryFieldsForm.type.reference_array',
            'taxonomyCategoryFieldsForm.type.keyvalue',
        ];

        return $types[$this->getType()];
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function getSearchable()
    {
        return $this->searchable;
    }

    public function isSearchable()
    {
        return $this->getSearchable();
    }

    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    public function getVisible()
    {
        return $this->visible;
    }

    public function isVisible()
    {
        return $this->visible;
    }
}
