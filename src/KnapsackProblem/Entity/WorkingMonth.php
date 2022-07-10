<?php

namespace App\KnapsackProblem\Entity;

use Doctrine\ORM\Mapping as ORM;
use Writ3it\LibAlgo\KnapsackProblem\ItemInterface;
use Writ3it\LibAlgo\KnapsackProblem\BagInterface;
use Doctrine\Common\Collections\Collection;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\KnapsackProblem\Repository\WorkingMonthRepository;

#[ORM\Entity(repositoryClass: WorkingMonthRepository::class)]
class WorkingMonth implements BagInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $month;

    /**
     * Available time (hours)\
     */
    #[ORM\Column(type: "integer")]
    private int $availableTime;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: "scheduledMonth", cascade: ["persist", "remove"])]
    private Collection $scheduledTasks;

     /**
     * Little optimization
     */
    #[ORM\Column(type: "boolean", options: ['default'=> false])]
    private bool $scheduled = false;

    /**
     * @param DateTimeInterface $month
     * @param int $availableTime
     */
    public function __construct(DateTimeInterface $month, int $availableTime)
    {
        $this->month = $month;
        $this->availableTime = $availableTime;
        $this->scheduledTasks = new ArrayCollection();
        $this->scheduled = false;
    }

    /**
     * Returns capacity of bag.
     *
     * Capacity determines the maximum weight that can be stored in the bag.
     *
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->availableTime;
    }
    
    /**
     * Add item to the solution (bag).
     *
     * @param ItemInterface|Task $item
     *
     * @return void
     */
    public function addItem(ItemInterface $item): void
    {
        if (!$this->scheduledTasks->contains($item)) {
            $this->scheduledTasks->add($item);
            $this->scheduled = true;
            $item->schedule($this);
        }
    }

    public function getScheduledTasks():Collection
    {
        return $this->scheduledTasks;
    }

    public function getScheduledSalary():int
    {
        return array_sum($this->scheduledTasks->map(fn (Task $task) => $task->getSalary())->getValues());
    }

    public function getScheduledTime():int
    {
        return array_sum($this->scheduledTasks->map(fn (Task $task) => $task->getEstimation())->getValues());
    }

    public function getName():string
    {
        return $this->month->format('F Y');
    }
}
