<?php

namespace AppBundle\DataTransformer;

use AppBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * used to give a "form value"
     */
    public function transform($tags)
    {
        if ($tags == null) {
            return null;
        }

        return implode(", ", $tags->toArray());
    }

    /**
     * used to give "a db value"
     */
    public function reverseTransform($names)
    {
        $collection = new ArrayCollection();

        if (!$names) {
            return $collection;
        }

        $tagsRepo = $this->em->getRepository(Tag::class);
        $tagsArr = explode(",", preg_replace('/\s+/', '', $names));

        foreach($tagsArr as $tagName) {
            $tag = $tagsRepo->findOneBy(array('name' => $tagName));

            if(empty($tag)) {
                $tag = new Tag();
                $tag->setName($tagName);

                $this->em->persist($tag);
                $this->em->flush();
            }

            $collection->add($tag);
        }

        return $collection;
    }
}