<?php

namespace App\KnapsackProblem\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\KnapsackProblem\Entity\WorkingMonth;
use App\KnapsackProblem\Service\WorkingMonthRepositoryInterface;

class WorkingMonthRepository extends ServiceEntityRepository implements WorkingMonthRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkingMonth::class);
    }

    /**
     * {@inheritDoc}
     */
    public function findAllNotScheduled(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT m FROM App\KnapsackProblem\Entity\WorkingMonth m WHERE m.scheduled = false ORDER BY m.month ASC'
        );
        return $query->getResult();
    }
    /**
     * {@inheritDoc}
     */
    public function flush(): void
    {
        $em = $this->getEntityManager();
        $em->flush();
    }
}
