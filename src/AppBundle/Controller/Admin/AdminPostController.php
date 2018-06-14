<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 14/06/2018
 * Time: 14:45
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Post;
use AppBundle\Entity\Repository\PostRepository;
use AppBundle\Entity\Tag;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminPostController
 * @package AppBundle\Controller\Admin
 */
class AdminPostController extends BaseController
{
    /**
     * @Route("/", name="admin_posts")
     */
    public function indexAction() {

        /** @var PostRepository $postRepo */
        $postRepo = $this->getDoctrine()->getRepository(Post::class);

        $posts = $postRepo->findBy(array(), array('date' => 'desc'));

        return $this->render('@App/Admin/Post/index.html.twig', array(
            'posts' => $posts
        ));
    }

    /**
     * Creates a new Post entity.
     * @param Request $request
     * @return Response
     *
     * @Route("/new-post", name="admin_post_add")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('admin_post_edit', array('id' => $post->getId()));
        }

        return $this->render('@App/Admin/Post/add.html.twig', array(
            'post' => $post,
            'add_form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Post entity.
     * @param Request $request
     * @param Post $post
     * @return Response
     * @Route("/{id}", name="admin_post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post) {

        $editForm = $this->createForm(PostType::class, $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('admin_post_edit', array('id' => $post->getId()));
        }

        return $this->render('@App/Admin/Post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
        ));
    }
}