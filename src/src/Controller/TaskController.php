<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/", name="task_index", methods={"GET"})
     */
    public function index(TaskService $taskService): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskService->getAllTasks()
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     */
    public function new(Request $request, TaskService $taskService): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->create($task);

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/delete/{id}", name="task_delete", methods={"GET"})
     */
    public function delete(Request $request, Task $task, TaskService $taskService): Response
    {
        $taskService->delete($task);

        return $this->redirectToRoute('task_index');
    }

    /**
     * @Route("/finish/{id}", name="task_finish", methods={"GET"})
     */
    public function finish(Request $request, Task $task, TaskService $taskService): Response
    {
        $taskService->finish($task);

        return $this->redirectToRoute('task_index');
    }

    /**
     * @Route("/reopen/{id}", name="task_reopen", methods={"GET"})
     */
    public function reopen(Request $request, Task $task, TaskService $taskService): Response
    {
        $taskService->reopen($task);

        return $this->redirectToRoute('task_index');
    }
}
