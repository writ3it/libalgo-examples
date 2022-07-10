# Knapsack problem

## Description of the example

Imagine you are a very busy freelancer developer. There are many independent tasks waiting for you in your backlog. You settle accounts with clients monthly for completed tasks. How to schedule tasks in months to get a profit as quickly as possible with limited work time?

The *working month* has a specific volume (*capacity*), expressed in hours.

The *tasks* are *estimated* and have a specific *salary*.


## How to run

```shell
$ php bin/console app:tasks:schedule
```

If you would like to persist the task schedule, call command below:

```shell
$ php bin/console app:tasks:schedule --persist
```

### Result

After command execution you will see the list of months and list of tasks which were processed by algorithm. 

```plain
+----------------+---------------+------------------------+-------------------------------+
| Month          | Time capacity | Scheduled tasks salary | Scheduled tasks work time (h) |
+----------------+---------------+------------------------+-------------------------------+
| July 2022      | 192           | 69627                  | 170                           |
| August 2022    | 155           | 24085                  | 154                           |
| September 2022 | 146           | 7523                   | 137                           |
+----------------+---------------+------------------------+-------------------------------+
+-----------+--------+----------------+----------------+
| Task name | Salary | Estimation (h) | Month          |
+-----------+--------+----------------+----------------+
| Task #0   | 1009   | 30             | not assigned   |
| Task #1   | 3057   | 45             | September 2022 |
| Task #2   | 8760   | 45             | August 2022    |
| Task #3   | 16803  | 44             | July 2022      |
| Task #4   | 17839  | 51             | July 2022      |
| Task #5   | 1886   | 52             | September 2022 |
| Task #6   | 5144   | 56             | August 2022    |
| Task #7   | 2580   | 40             | September 2022 |
| Task #8   | 15931  | 31             | July 2022      |
| Task #9   | 10181  | 53             | August 2022    |
| Task #10  | 19054  | 44             | July 2022      |
| Task #11  | 891    | 53             | not assigned   |
+-----------+--------+----------------+----------------+
```

## Code explanation

### Data

Example database is created with [migration](../../migrations/Version20220710093619.php). There is a code to produce sample 12 tasks which should be scheduled in 3 months, if they fit therein.

### Code

The selection of optimal tasks is a knapsack problem. To model the monthly billing, the *Bag* would be months. (`WorkingMonth` is implementing `BagInterface`.) Tasks are *items* that should be assigned to the months. (`Task` is implementing `ItemInterface`.) To get the profit as soon as possible, we need to maximize first month, remove assigned task from the set and repeat operation for the next month. This operation is implemented in `TaskSchedulerService` which is using `libalgo` library. Some tasks could not be assigned because estimated time is greater than available time capacity in months. That's why in the result table you can find *not assigned* tasks.

`WorkingMonthRepositoryInterface` and `TaskRepositoryInterface` are used to separate database layer from logic/application layer. `TasksSchedulerSServiceInterface` is used to separate logic/application from presentation layer. `ScheduleTaskCommand` is an Symfony Command.

`config.yaml` encapsulates framework configuration required to work with this component. 