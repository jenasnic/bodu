<?php

namespace Bodu\HomeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BannerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BannerRepository extends EntityRepository
{
    public function getMaxRank()
    {
        try
        {
            $query = $this->_em->createQuery('SELECT MAX(b.rank) FROM BoduHomeBundle:Banner b');
            return $query->getSingleScalarResult();
        }
        catch (Exception $ex) {
            return '1';
        }
    }
}
