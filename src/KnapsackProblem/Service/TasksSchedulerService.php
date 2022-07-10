<?php

namespace App\KnapsackProblem\Service;

use Writ3it\LibAlgo\KnapsackProblem\Algorithm\DynamicKnapsackSolver;
use Doctrine\Common\Collections\Collection;
use App\KnapsackProblem\Entity\Task;

class TasksSchedulerService implements TasksSchedulerServiceInterface
{

    /**
     * {@inheritDoc}
     */
    public function scheduleTasks(Collection $tasks, Collection $months):void
    {
        $solver = new DynamicKnapsackSolver();
        
        foreach ($months as $month) {
            $solver->solve($tasks->toArray(), $month);
            $tasks = $tasks->filter(fn (Task $task) => !$task->isScheduled());
        }
    }
}
