<?php

namespace Wallabag\AnnotationBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AnnotationRepository.
 */
class AnnotationRepository extends EntityRepository
{
    /**
     * Return a query builder to used by other getBuilderFor* method.
     *
     * @param int $userId
     *
     * @return QueryBuilder
     */
    private function getBuilderByUser($userId)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->andWhere('u.id = :userId')->setParameter('userId', $userId)
            ->orderBy('a.id', 'desc')
        ;
    }

    /**
     * Retrieves all annotations for a user.
     *
     * @param int $userId
     *
     * @return QueryBuilder
     */
    public function getBuilderForAllByUser($userId)
    {
        return $this
            ->getBuilderByUser($userId)
        ;
    }

    /**
     * Get annotation for this id.
     *
     * @param int $annotationId
     *
     * @return array
     */
    public function findAnnotationById($annotationId)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :annotationId')->setParameter('annotationId', $annotationId)
            ->getQuery()->getSingleResult()
        ;
    }

    /**
     * Find annotations for entry id.
     *
     * @param int $entryId
     * @param int $userId
     *
     * @return array
     */
    public function findAnnotationsByPageId($entryId, $userId)
    {
        return $this->createQueryBuilder('a')
            ->where('a.entry = :entryId')->setParameter('entryId', $entryId)
            ->andwhere('a.user = :userId')->setParameter('userId', $userId)
            ->getQuery()->getResult()
        ;
    }

    /**
     * Find last annotation for a given entry id. Used only for tests.
     *
     * @param int $entryId
     *
     * @return array
     */
    public function findLastAnnotationByPageId($entryId, $userId)
    {
        return $this->createQueryBuilder('a')
            ->where('a.entry = :entryId')->setParameter('entryId', $entryId)
            ->andwhere('a.user = :userId')->setParameter('userId', $userId)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Used only in test case to get the right annotation associated to the right user.
     *
     * @param string $username
     *
     * @return Annotation
     */
    public function findOneByUsername($username)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->where('u.username = :username')->setParameter('username', $username)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
