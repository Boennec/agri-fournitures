<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

public function __construct(EntityManagerInterface $entityManager){
$this->entityManager = $entityManager;

}
    /**
     * @Route("/compte/modifier-mot-de-passe", name="account_password")
     */

    //on passe Request dans la function index(), que l'on place dans $request
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {

        $notification = null;
        //on veut reccupérer l'user connecté
        $user = $this->getUser();

        //il faut appeler le formulaire qui est dans changePasswordType, 
        //le lier a l'user,  et le passer a la vue
        $form = $this->createForm(ChangePasswordType::class, $user);

        //handleRequest sert a écouter la requete entrante
        $form->handleRequest($request);

        //si le form a été soumis et validé
        if ($form->isSubmitted() && $form->isValid()) {

            //on veut donc modifier le password;
            //il faut pouvoir comparer le password actuellement saisi par le user avec le password encrypté dans la bdd
            $old_pwd = $form->get('old_password')->getData();
            if ($encoder->isPasswordValid($user, $old_pwd)) {

                //on reccupere le new password saisi dans le form 
                $new_pwd = $form->get('new_password')->getData();

                //on hash le new password avec hashpassword
                $password = $encoder->HashPassword($user, $new_pwd);

                //on set le new password dans la propriété password de l'entity User
                $user->setPassword($password);


                //$this->entityManager->persist($user);
                //pas besoin de persister car c'est une mise a jour et non une création de password

                $this->entityManager->flush($user);
                $notification = "Votre mot de passe a bien été mis a jour";
            } else {

                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }

        //il faut appeler createview() pour passer la vue a twig
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
