<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig',
         [
             'last_username' => $lastUsername,
              'error' => $error
        ]);
    }


    /**
     * @Route("/register", name="app_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        
        //fil function 3melna injection de dependence  :Request recuperation/il ya modif ds base dc manager 
        //affichage de formulaire qui se base sur RegistrationType (ds dossier Form) (make:form RegistrationType )
         //ce form lier a un user donc on va crer un user 
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
       //recuperation 
        $form->handleRequest($request);
            //si le form et valid et submitted
          if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            //si oui hez lma3loumet lit3abou
            $manager->persist($user);
            //si oui envoyer a la base
            $manager->flush();
            //redirection de la route vers page  app_login'
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView() //>createView() : on va envoyer que le view (pas de continue )
             //afficher form ds templ   {{ form_start(form) : on vaenvoyer le form}}
             // {{ form_widget(form)}} :afficher formul
             // {{ form_end(form)}} :fermer formul
             //!!!!!!! form_row bech naffichi les label w tnejem t3adi fih objet
             //ya3ni des places holder bil attr ili fil RegistrationType wella fi twig
        ]);
    }
    //!!!! les controlle des champ se trouve dans entity w yelzmek te3mel import li
    //use Symfony\Component\Validator\Constraints as Assert; w tzid @Assert lil controle


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    { }
}
