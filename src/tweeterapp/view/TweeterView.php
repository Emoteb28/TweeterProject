<?php
namespace tweeterapp\view;

use tweeterapp\model\User;
use mf\router\Router;

class TweeterView extends \mf\view\AbstractView {
  
    /*Constructeur  classe parent*/
    public function __construct( $data ){
        parent::__construct($data);
    }


    /* renderHeader qui retourne le fragment HTML de l'entête (unique pour toutes les vues)*/
    public function renderHeader(){
        $router = new Router();
        $renderHeader = '<div>';
        $renderHeader .= '<h1>TweeterProject</h1>';
        $renderHeader .= '</div>';

        // si l'utilisateur est connecté on à le droit à d'autres options : ajouter un tweet, voir les gens qui se suivent, les utilisateurs classés seulon leu nombre de followers
        if(isset($_SESSION['user_login'])){

        	$renderHeader .= "<nav> <a href='".$router->urlFor('home')."'> <img src='/TweeterProject/images/home.png' width='50' align='left' > </a> <a href='".$router->urlFor('logout')."'> <img src='/TweeterProject/images/logout.png'  width='50'  align='center' > </a> </a> <a href='".$router->urlFor('signUp')."'> <img src='/TweeterProject/images/add.png' width='70' align='left'> </a>   </nav> <p> ".$_SESSION['user_login']." : Vous n'êtes pas encore connecté </p>  <a href='".$router->urlFor('ViewFollowers')."'> 

<img src=' /TweeterProject/images/followers.png'  width='50' align='left' alt='Follower' > </a>
        	
<a href='".$router->urlFor('post')."'> <img src='/TweeterProject/images/messages.png'  width='50' align='center' alt='Message'> </a> <a href='".$router->urlFor('ViewNumbersOfFollowersOfUsers')."'> <img src='/TweeterProject/images/score.png'  width='70' align='right' > </a>";

        } else {
        	//autrement l'utilisateur anonyme n'aura droit qu'aux options de base : aller dans le home, s'authentifier, ou s'inscrire s'il n'a pas déja un compte
        	$renderHeader .= "<nav> <a href='".$router->urlFor('home')."'> <img src='/TweeterProject/images/home.png' width='50'  align='left' > 
</a> <a href='".$router->urlFor('signIn')."'> 
<img src='/TweeterProject/images/connexion.png' width='70' align='center' > </a> </a> <a href='".$router->urlFor('signUp')."'> <img src='/TweeterProject/images/add.png' width='70'  align='center' > </a> </br>   </nav> <p> Vous n'êtes pas connecté </p>";

        }
        return $renderHeader;
  }

    /* Méthode renderFooter qui retourne le fragment HTML du bas de la page (unique pour toutes les vues)
     */
    public function renderFooter(){
        return '<div class="tweet-footer"> Licence Pro CIASIE &copy;2018 </div>';
    }

    /* Méthode renderHome qui représente la vue de la fonctionalité afficher tous les Tweets.*/
    public function renderHome(){
        $router = new \mf\router\Router();
        $res = "<article class='theme-backcolor2'>";
        $res .= "<h2>Derniers Tweets</h2>";
        foreach($this->data as $key => $t){
            $user = User::select()->where('id','=',$t->author)->first();
            $res .= "<div class='tweet'>";
            $res .= "<a href='".$router->urlFor('view',['id' => $t->id])."' class='tweet-text'>$t->text</a>";
            $res .= "<a href='".$router->urlFor('profil',['id_user' => $t->author])."' class='tweet-text'> <p class='tweet-author'>$user->username</p> </a>";
            $res .= "<p>$t->created_at</p>";
            if(isset($_SESSION['user_login'])){
            $res .= "<p class='tweet-text'> <a href='".$router->urlFor('like',['id' => $t->id])."'> 
<img src='/TweeterProject/images/like.png' align='left' > </a> Score : $t->score <a href='".$router->urlFor('dislike',['id' => $t->id])."'> <img src='/TweeterProject/images/dislike.png' align='right' > </a></p>";
        }
            $res .= "</div>";
        }
        $res .= "</article>";
        return $res;
    }

    //Affichage du profil d'un utilisateur en cliquant sur l'auteur d'un tweet
    public function renderProfil(){
    $router = new \mf\router\Router();
    $res = "";

    foreach($this->data as $key => $t){
    		$res .= "<h1> Profil de l'utilisateur</h1> </br>";
            $res .= "Nom complet :".$t->Nom;
            $res .= "<a href='".$router->urlFor('user',['id_user' => $t->id])."' class='tweet-text'>username :".$t->username." <br>Voir les twwets de cet utilisateur</a>";
            $res .= "Nombre de followers : ".$t->followers;

            $res .= "</br> </br>";


    }
        return $res;
    }
  
