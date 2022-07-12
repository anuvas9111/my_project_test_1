<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController

{


    /**
     * @Route("/task", name="app_task")
     */
    public function new(Request $request): Response
    {
        //Создание новой заявки
        $task = new Task();
        $form = $this->createForm(TaskFormType::class, $task);
        if ($request->isMethod('POST'))
        {
            $form->submit($request->request->get($form->getName()));
            if ($form->isSubmitted() && $form->isValid())
            {
                $task = $form->getData();
                $user = $this->getUser();
                $task->setUser($user);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($task);
                $entityManager->flush();
                return $this->redirectToRoute('app_task');
            }
        }

        return $this->renderForm('task/task.html.twig', [
            'form' => $form,
        ]);
    }

}