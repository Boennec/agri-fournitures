<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/compte/modifier-mot-de-passe", name="account_password")
     */
    public function index(): Response
    {
        //on veut reccupérer l'user connecté
        $user = $this->getUser();

        //il faut appeler le formulaire qui est dans changePasswordType, 
        //le lier a l'user,  et le passer a la vue
        $form = $this->createForm(ChangePasswordType::class, $user);

        //il faut appeler createview() pour passer la vue a twig
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
