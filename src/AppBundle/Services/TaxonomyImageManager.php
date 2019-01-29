<?php

namespace AppBundle\Services;

use AppBundle\Entity\TaxonomyImage;
use AppBundle\Entity\TaxonomyItem;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TaxonomyImageManager
{
    protected $taxonomyImageRepository;

    public function __construct(EntityRepository $taxonomyImageRepository)
    {
        $this->taxonomyImageRepository = $taxonomyImageRepository;
    }

    public function getImages(TaxonomyItem $item, $slug)
    {
        return array_filter($item->getImages()->toArray(), function($image) use ($slug) {
            return $image->getSlug() == $slug;
        });
    }

    public function addImage(TaxonomyItem $item, $slug, UploadedFile $file)
    {
        $image = new TaxonomyImage();
        $image->setTaxonomyItem($item);
        $image->setSlug($slug);
        $image->setPath($this->uploadImage($file));

        $item->getImages()->add($image);

        return $image;
    }

    public function uploadImage(UploadedFile $file)
    {
        $name = sprintf('%s.%s', substr(sha1(rand()), 0, 10), $file->guessExtension());
        $file->move('uploads/taxonomy', $name);

        return $name;
    }
}
