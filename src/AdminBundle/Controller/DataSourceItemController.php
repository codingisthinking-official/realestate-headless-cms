<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\DataSource;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Services\TaxonomyManager;
use AdminBundle\Forms\TaxonomyItemSearchForm;
use AppBundle\Entity\TaxonomyItem;
use AppBundle\Entity\TaxonomyCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DataSourceItemController extends Controller
{
    /**
     * @Route("/datasource/{id}/", name="admin.datasource")
     * @Template()
     */
    public function datasourceAction(Request $request, DataSource $dataSource)
    {
        $this->get('admin.breadcrumb')->attach(
            $dataSource->getLabel(), $this->generateUrl('admin.datasource', ['id' => $dataSource->getId()])
        );

        $currentPage = $request->query->getInt('page', 1);

        $buzz = new Client();
        $response = $buzz->get(str_replace('%page%', $currentPage, $dataSource->getUrl()));

        $error = false;
        if ($response->getStatusCode() > 400) {
            $error = true;
        }

        $responseData = $error ? [] : json_decode($response->getBody(), true);
        $responseData['data'] = $responseData['posts'];

        $paginator = $this->get('knp_paginator');

        if (!$error) {
            $pager = $paginator->paginate(
                range(0, $responseData['total_items']),
                $currentPage,
                15
            );
        }

        return [
            'pager' => $pager,
            'datasource' => $dataSource,
            'error' => $error,
            'response' => $responseData,
        ];
    }
}