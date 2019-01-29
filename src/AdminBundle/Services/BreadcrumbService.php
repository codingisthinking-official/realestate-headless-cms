<?php

namespace AdminBundle\Services;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BreadcrumbService
{
    protected $paths = [];

    protected $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->attach('Admin', $this->urlGenerator->generate('admin.dashboard'));
    }

    public function attach($title, $path)
    {
        $this->paths[] = [
            'title' => $title,
            'path' => $path
        ];

        return $this;
    }

    public function getPaths()
    {
        return $this->paths;
    }
}

