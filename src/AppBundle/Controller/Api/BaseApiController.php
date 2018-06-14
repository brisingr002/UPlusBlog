<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 14/06/2018
 * Time: 15:15
 */

namespace AppBundle\Controller\Api;


use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class BaseApiController extends BaseController
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serializedJsonResponse($data, $serializerGroups = array(), $message = "") {

        $encapsulatedData = array(
            'status' => 'success',
            'payload' => $data,
            'message' => $message
        );

        $json = $this->serializer->serialize($encapsulatedData , 'json', array('groups' => $serializerGroups));

        return new JsonResponse($json, 200, array(), true);
    }

    public function errorJsonResponse($message, $statusCode = Response::HTTP_BAD_REQUEST) {
        return new JsonResponse(array(
            'status' => 'error',
            'message' => $message
        ), $statusCode);
    }
}