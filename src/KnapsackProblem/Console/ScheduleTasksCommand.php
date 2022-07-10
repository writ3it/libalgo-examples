<?php

namespace App\KnapsackProblem\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\KnapsackProblem\Service\TasksSchedulerServiceInterface;
use App\KnapsackProblem\Entity\WorkingMonth;
use Symfony\Component\Console\Helper\Table;
use App\KnapsackProblem\Entity\Task;
use App\KnapsackProblem\Service\TaskRepositoryInterface;
use App\KnapsackProblem\Service\WorkingMonthRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Input\InputOption;

class ScheduleTasksCommand extends Command
{
    private TasksSchedulerServiceInterface $tasksSchedulerService;

    private TaskRepositoryInterface $taskRepository;
    private WorkingMonthRepositoryInterface $workingMonthRepository;
    public function __construct(
        TasksSchedulerServiceInterface $tasksSchedulerService,
        TaskRepositoryInterface $taskRepository,
        WorkingMonthRepositoryInterface $workingMonthRepository
    ) {
        $this->tasksSchedulerService = $tasksSchedulerService;
        $this->workingMonthRepository = $workingMonthRepository;
        $this->taskRepository = $taskRepository;
        parent::__construct();
    }
    public function configure()
    {
        $this
        ->setName('app:tasks:schedule')
        ->setDescription('Schedule tasks with Knapsack Problem Solver')
        ->addOption('persist', 'p', InputOption::VALUE_NONE, 'Persist the results to the database');
    }

    public function execute(InputInterface $input, OutputInterface $output):int
    {
        /**
         * @var ArrayCollection<Task> $tasks
         * @var ArrayCollection<WorkingMonth> $months
         */
        $tasks =  new ArrayCollection($this->taskRepository->findAllNotScheduled());
        $months = new ArrayCollection($this->workingMonthRepository->findAllNotScheduled());

        $this->tasksSchedulerService->scheduleTasks($tasks, $months);
        
        if ($input->getOption('persist')) {
            $this->workingMonthRepository->flush();
        }

        $table = new Table($output);
        $table->setHeaders(['Month', 'Time capacity','Scheduled tasks salary', 'Scheduled tasks work time (h)']);

        $table->setRows(
            $months
                ->map(fn (WorkingMonth $month) =>[$month->getName(),$month->getCapacity(), $month->getScheduledSalary(), $month->getScheduledTime()])
                ->toArray()
        );

        $table->render();

        $table = new Table($output);
        $table->setHeaders(['Task name', 'Salary', 'Estimation (h)', 'Month']);

        $table->setRows(
            $tasks
                ->map(fn (Task $task) =>[$task->getName(), $task->getSalary(), $task->getEstimation(), $task->getAssignedMonthName()])
                ->toArray()
        );

        $table->render();

        return Command::SUCCESS;
    }
}
