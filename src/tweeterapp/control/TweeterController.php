<?php

namespace tweeterapp\control;

require_once 'main.php';
use tweeterapp\model\Tweet;
use tweeterapp\model\User;
use tweeterapp\model\Follow;
use tweeterapp\view\TweeterView;

/* Classe TweeterController :
 *  - afficher la liste des Tweets
 *  - afficher un Tweet
 *  - afficher les tweet d'un utilisateur 
 *  - afficher la le formulaire pour poster un Tweet
 *  - afficher la liste des utilisateurs suivis 
 *  - Evaluer un Tweet
 *  - suivre un utilisateur
 */

class TweeterController extends \mf\control\AbstractController {

    /*  Appelle la classe parent \mf\control\AbstractController*/
    public function __construct(){
        parent::__construct();
        TweeterView::addStyleSheet('html/Tweeter.css');
        TweeterView::setAppTitle('TwetterProject');
    }


    /* Méthode viewHome :  affiche la liste de Tweet*/
    
    //vue par défaut avec tous les tweets de tous les utilisateurs
    public function viewHome(){
        $tweets = tweet::select()->orderByDesc('created_at')->get();
       	$vue = new \tweeterapp\view\TweeterView($tweets); 
        echo $vue->render('home');
        //echo $vue->renderPostTweet();

        /* Algorithme :
         *  
         *  1 RÃ©cupÃ©rer tout les tweet en utilisant le modÃ¨le Tweet
         *  2 Parcourir le rÃ©sultat 
         *      afficher le text du tweet, l'auteur et la date de crÃ©ation
         *  3 Retourner un block HTML qui met en forme la liste
         * 
         */

        

    }

	
	
    /* MÃ©thode viewTweet : 
     *  
     * RÃ©alise la fonctionnalitÃ© afficher un Tweet
     *
     */
    
    //Affichage d'un tweet en particulier à partir de l'id tweet
    public function viewTweet(){


         if(isset($_GET['id'])){
            $tweet_id = $_GET['id'];
            $tweet = Tweet::where('id','=',$tweet_id)->first();
            $vue = new \tweeterapp\view\TweeterView($tweet);
            return $vue->render('tweet');

         }

    }


    //Appel du formulaire pour poster un tweet
    public function postTweet(){

    	$vue = new \tweeterapp\view\TweeterView('');
        return $vue->render('PostTweet');
    }

    
    //methode réalisant l'envoi du tweet
    public function sendTweet(){

        echo 'envoi du tweet';
        $user = User::where('username','=',$_SESSION['user_login'])->first();
        $tweet = new \tweeterapp\model\Tweet();
        $tweet->text = $_POST['tweet'];
        $tweet->author = $user->id;
        $tweet->score = 0;
        //enregistrement du nouveau tweet dans la bd
        $tweet->save();
        $vue = new \tweeterapp\view\TweeterView('');
        return $vue->render('SendTweet');

    }

    /*

    public function follow(){

       

         if(isset($_GET['id_user']) && isset($_GET['id_follower'])){

            $id_user = $_GET['id_user'];

            $id_follower=$_GET['id_follower'];

            $follow = new \tweeterapp\model\Follow();
         
            $follow->follower = $id_follower;

            $follow->followee = $id_user;


            $follow->save();

            

         }


    }
*/
    

    public function followingOfAnUser(){
    	$tweet = Follow::where('follower','=',$id);
    	$vue = new \tweeterapp\view\TweeterView('');
        return $vue->render('followingOfAnUser');


    }






    /* MÃ©thode viewUserTweets :
     *
     * RÃ©alise la fonctionnalitÃ© afficher les tweet d'un utilisateur
     *
     */
    

    //methode affichant les tweets rédiges par un utilisateur en particulier
    public function viewUserTweets(){
        //si l'utilisateur est authentifié

        if(isset($_GET['id_user'])){
           $user_id = $_GET['id_user'];
           $user = User::where('id','=',$user_id)->first();
              
           if(!is_null($user)){
           //$tweets = $user->tweets()->get();
           $tweet = Tweet::where('author','=',$user_id)->get();
           }
           else{
            $tweet = null;
           }
            $vue = new \tweeterapp\view\TweeterView($tweet);
           return $vue->render('userTweets');  
        }

    }

    //demande d'affichage du formulaire de connexion
    public function viewFormSignIn(){
        $vue = new \tweeterapp\view\TweeterView('');
        return $vue->render('viewFormSignIn');
    }

    //demande d'affichage du formulaire d'inscription
    public function viewFormSignUp(){
        $vue = new \tweeterapp\view\TweeterView('');
        return $vue->render('viewFormSignUp');
    }

    
    //
    public function viewFollowers(){
        $tweets = \tweeterapp\model\Follow::all();
        $vue = new \tweeterapp\view\TweeterView($tweets);
        return $vue->render('viewFollowers');
    }

    
    
     public function viewNumbersOfFollowersOfUsers() {
         $lignes4 = User::select()->orderByDesc('followers')->get();
         $vue = new \tweeterapp\view\TweeterView($lignes4);
        return $vue->render('viewNumbersOfFollowersOfUsers');
    }

    public function likeTweet(){
        $tweet_id = $_GET['id'];
        $tweet = Tweet::where('id','=',$tweet_id)->first();
        $tweet->score = $tweet->score + 1;
        $tweet->save();
        $tweets = tweet::select()->orderByDesc('created_at')->get();
        //$tweets = \tweeterapp\model\Tweet::all()->orderBy('created_at');
        $vue = new \tweeterapp\view\TweeterView($tweets); 
        echo $vue->render('home');
    }

    public function dislikeTweet(){
        $tweet_id = $_GET['id'];
        $tweet = Tweet::where('id','=',$tweet_id)->first();
        $tweet->score = $tweet->score - 1;
        $tweet->save();
        $tweets = tweet::select()->orderByDesc('created_at')->get();
        //$tweets = \tweeterapp\model\Tweet::all()->orderBy('created_at');
        $vue = new \tweeterapp\view\TweeterView($tweets); 
        echo $vue->render('home');


    }

    public function profil(){
            if(isset($_GET['id_user'])){
           $user_id = $_GET['id_user'];
           $user = User::select()->where('id','=',$user_id)->get();
           $vue = new \tweeterapp\view\TweeterView($user);
           echo $vue->render('profil');
            }
    }
}