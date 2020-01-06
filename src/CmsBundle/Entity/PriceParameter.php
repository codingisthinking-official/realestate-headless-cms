<?php

namespace CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="PriceParameter")
 */
class PriceParameter
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
    protected $tooltip;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    protected $options;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $groupName;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $fieldRequired;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $yearCost = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $monthlyCost = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $autocomplete;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $displayAverage;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }
    
    public function getYearCost()
    {
        return $this->yearCost;
    }

    public function getOptions()
    {
        return $this->options;
    }
    
    public function getGroupName()
    {
        return $this->groupName;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getTooltip()
    {
        return $this->tooltip;
    }

    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getFieldRequired()
    {
        return $this->fieldRequired;
    }

    public function setFieldRequired($fieldRequired)
    {
        $this->fieldRequired = $fieldRequired;
    }

    public function getAutocomplete()
    {
        return $this->autocomplete;
    }

    public function setAutocomplete($autocomplete)
    {
        $this->autocomplete = $autocomplete;
    }

    public function setDisplayAverage($displayAverage)
    {
        $this->displayAverage = $displayAverage;
    }

    public function getDisplayAverage()
    {
        return $this->displayAverage;
    }

    public function getMonthlyCost()
    {
        return $this->monthlyCost;
    }

    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

    public function setYearCost($yearCost)
    {
        $this->yearCost = $yearCost;
    }

    public function setMonthlyCost($monthlyCost)
    {
        $this->monthlyCost = $monthlyCost;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'group_name' => $this->getGroupName(),
            'year_cost' => $this->getYearCost(),
            'monthly_cost' => $this->getMonthlyCost(),
            'title' => $this->getTitle(),
            'slug' => $this->getSlug(),
            'tooltip' => $this->getTooltip(),
            'type' => $this->getType(),
            'position' => $this->getPosition(),
            'options' => $this->getOptions(),
            'field_required' => $this->getFieldRequired(),
            'autocomplete' => $this->getAutocomplete(),
            'display_average' => $this->getDisplayAverage(),
        ];
    }
}
