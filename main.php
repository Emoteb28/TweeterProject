<?php

/* pour le chargement automatique des classes dans vendor */
require_once 'vendor/autoload.php';
require_once 'src/mf/utils/ClassLoader.php';

use mf\router\Router;
use tweeterapp\model\User;
use tweeterapp\model\Follow;
use tweeterapp\model\Like;
use tweeterapp\model\Tweet;
use tweeterapp\control\TweeterController;
use tweeterapp\auth\TweeterAuthentification;


$loader = new mf\utils\ClassLoader("src");
$loader->register();


//chagement du fichier de configuration dans la base de donnée locale
$init = parse_ini_file("conf/config.ini");

//recuperation des paramètres de configuration
$config = [
    'driver'    => $init["driver"],
    'host'      => $init["host"],
    'database'  => $init["database"],
    'username'  => $init["username"],
    'password'  => $init["password"],
    'charset'   => $init["charset"],
    'collation' => $init["collation"],
    'prefix'    => $init["prefix"] ];

/* une instance de connexion  */
$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection( $config ); /* configuration avec nos paramètres */
$db->setAsGlobal();            /* visible de tout fichier */
$db->bootEloquent();           /* établir la connexion */
$tweet = new Tweet();
$like = new Like();
$follow = new Follow();
$user = new User();

//création d'une nouvelle session
session_start();

//Router pour enregistrer les chemins de l'application
$router = new \mf\router\Router();

$router->addRoute('home', '/home/', '\tweeterapp\control\TweeterController', 'viewHome',\tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('view', '/view/', '\tweeterapp\control\TweeterController', 'viewTweet',\tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('user', '/user/', '\tweeterapp\control\TweeterController', 'viewUserTweets', \tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('post', '/post/', '\tweeterapp\control\TweeterController', 'postTweet',\tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('send', '/send/', '\tweeterapp\control\TweeterController', 'sendTweet',\tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('signIn', '/signIn/', '\tweeterapp\control\TweeterAdminController', 'viewFormSignIn',\tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('signUp', '/signUp/', '\tweeterapp\control\TweeterAdminController', 'viewFormSignUp',\tweeterapp\auth\TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('signup_check', '/signup_check/', '\tweeterapp\control\TweeterAdminController', 'checkSignup');

$router->addRoute('login_check', '/login_check/', '\tweeterapp\control\TweeterAdminController', 'checkLogin', TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('ViewFollowers', '/ViewFollowers/', '\tweeterapp\control\TweeterController', 'viewFollowers');

$router->addRoute('login_check', '/login_check/', '\tweeterapp\control\TweeterAdminController', 'checkLogin', TweeterAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('ViewNumbersOfFollowersOfUsers', '/viewNumbersOfFollowersOfUsers/', '\tweeterapp\control\TweeterController', 'viewNumbersOfFollowersOfUsers');

$router->addRoute('like', '/like/', '\tweeterapp\control\TweeterController', 'likeTweet', TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('dislike', '/dislike/', '\tweeterapp\control\TweeterController', 'dislikeTweet', TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('profil', '/profil/', '\tweeterapp\control\TweeterController', 'profil', TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('login', '/login/', '\tweeterapp\control\TweeterAdminController', 'viewLogin', TweeterAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('logout', '/logout/', '\tweeterapp\control\TweeterAdminController', 'makeLogout', TweeterAuthentification::ACCESS_LEVEL_USER);


$router->addRoute('like', '/like/', '\tweeterapp\control\TweeterController', 'likeTweet', TweeterAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('dislike', '/dislike/', '\tweeterapp\control\TweeterController', 'dislikeTweet', TweeterAuthentification::ACCESS_LEVEL_USER);

//le chemin par défaut est le home avec tous les tweets classés du plus rècent au plus anciens
$router->setDefaultRoute('/home/');

//activation du router
$router->run();




