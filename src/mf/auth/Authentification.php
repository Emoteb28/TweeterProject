<?php
namespace mf\auth;
require_once "AbstractAuthentification.php";

class Authentification extends AbstractAuthentification {

    public function __construct(){
        if(isset($_SESSION['user_login'])){
            $this->user_login = $_SESSION['user_login'];
            $this->access_level = $_SESSION['access_level'];
            $this->logged_in = true;
        }else{
            $this->user_login = NULL;
            $this->access_level = self::ACCESS_LEVEL_NONE;
            $this->logged_in = false;
        }

    }

    //methode qui après authentification crée une nouvelle session avec le niveau de l'utilisateur et son login jsuq'à la déconnexion ou les variables de sessions seront supprimées.
    public function updateSession($username,$level){
            $this->user_login = $username;
            $this->access_level = $level;
            $_SESSION['user_login'] = $username;
            $_SESSION['access_level'] = $level;
            $this->logged_in = true;
    } 


    //deconnexion de l'utilisateur avec la suppression des variables de sessions
    public function logout(){
        unset($_SESSION['user_login']);
        unset($_SESSION['access_level']);
        $this->user_login = NULL;
        $this->access_level = self::ACCESS_LEVEL_NONE;
        $this->logged_in = false;
    }



    public function checkAccessRight($requested){
        if($requested > $this->access_level){
            return false;
        }else{
            return true;
        }
    }

    //fonction d'authentification qui vérifie si le mdp tappé correspond au hash de la bd.
    public function login($username,$db_pass,$pass,$level){
        if($this->verifyPassword($pass,$db_pass)){
            $this->updateSession($username,$level);
        }else{
            throw new \Exception('mot de passe ne corespond pas au hache');
        }
    }

    //Hachage de mdp pour ne pas qu'ils soient en clair dans la bd
    public function hashPassword($password){
        return password_hash($password,PASSWORD_DEFAULT);
    }

    //fonction d'authentification qui vérifie si le mdp tappé correspond au hash de la bd.
    public function verifyPassword($password, $hash){
        return password_verify($password,$hash);
    }


}

?>