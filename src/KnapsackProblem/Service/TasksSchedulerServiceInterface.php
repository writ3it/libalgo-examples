<?php

namespace App\KnapsackProblem\Service;

use App\KnapsackProblem\Entity\Task;
use App\KnapsackProblem\Entity\WorkingMonth;
use Doctrine\Common\Collections\Collection;


interface TasksSchedulerServiceInterface
{
    /**
     * Returns task in any order that can be scheduled. 
     * @param Collection<mixed,Task> $tasks
     * @param Collection<mixed,WorkingMonth> $months
     */
    public function scheduleTasks(Collection $tasks, Collection $months):void;
}
