<?php

namespace AppBundle\Services;

use AppBundle\Entity\TaxonomyCategory;
use AppBundle\Interfaces\FileUploadInterface;
use Doctrine\ORM\EntityRepository;
use Pecee\Http\Dropbox\Dropbox;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\SecurityContextInterface;

class TaxonomyManager
{
    const TYPE_FILE = 7;
    const TYPE_IMAGE = 2;

    const PATH_UPLOAD = 'vendors/';

    protected $taxonomyCategory;
    protected $securityContext;

    public function __construct(EntityRepository $taxonomyCategory, SecurityContextInterface $securityContext)
    {
        $this->taxonomyCategory = $taxonomyCategory;
        $this->securityContext = $securityContext;
    }

    public function uploadFiles(TaxonomyCategory $taxonomyCategory, $object)
    {
        foreach ($taxonomyCategory->getFields() as $field) {
            if ($field->getType() == self::TYPE_FILE || $field->getType() == self::TYPE_IMAGE) {
                $key = 'get' . $field->getSlug();
                $file = $object->{$key}();

                if ($file instanceof UploadedFile) {
                    $path = $this->buildName($file);

//                    $result = $this->dropboxApi->upload(file_get_contents($file->getRealPath()), '/' . $path);

//                    var_dump($result);die;

                    $file->move(self::PATH_UPLOAD, $path);

                    $setter = 'set' . $field->getSlug();

                    $object->{$setter}($path);
                }
            }
        }
    }

    public function getTaxonomyCategories()
    {
        return $this->taxonomyCategory->findAll();
    }

    protected function buildName(UploadedFile $file, $prefix = null)
    {
        $name = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());

        if ($prefix) {
            $path = $name. '-' . $prefix;
        } else {
            $path = $name;
        }

        $fullPath = self::PATH_UPLOAD . $path . '.' . $file->getClientOriginalExtension();

        if (file_exists($fullPath)) {
            return $this->buildName($file, ++$prefix);
        }

        return $fullPath;
    }
}

