<?php



namespace tweeterapp\auth;



use mf\auth\AuthentificationException;

use tweeterapp\model\User;




class TweeterAuthentification extends \mf\auth\Authentification {



    /*

     * Classe TweeterAuthentification qui définie les méthodes qui dépendent

     * de l'application (liée à la manipulation du modèle User) 

     *

     */



    /* niveaux d'accès de TweeterApp 

     *

     * Le niveau USER correspond a un utilisateur inscrit avec un compte

     * Le niveau ADMIN est un plus haut niveau (non utilisé ici)

     * 

     * Ne pas oublier le niveau NONE un utilisateur non inscrit est hérité 

     * depuis AbstractAuthentification 

     */

    //constantes décrivant les droits en fonctions des types d'utilisateurs

    const ACCESS_LEVEL_NONE  = 0; 

    const ACCESS_LEVEL_USER  = 100;   

    const ACCESS_LEVEL_PARTNER = 150;

    const ACCESS_LEVEL_ADMIN = 9999;



    /* constructeur */

    public function __construct(){

        parent::__construct();

    }


    //création d'un nouvel utilisateur avec le niveau d'accès d'un utilisateur
    public function createUser($username, $pass, $fullname, $level = self::ACCESS_LEVEL_USER) {

        //vérifie si l'utilisateur existe pour ne pas qu'il se crée 2 profils
        $userExists = User::where('username', '=' ,$username)->first();

        //vérifie si  l/' utilisateur n'exsite pas déja pour lui créer un nouveau profil
        if(!$userExists){

            $user = new \tweeterapp\model\User();

            $user->username = $username;

            $user->fullname = $fullname;

            $user->password = $this->hashPassword($pass);

            $user->level = $level;

            $user->followers = 0;

            //enregistrement du nouvel itilisateur dans la BD
            $user->save();

        } else {

            throw new AuthenticationException('User already exists');

        }

    }



    /* La méthode loginUser

     *  

     * permet de connecter un utilisateur qui a fourni son nom d'utilisateur 

     * et son mot de passe (depuis un formulaire de connexion)

     *

     * @param : $username : le nom d'utilisateur   

     * @param : $password : le mot de passe tapé sur le formulaire

     *

     * Algorithme :

     * 

     *  - Récupérer l'utilisateur avec l'identifiant $username depuis la BD

     *  - Si aucun de trouvé 

     *      - soulever une exception 

     *  - sinon 

     *      - réaliser l'authentification et la connexion

     *

     */

    /**

     * @param $username

     * @param $password

     * @throws AuthenticationException

     */

    //connexion de l'utilisateur
    public function loginUser($username, $password){

        //recherche si la personne qui se connecte a un profil
        $expectedUser = User::where('username', '=', $username)->first();

        //si l'utilisateur existe et s'est correctement authentifié
        if($expectedUser){

            try {

                $this->login($username, $expectedUser->password, $password, $expectedUser->level);

            } catch (AuthenticationException $e) {

                throw new AuthentificationException('Check your credentials.');

            }

        } else {

            //exception soulevée si l'utilisateur n'existe pas
            throw new AuthentificationException('This user doesn\'t exists');

        }

    }



}