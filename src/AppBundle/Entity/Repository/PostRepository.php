<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 14/06/2018
 * Time: 14:18
 */

namespace AppBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    /**
     * @param bool $sortByDate
     * @return array
     */
    public function findAllActive($sortByDate = true) {
        return $this->findBy(array("active" => true), $sortByDate ? array('date' => 'DESC') : array());
    }

    public function getOnlyActiveQuery() {
        return $this
            ->createQueryBuilder('p')
            ->where('p.active = 1')
            ->orderBy('p.date', 'DESC');
    }


}