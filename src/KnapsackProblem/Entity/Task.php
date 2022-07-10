<?php

namespace App\KnapsackProblem\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\KnapsackProblem\Repository\TaskRepository;
use Writ3it\LibAlgo\KnapsackProblem\ItemInterface;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task implements ItemInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    /**
     * Time estimation (hours)
     */
    #[ORM\Column(type: "integer")]
    private int $estimation;

    /**
     * Salary for the task (some currency)
     */
    #[ORM\Column(type: "integer")]
    private int $salary;

    #[ORM\ManyToOne(targetEntity: WorkingMonth::class, inversedBy: "scheduledTasks")]
    #[ORM\JoinColumn(name: 'scheduled_month_id', referencedColumnName: 'id')]
    private WorkingMonth $scheduledMonth;

    /**
     * Little optimization
     */
    #[ORM\Column(type: "boolean", options: ['default'=> false])]
    private bool $scheduled;

    /**
     * @param $id mixed
     * @param $name mixed
     * @param $estimation mixed
     * @param $salary mixed
     */
    public function __construct($id, $name, $estimation, $salary)
    {
        $this->id = $id;
        $this->name = $name;
        $this->estimation = $estimation;
        $this->salary = $salary;
        $this->scheduled = false;
    }


    /**
     * Returns weight of item.
     *
     * Weight determines the size of the object in Bag.
     *
     * @return int
     */
    public function getWeight(): int
    {
        return $this->getEstimation();
    }
    
    /**
     * Returns value of item.
     *
     * Value is the parameter to be maximized.
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->getSalary();
    }

    public function getSalary(): int
    {
        return $this->salary;
    }

    public function getEstimation(): int
    {
        return $this->estimation;
    }

    public function schedule(WorkingMonth $month): void
    {
        $this->scheduledMonth = $month;
        $this->scheduled = true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isScheduled():bool
    {
        return $this->scheduled;
    }

    public function getAssignedMonthName():string
    {
        return $this->isScheduled() ? $this->scheduledMonth->getName() : 'not assigned';
    }
}
