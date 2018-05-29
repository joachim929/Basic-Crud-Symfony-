<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Flex\Response;

class TaskController extends AbstractController
{
    /**
     * this one shows all tasks
     * @Route("/task", name="task")
     */
    public function showTasks(Request $request){
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $repository->findAll();

        return $this->render('task/task.html.twig', [
            'tasks' => $this->showAll()
        ]);
    }

    /**
     * Gets all the data from the database
     */
    public function showAll(){
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $repository->findAll();

        return $tasks;
    }
    /**
     *  Deletes a entry
     * @Route("/task/delete/{id}", name="delete_task")
     */
    public function deleteTask($id){
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if(!$task){
            throw $this->createNotFoundException(
                'No task found for id: '.$id
            );
        }
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('task');
    }

    /**
     * Page to create new tasks
     * @Route("/task/new", name="new_task")
     */
    public function createTask(Request $request){
        $form = $this->createForm(TaskType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $taskFormData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $task = new Task();
            $task->setTask($taskFormData['task']);
            $task->setDueDate($taskFormData['dueDate']);

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task');
        }

        return $this->render('task/new_task.html.twig', [
            'our_form' => $form->createView()
        ]);
    }

    /**
     * Page to edit existing task
     * I know I have a form/TaskType class but was having difficulty implementing it with this function
     * So making a new form, just looks exactly the same as when making a new form other than being prepopulated
     * @Route("/task/edit/{id}", name="edit_task")
     */
    public function editTask(Request $request, $id){
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if(!$task){
            throw $this->createNotFoundException(
                'No task found for id: '.$id
            );
        }

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateTimeType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('task');
        }

        return $this->render('task/edit.html.twig', [
            'our_form' => $form->createView()
        ]);
    }



}
