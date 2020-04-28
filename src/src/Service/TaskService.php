<?php

namespace App\Service;

use \Doctrine\ORM\EntityManager;
use \App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    protected $entityManager;

    /**
     * TaskService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Task $task)
    {
        //  some additional logic can be added there, but to keep simple - lets leave it as is
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function delete(Task $task)
    {
        //  some additional logic can be added there, but to keep simple - lets leave it as is
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function getAllTasks()
    {
        return $this->entityManager->getRepository(Task::class)->findBy([], ['created' => 'DESC']);
    }

    public function finish(Task $task)
    {
        $task->setFinished(true);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function reopen(Task $task)
    {
        $task->setFinished(false);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}

