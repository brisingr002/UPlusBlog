<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post
 * @package AppBundle\Entity
 * @ORM\Table(name="posts", uniqueConstraints={ @ORM\UniqueConstraint(name="slug_unique", columns={"slug"}) })
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"slug"},
 *     errorPath="slug",
 *     message="This slug is already in use."
 * )
 */
class Post
{

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"preview", "detail"})
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default": 1})
     */
    private $active = true;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", nullable=false, length=150)
     * @Groups({"preview", "detail"})
     *
     * @Assert\Length(max = 150)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true, length=200)
     * @Groups({"preview", "detail"})
     */
    private $slug;

    /**
     * @var string
     * @ORM\Column(name="text", type="text", nullable=false)
     * @Groups({"detail"})
     *
     * @Assert\NotBlank()
     */
    private $text;

    /**
     * @var DateTime
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @Groups({"preview", "detail"})
     */
    private $date;

    /**
     * @var integer
     * @ORM\Column(name="number_of_views", type="integer", nullable=false, options={"default": 0})
     * @Groups({"preview", "detail"})
     */
    private $numberOfViews = 0;

    /**
     * @var \Doctrine\Common\Collections\Collection|Tag[]
     * @ORM\ManyToMany(targetEntity="Tag", mappedBy="posts", cascade={"persist"})
     * @Groups({"detail"})
     */
    private $tags;



    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->date = new \DateTime();
    }

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist() {
        $this->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->slug)));

    }

    /**
     * @ORM\PreUpdate()
     */
    public function onPreUpdate() {
        $this->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->slug)));
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
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param int $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfViews()
    {
        return $this->numberOfViews;
    }

    /**
     * @param int $numberOfViews
     * @return $this
     */
    public function setNumberOfViews($numberOfViews)
    {
        $this->numberOfViews = $numberOfViews;
        return $this;
    }

    /**
     * @param int $byValue
     * @return $this
     */
    public function incrementNumberOfViews($byValue = 1) {
        $this->numberOfViews += $byValue;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection|Tag[] $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        if ($this->tags->contains($tag)) {
            return;
        }

        $this->tags->add($tag);
        $tag->addPost($this);
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            return;
        }

        $this->tags->removeElement($tag);
        $tag->removePost($this);
    }

    public function getTagsJoined() {
        return implode(' | ', $this->tags->toArray());
    }
}