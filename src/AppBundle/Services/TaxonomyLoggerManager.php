<?php

namespace AppBundle\Services;

use Doctrine\ORM\PersistentCollection;
use AppBundle\Entity\User;
use AppBundle\Entity\TaxonomyCategory;
use AppBundle\Entity\TaxonomyLogger;
use AppBundle\Entity\TaxonomyField;
use AppBundle\Entity\TaxonomyItem;
use AppBundle\Interfaces\TaxonomyFieldInterface;
use AppBundle\Interfaces\TaxonomyItemInterface;
use AppBundle\ObjectValue\TaxonomyField as ValueObjectTaxonomyField;
use AppBundle\ObjectValue\TaxonomyItem as ValueObjectTaxonomyItem;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\SecurityContextInterface;

class TaxonomyLoggerManager
{
    protected $taxonomyLogger;
    protected $securityContext;

    public function __construct(
        EntityRepository $taxonomyLogger, SecurityContextInterface $securityContext
    ) 
    {
        $this->taxonomyLogger = $taxonomyLogger;
        $this->securityContext = $securityContext;
    }

    public function logDelete(TaxonomyItem $taxonomyItem)
    {
        return $this->log(
            $this->securityContext->getToken()->getUser(),
            $taxonomyItem,
            'delete'
        );
    }

    public function logUpdate(TaxonomyItem $taxonomyItem)
    {
        return $this->log(
            $this->securityContext->getToken()->getUser(),
            $taxonomyItem,
            'update'
        );
    }

    public function logCreate(TaxonomyItem $taxonomyItem)
    {
        return $this->log(
            $this->securityContext->getToken()->getUser(),
            $taxonomyItem,
            'create'
        );
    }

    protected function log(User $user, TaxonomyItem $item, $type)
    {
        $logger = new TaxonomyLogger();
        $logger->setUser($user);
        $logger->setTaxonomyItem($item);
        $logger->setTaxonomyCategory($item->getTaxonomyCategory());
        $logger->setType($type);

        return $logger;
    }
}
