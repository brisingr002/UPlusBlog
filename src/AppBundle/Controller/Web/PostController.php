<?php

namespace AppBundle\Controller\Web;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Post;
use AppBundle\Entity\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController
 * @package AppBundle\Controller\Web
 * @Route("/")
 */
class PostController extends BaseController
{
    const ITEMS_PER_PAGE = 2;

    /**
     * @Route("/{currentPageNumber}", name="posts")
     */
    public function indexAction(Request $request, $currentPageNumber = 1)
    {
        /** @var PostRepository $postRepo */
        $postRepo = $this->getDoctrine()->getRepository(Post::class);

        $query = $postRepo->getOnlyActiveQuery();
        $paginator  = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        $totalItemCounts = count($paginator);
        $totalNumberOfPages = ceil($totalItemCounts / static::ITEMS_PER_PAGE);
        $offset = static::ITEMS_PER_PAGE * ($currentPageNumber - 1);

        $paginator->getQuery()->setFirstResult($offset)->setMaxResults(static::ITEMS_PER_PAGE);

        return $this->render('@App/Web/Post/index.html.twig', array(
            'posts' => $paginator,
            'totalNumberOfPages' => $totalNumberOfPages,
            'currentPageNumber' => $currentPageNumber
        ));
    }

    /**
     * @Route("/detail/{slug}", name="post_detail")
     * @param $slug
     * @return Response
     */
    public function detailAction($slug) {

        $manager = $this->getDoctrine()->getManager();

        /** @var Post $post */
        $post = $manager->getRepository(Post::class)->findOneBy(array('slug' => $slug, 'active' => true));

        if($post === null) {
            return $this->redirectToRoute('posts');
        }

        $post->incrementNumberOfViews();
        $manager->persist($post);
        $manager->flush();

        return $this->render('@App/Web/Post/detail.html.twig', array(
            'post' => $post
        ));
    }
}
