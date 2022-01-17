<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        //on integre dans $entityManager l'EntityManager qui a été instancié grace a l'EntityManagerInterface
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {

        $user = new User();
        //on va lier l'objet User au formulaire
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        //es ce que le form est soummis (clic sur le button submit) et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $password = $encoder->HashPassword($user, $user->getPassword());

            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }


        return $this->render('register/index.html.twig', [
            'formUser' => $form->createView()
        ]);
    }
}
