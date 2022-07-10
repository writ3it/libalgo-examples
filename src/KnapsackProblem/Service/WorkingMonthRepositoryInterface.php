<?php

namespace App\KnapsackProblem\Service;

use Writ3it\LibAlgo\KnapsackProblem\BagInterface;
use App\KnapsackProblem\Entity\WorkingMonth;

interface WorkingMonthRepositoryInterface
{
    /**
     * Returns working months in ascending order that can be scheduled. 
     * 
     * @return WorkingMonth[]|BagInterface[]
     */
    public function findAllNotScheduled(): array;

    public function flush(): void;
}
