<?php

namespace App\KnapsackProblem\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\KnapsackProblem\Service\TaskRepositoryInterface;
use App\KnapsackProblem\Entity\Task;

class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }
    /**
     * {@inheritDoc}
     */
    public function findAllNotScheduled(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t FROM App\KnapsackProblem\Entity\Task t WHERE t.scheduled = false'
        );
        return $query->getResult();
    }
}
