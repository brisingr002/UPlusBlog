<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 14/06/2018
 * Time: 14:11
 */

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class Tag
 * @package AppBundle\Entity
 * @ORM\Table(name="tags", uniqueConstraints={ @ORM\UniqueConstraint(name="name_unique", columns={"name"}) }))
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PostRepository")
 */
class Tag
{

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"preview", "detail"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     * @Groups({"preview", "detail"})
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection|Post[]
     * @ORM\ManyToMany(targetEntity="Post", inversedBy="tags", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="posts_tags",
     *     joinColumns={ @ORM\JoinColumn(name="tag_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="post_id", referencedColumnName="id") }
     * )
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $posts;


    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->name;
    }

    // MARK: Getters and Setters

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return \Doctrine\Common\Collections\Collection|Post[]
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection|Post[] $posts
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * @param Post $post
     */
    public function addPost(Post $post)
    {
        if ($this->posts->contains($post)) {
            return;
        }

        $this->posts->add($post);
        $post->addTag($this);
    }
    /**
     * @param Post $post
     */
    public function removePost(Post $post)
    {
        if (!$this->posts->contains($post)) {
            return;
        }

        $this->posts->removeElement($post);
        $post->removeTag($this);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }
}