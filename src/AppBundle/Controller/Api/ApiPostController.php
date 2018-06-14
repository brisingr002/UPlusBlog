<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 14/06/2018
 * Time: 14:45
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Post;
use AppBundle\Entity\Repository\PostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiPostController
 * @package AppBundle\Controller\Api
 * @Route("/posts")
 */
class ApiPostController extends BaseApiController
{

    /**
     * @Route("/")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function indexAction() {

        /** @var PostRepository $postsRepo */
        $postsRepo = $this->getDoctrine()->getRepository(Post::class);
        $posts = $postsRepo->findAllActive();

        return $this->serializedJsonResponse($posts, array('preview'));
    }

    /**
     * @Route("/{id}")
     * @Method({"GET"})
     *
     * @param $id
     * @return JsonResponse
     */
    public function detailAction($id) {

        $manager = $this->getDoctrine()->getManager();

        /** @var Post $post */
        $post = $manager->getRepository(Post::class)->find($id);

        if ($post === null) {
            return $this->errorJsonResponse("ID {$id} not found", Response::HTTP_NOT_FOUND);
        }

        $post->incrementNumberOfViews();
        $manager->persist($post);
        $manager->flush();

        return $this->serializedJsonResponse($post, array('detail'));
    }
}