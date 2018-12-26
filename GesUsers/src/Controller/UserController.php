<?php 


namespace App\Controller;
Use App\Entity\User;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

  class UserController extends Controller {
    /**
     * @Route("/", name="user_list")
     * @Method({"GET"})
     */
    public function index() {
      $users= $this->getDoctrine()->getRepository(User::class)->findAll();
      return $this->render('users/index.html.twig', array('users' => $users));
    }
    /**
     * @Route("/users/{id}", name="user_show")
     */
    public function show($id) {
      $user = $this->getDoctrine()->getRepository(User::class)->find($id);
      return $this->render('users/show.html.twig', array('user' => $user));
    }
    
    /**
     * @Route("/user/new", name="new_user")
     */
    public function new(Request $request) {
      $user = new User();
      $form = $this->createFormBuilder($user)
        ->add('fullname', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('adresse', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('tel', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('save', SubmitType::class, array(
          'label' => 'Create',
          'attr' => array('class' => 'btn btn-primary mt-3')
        ))
        ->getForm();
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('user_list');
      }
      return $this->render('users/new.html.twig', array(
        'form' => $form->createView()
      ));
    }
    /**
     * @Route("/user/edit/{id}", name="edit_user")
     */
    public function edit(Request $request, $id) {
      $user = new User();
      $user = $this->getDoctrine()->getRepository(User::class)->find($id);
      $form = $this->createFormBuilder($user)
        ->add('fullname', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('adresse', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('tel', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('save', SubmitType::class, array(
          'label' => 'Edit',
          'attr' => array('class' => 'btn btn-primary mt-3')
        ))
        ->getForm();
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('user_list');
      }
      return $this->render('users/edit.html.twig', array(
        'form' => $form->createView()
      ));
    }
    /**
     * @Route("/user/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
      $user = $this->getDoctrine()->getRepository(User::class)->find($id);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($user);
      $entityManager->flush();
      $response = new Response();
      $response->send();
    }
    // // /**
    // //  * @Route("/article/save")
    // //  */
    // // public function save() {
    // //   $entityManager = $this->getDoctrine()->getManager();
    // //   $article = new Article();
    // //   $article->setTitle('Article Two');
    // //   $article->setBody('This is the body for article two');
    // //   $entityManager->persist($article);
    // //   $entityManager->flush();
    // //   return new Response('Saved an article with the id of  '.$article->getId());
    // // }
  }