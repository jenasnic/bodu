<?php

namespace Bodu\SkinBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SkinRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SkinRepository extends EntityRepository
{
    public function isUsedBySection($skinId)
    {
        try
        {
            $query = $this->_em->createQuery('SELECT COUNT(se) FROM BoduSectionBundle:Section se JOIN se.skin sk WHERE sk.id = :skinId');
            $query->setParameter('skinId', $skinId);

            return $query->getSingleScalarResult();
        }
        catch (Exception $ex) {
            return '-1';
        }
    }
}
