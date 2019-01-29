<?php

namespace AdminBundle\Forms;

use AppBundle\Entity\TaxonomyCategory;
use AppBundle\Entity\TaxonomyCategoryField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxonomyItemSearchForm extends AbstractType
{
    protected $taxonomyCategory;

    public function setTaxonomyCategory(TaxonomyCategory $taxonomyCategory)
    {
        $this->taxonomyCategory = $taxonomyCategory;
        return $this;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'taxonomyItemSearchForm.title'])
        ;
    }    


    public function getName()
    {
        return 'form_taxonomy_item_search';
    }
}

