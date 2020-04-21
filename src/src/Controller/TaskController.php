<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/", name="task_index", methods={"GET"})
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository(Task::class)->findBy([], ['created' => 'DESC']);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

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
    public function delete(Request $request, Task $task): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_index');
    }

    /**
     * @Route("/finish/{id}", name="task_finish", methods={"GET"})
     */
    public function finish(Request $request, Task $task): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task->setFinished(true);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_index');
    }

    /**
     * @Route("/reopen/{id}", name="task_reopen", methods={"GET"})
     */
    public function reopen(Request $request, Task $task): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task->setFinished(false);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_index');
    }
}