    /* Méthode renderUeserTweets pour la vue de la fonctionalité afficher tout les Tweets d'un utilisateur donné en allant dans le profil de l'utilisateur en cliqaunt sur lire ses tweets */
    public function renderUserTweets(){
       $router = new \mf\router\Router();
        $res = "";
         foreach($this->data as $key => $t){
            $res .= "<div class='tweet'>";
            $res .= "<a href='".$router->urlFor('view',['id' => $t->id])."' class='tweet-text'>$t->text</a>";
            $res .= "</br>";
            $res .= "<a href='".$router->urlFor('user',['id_user' => $t->id])."' class='tweet-text'> $t->created_at </a>";
        
            $res .= "</div>";
                 }
        return $res;
        /*  Retourne le fragment HTML pour afficher tous les Tweets d'un utilisateur donné.
         * L'attribut $this->data contient un objet User.
         */
        
    }

    /* Méthode renderViewTweet qui réalise la vue de la fonctionnalité affichage d'un tweet */
    public function renderViewTweet(){
        $router = new \mf\router\Router();
         if(!is_null($this->data)){
            $user = User::select()->where('id','=',$this->data->author)->first();
            $res = "<article class='theme-backcolor2'>";
            $res .= "<div class='tweet'>"; 
                    $res .= "<a href='".$router->urlFor('view',['id' => $this->data->id])."' class='tweet-text'>".$this->data->text."</a>";
                    $res .= "<a href='".$router->urlFor('user',['id' => $user->id])."' class='tweet-author'>".$user->username."</a>";
                    $res .= "<p>".$this->data->created_at."</p>";
                    $res .= "<div class='tweet-footer'><hr>";
                    $res .= "<p class='tweet-score'>".$this->data->score."</p>";
                    $res .= "</div>";
            $res .= "</div>";
            $res .= "</article>";
                
            return $res;
         }else{
             return;
         }        
    }

    public function renderfollowingOfAnUser(){
         $router = new \mf\router\Router();
        $renderfollowingOfAnUser = "";
        $renderfollowingOfAnUser .= "<article class='theme-backcolor2'>";
        $renderfollowingOfAnUser .= "<h2>Followers of an user</h2>";
        foreach($this->data as $key => $t){
            $user = Follow::select()->where('follower','=',$id);

            $renderfollowingOfAnUser .= "<div class='tweet'>";
            $renderfollowingOfAnUser .= "<div class='tweet-text'>$t->followee</div>";
            $renderfollowingOfAnUser .= "</div>";
        }
        $renderfollowingOfAnUser .= "</article>";
        return $renderfollowingOfAnUser;
        $renderfollowingOfAnUser = "";
    }

    /* Méthode renderPostTweet qui realise la vue de régider un Tweet*/
    public function renderPostTweet(){
         $router = new Router();
        $html = <<<EOF
<div id="tweet-form" >
    <form action="{$router->urlFor('send')}" method="post">
        <label for="tweet">Rédigez votre tweet</label><br />
        <textarea name="tweet" rows="10" cols="50">trololo</textarea> </br>
        <button type='submit'> Poster le tweet <button>
    </form>
</section>
</div>
EOF;
          return $html;

    }

    /* Méthode renderPostTweet qui retourne la framgment HTML qui dessine un formulaire pour la rédaction d'un tweet, l'action du formulaire est la route "send"*/
    public function renderSendTweet(){
        $renderPostTweet = '';
        $renderPostTweet .= "<div class='tweet'>";
        $renderPostTweet .= "votre tweet a été posté";
        $renderPostTweet .= '</div>';
         return $renderPostTweet;
    }

    //Affichage du formulaire qui permettra de s'enregistrer en tant que nouvel utilisateur
    private function renderViewFormSignUp(){
    	$router = new Router();
    	$res = "<section id='create'>";
        $html = <<<EOF
 
    <form action="{$router->urlFor('signup_check')}" method="post">
    <label for="tweet"><h3>Création de compte</h3></label><br />  
        <div><label for="">Fullname</label> <input type="text" name="fullname"></div>
        <div><label for="">Username</label> <input type="text" name="username"></div>
        <div><label for="">Password</label> <input type="password" name="password"></div>
        <div><label for="">Password Confirmation</label> <input type="password" name="password_confirm"></div>
        <button type="submt">Register</button>
    
    </form>
 
EOF;
        return $html;
    }

