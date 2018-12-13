<?php


namespace tweeterapp\control;
use mf\auth\Authentication;
use mf\auth\AuthenticationException;
use mf\control\AbstractController;
use mf\router\Router;
use mf\utils\HttpRequest;
use tweeterapp\auth\TweeterAuthentication;
use tweeterapp\view\TweeterView;


class TweeterAdminController extends \mf\control\AbstractController
{
    public function __construct(){
        parent::__construct();
        TweeterView::addStyleSheet('html/Tweeter.css');
        TweeterView::setAppTitle('Tweeter');
    }


    //affiche la vue du formulaire d'authentification
    public function viewFormSignIn(){
        $vue = new \tweeterapp\view\TweeterView('');
        $vue->render('viewFormSignIn');
    }

    //affiche la vue du formulaire d'inscription
    public function viewFormSignUp(){
        $vue = new \tweeterapp\view\TweeterView('');
        $vue->render('viewFormSignUp');
    }


    //methode ppour vérifier si tous les champs lors de l'inscription sont totalement remplis et si le mot de passe est tappé en 1er et en 2ème sont les bons
    public function checkSignup(){
        $request = new \mf\utils\HttpRequest();
        $post = $request->post;
        $authentification = new \tweeterapp\auth\TweeterAuthentification();
        $router = new Router();
        $errors = [];
        //si aucun champ n'est vide et sont totalement remplis
        if(!empty($post['username']) && !empty($post['password']) && !empty($post['fullname']) && !empty($post['password_confirm'])){
            try {//vérifie que les 2 mots de passe lors de l'inscription sont similaires
                if($post['password'] === $post['password_confirm']){
                    //creation de l'utilisateur
                    $authentification->createUser($post['username'], $post['password'], $post['fullname']);
                    //connextion du nouvel utilisateur directement sur tweeter
                    $authentification->loginUser($post['username'], $post['password']);
                    //aller dans le home
                    header('Location: ' . $router->urlFor('home'));
                } else {
                    $errors[] = 'Passwords mismatch';
                    header('Location: ' . $router->urlFor('signUp'));
                }
            } catch (AuthenticationException $e) {
                $errors[] = $e->getMessage();
            }
        } else {
            $errors[] = 'Verifiez le formulaire.';
            header('Location: ' . $router->urlFor('signUp'));
        }
        if(!empty($errors)){
            //afficher les erreurs d'enregistrement d'un nouvel utilisateur sur un autre page
            $tweeterView = new \tweeterapp\auth\TweeterView(['errors' => $errors]);
            $tweeterView->render('viewFormSignUp');
        }
    }

    //réalise la déconnexion
    public function makeLogout(){
        $authentication = new \tweeterapp\auth\TweeterAuthentification();
        $router = new Router();
        $authentication->logout();
        header('Location: ' . $router->urlFor('home'));
    }

    //vérifie la connextion d'un utilisateur twetter
    public function checkLogin(){
        $request = new HttpRequest();
        $post = $request->post;
        $authentification = new \tweeterapp\auth\TweeterAuthentification();
        $errors = [];
        if(!empty($post['username']) && !empty($post['password'])){
            try {
                //fonction d'authentification à twetter
                $authentification->loginUser($post['username'], $post['password']);
                $router = new Router();
                //orientation vers le profil de l'utilisateur qui vient de se connecter à un instant
                //header('Location: ' . $router->urlFor('me'));
                header('Location: ' . $router->urlFor('home'));
            } catch (AuthenticationException $e) {
                $errors[] = $e->getMessage();
            }
        } else {
            header('Location: ' . $router->urlFor('signIn'));
            $errors[] = 'Verifiez le formulaire.';
        }
        if(!empty($errors)){
            //vue sur les erreurs
            $tweeterView = new \tweeterapp\auth\TweeterView(['errors' => $errors]);
            //retour à l'écran d'authentification (de connexion d'un utilisateur)
            $tweeterView->render('viewFormSignIn');
        }
    }
}