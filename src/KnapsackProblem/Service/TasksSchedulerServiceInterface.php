<?php

namespace App\KnapsackProblem\Service;

use App\KnapsackProblem\Entity\Task;
use App\KnapsackProblem\Entity\WorkingMonth;
use Doctrine\Common\Collections\Collection;


interface TasksSchedulerServiceInterface
{
    /**
     * Returns task in any order that can be scheduled. 
     * @param Collection<Task> $tasks
     * @param Collection<WorkingMonth> $months
     */
    public function scheduleTasks(Collection $tasks, Collection $months):void;
}