    //Formulaire pour se connecter
    private function renderViewFormSignIn(){
    	$router = new Router();
    	$res = <<<EOF
    	<form action="{$router->urlFor('login_check')}" method="post">
    	 <label for="tweet"><h3>Veuiller entrer vos informations de connexion </h3></label>        
        <input class="input" type="text" name="username" id="username" placeholder="username" required /><br>
        <input class="input" type="password" name="password" id="password" placeholder="password" required /><br>

        <button type='submit'>Se connecter</button>
        </form>

EOF;
        return $res;
    }

    //Vue pour voir les relation de following entre utilisateurs
    private function renderViewFollowers(){
        $renderViewNumbersOfFollowersOfUsers = "";
        $renderViewNumbersOfFollowersOfUsers .= "Liste des followers";
        foreach($this->data as $key => $t){
            $user = User::select()->where('id','=',$t->follower)->first();
            $user2 = User::select()->where('id','=',$t->followee)->first();
            $renderViewNumbersOfFollowersOfUsers .= "<div class='tweet'>";
            $renderViewNumbersOfFollowersOfUsers .= "<div class='tweet-text'>$user->username</div>";
            $renderViewNumbersOfFollowersOfUsers .= "<div class='tweet-text'> suite </div>";
            $renderViewNumbersOfFollowersOfUsers .= "<div class='tweet-text'>$user2->username</div>";
            $renderViewNumbersOfFollowersOfUsers .= "</div>";
        }
        return $renderViewNumbersOfFollowersOfUsers;
    }

    //Vue avec le classement des utilisateur suivant leur nombre de followers
    private function renderViewNumbersOfFollowersOfUsers(){
        $render = "";
        $render .= "Liste des utilisateurs suivant le nombre de followers";
        foreach($this->data as $key => $t){
            $render .= "<div class='tweet'>";
            $render .= "<div class='tweet-text'>$t->username</div>";
            $render .= "<div class='tweet-text'> a pour score </div>";
            $render .= "<div class='tweet-text'>$t->followers</div>";

            $render .= "</div>";
        }
        return $render;
    }

    /* Méthode renderBody qui retourne la framgment HTML de la balise <body> elle est appelée
     * par la méthode héritée render.
     * Cette méthode va appeler les diffèrentes méthodes d'affichages suivant l'utilisation. L'appel se fera à partir du tweetercontroler ou tweeteradmincontroler pour les authentification et/ou inscriptions.
*/
    public function renderBody($selector){
        $http_req = new \mf\utils\HttpRequest();
        $rendu = "";
        $rendu .= "<header class='theme-backcolor1'>".$this->renderHeader();
        $rendu .= "<nav id='nav-menu'>";
        $rendu .= "</nav></header>";
        $rendu .= "<section>";
        if($selector == 'home'){
        	//$rendu .= "vous etes dans le home";
            $rendu .= $this->renderHome();
        }else if($selector == 'userTweets'){
            $rendu .= $this->renderUserTweets();
        }else if($selector == 'viewTweet'){
            $rendu .= $this->renderViewTweet();
        }else if($selector == 'PostTweet'){
            $rendu .= $this->renderPostTweet();
        }else if($selector == 'SendTweet'){
            $rendu .= $this->renderSendTweet();
        }else if($selector == 'viewFormSignIn'){
            $rendu .= $this->renderViewFormSignIn();
            //viewFormSignUp
        }else if($selector == 'viewFormSignUp'){
            $rendu .= $this->renderViewFormSignUp();
            //viewFormSignUp
        }else if($selector == 'viewFollowers'){
            $rendu .= $this->renderViewFollowers();
            //viewFormSignUp
        }else if($selector == 'viewNumbersOfFollowersOfUsers'){
            $rendu .= $this->renderViewNumbersOfFollowersOfUsers();
        }else if($selector == 'profil'){
        	$rendu .= $this->renderProfil();
        }
        	//ViewNumbersOfFollowersOfUsers
        $rendu .= $this->renderFooter();
        return $rendu;
    }

    //Methode qui va afficher la page à partir du renderbody
    public function render($selector){
        /* le titre du document */
        $title = self::$app_title;
        /* les feuilles de style */
        $app_root = (new \mf\utils\HttpRequest())->root;
        $styles_sheets = 'html/Tweeter.css';
        $styles ='';
        foreach ( self::$style_sheets as $file )
            $styles .= '<link rel="stylesheet" href="'.$app_root.'/'.$file.'"> ';
        /* on appele la methode renderBody de la sous classe */
        $body = $this->renderBody($selector);
        /* construire la structure de la page
         * 
         *  Noter l'utilisation des variables ${title} ${style} et ${body}
         * 
         */

$html = <<<EOT
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>${title}</title>
        ${styles}
    </head>

    <body>
        
       ${body}

    </body>
</html>
EOT;
        /* Affichage de la page
         *
         * C'est la seule instruction echo dans toute l'application 
         */
        echo $html;
    }
}