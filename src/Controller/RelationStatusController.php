<?php

namespace App\Controller;

use App\Representation\Pagination;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;

/**
 * Class RelationStatusController
 * @package App\Controller
 * @SWG\Tag(name="RelationStatus")
 */
class RelationStatusController extends DefaultController
{
    protected $entity = 'App:RelationStatus';
    protected $namespaceEntity = 'App\Entity\RelationStatus';
    protected $namespaceType = 'App\Form\RelationStatusType';

    /**
     * Retrieve all data from one table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the {limit} first relationStatus",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Doc\Model(type="App\Entity\RelationStatus", groups={"all", "relationStatus"}))
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"all", "relationStatus"})
     *
     *  \|/  PAGINATION \|/
     *
     * @Rest\QueryParam(
     *  name="page",
     *  requirements="\d+",
     *  default=1,
     *  description="Paging start index(depends on the limit)"
     * )
     * @Rest\QueryParam(
     *  name="limit",
     *  requirements="\d+",
     *  default=25,
     *  description="Number of items to display. affects pagination"
     * )
     *
     * @Rest\Get(path="/relationStatus", name="GET_RelationStatuses")
     *
     * @param ParamFetcher $paramFetcher
     * @return Pagination
     */
    public function getRelationStatuses(ParamFetcher $paramFetcher)
    {
        return $this->getAll($paramFetcher);
    }

    /**
     * Retrieve one resource from the table
     *
     * @SWG\Response(response=200, description="return the RelationStatus")
     *
     * @Rest\View(serializerGroups={"all", "relationStatus"})
     * @Rest\Get(path="/relationStatus/{id}", name="GET_RelationStatus")
     *
     * @param $id
     * @return object|null
     */
    public function getRelationStatus($id)
    {
        return $this->getOne($id);
    }
}
