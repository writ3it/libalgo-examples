<?php

namespace App\KnapsackProblem\Service;
use Writ3it\LibAlgo\KnapsackProblem\ItemInterface;
use App\KnapsackProblem\Entity\Task;

interface TaskRepositoryInterface{
    /**
     * @return Task[]|ItemInterface[]
     */
    public function findAllNotScheduled(): array;
}