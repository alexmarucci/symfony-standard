<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormError;
use AppBundle\Entity\Task;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //Dummy data
        $task = new Task();
        $task->setTask('');
        $task->setDueDate(new \DateTime('tomorrow'));

        // 1) Create a Form
        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class, array(
                    'attr' => ['placeholder' => 'Leave me blank'],
                    'constraints' => new NotBlank(),
                    'label' => false,
                    'required' => false
                ))
            ->add('save', SubmitType::class, array('label' => 'Throw Exception'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // 2) Catch form errors
            $errors = $form->getErrors(true);
            $formError = $errors->current();    

            // 3) Try to serialize an object of type Symfony\Component\Form\FormError
            if ($formError instanceof FormError) {
                $serializedFormError = $formError->serialize();
            }

            return new Response('Yay! No Exception Occurs.');
        }
        
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
