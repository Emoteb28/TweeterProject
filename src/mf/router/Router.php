<?php

namespace mf\router;
use tweeterapp\control\TweeterController;

 class Router extends AbstractRouter {
    /*   Une instance de HttpRequest */
    public $http_req = null;
    
    /*
     * Attribut statique qui stocke les routes possibles de l'application 
     * 
     * - Une route est reprÃ©sentÃ©e par un tableau :
     *       [ le controlleur, la methode, niveau requis ]
     * 
     * - Chaque route est stokÃ¨e dans le tableau sous la clÃ© qui est son URL 
     * 
     */
    
    static public $routes = array ();

    /* 
     * Attribut statique qui stocke les alias des routes
     *
     * - Chaque URL est stockÃ© dans une case ou la clÃ© est son alias 
     *
     */

    static public $aliases = array ();
    
    /*
     * Un constructeur 
     * 
     *  - initialiser l'attribut httpRequest
     * 
     */ 
    /*
    public function __construct(){
        
        parent::__construct();
    }
    
    /*
     * MÃ©thode run : execute une route en fonction de la requÃªte 
     *    (la requÃªte est rÃ©cupÃ©rÃ©e dans l'atribut $http_req)
     *
     * Algorithme :
     * 
     * - l'URL de la route est stockÃ©e dans l'attribut $path_info de 
     *         $http_request
     *   Et si une route existe dans le tableau $route sous le nom $path_info
     *     - crÃ©er une instance du controleur de la route
     *     - exÃ©cuter la mÃ©thode de la route 
     * - Sinon 
     *     - exÃ©cuter la route par dÃ©faut : 
     *        - crÃ©er une instance du controleur de la route par dÃ©fault
     *        - exÃ©cuter la mÃ©thode de la route par dÃ©fault
     * 
     */
    /*
     public function run(){

           // print_r($this->http_req->path_info);

            var_dump($this->http_req->path_info);

         if(isset(self::$routes[$this->http_req->path_info])) {
            
            $cntrl = new self::$routes[$this->http_req->path_info][0]();
            $var = self::$routes[$this->http_req->path_info][1];
            echo $cntrl->$var();
            
        }else{
            
            $cntrl = new self::$routes[self::$aliases['default']][0]();
            $var = self::$routes[self::$aliases['default']][1];
            echo $cntrl->$var();
            
        }


    }

    /*
     * MÃ©thode urlFor : retourne l'URL d'une route depuis son alias 
     * 
     * ParamÃ¨tres :
     * 
     * - $route_name (String) : alias de la route
     * - $param_list (Array) optionnel : la liste des paramÃ¨tres si l'URL prend
     *          de paramÃ¨tre GET. Chaque paramÃ¨tre est reprÃ©sentÃ© sous la forme
     *          d'un tableau avec 2 entrÃ©es : le nom du paramÃ¨tre et sa valeur  
     *
     * Algorthme:
     *  
     * - Depuis le nom du scripte et l'URL stockÃ© dans self::$routes construire 
     *   l'URL complÃ¨te 
     * - Si $param_list n'est pas vide 
     *      - Ajouter les paramÃ¨tres GET a l'URL complÃ¨te    
     * - retourner l'URL
     *
     */
    /*
    
    public function urlFor($route_name, $param_list=[]){

        $url = $this->http_req->script_name;
         $url .= self::$aliases[$route_name];

        if(!empty($param_list)){
            foreach($param_list as $key => $val){
                $url .= "?".$key."=".$val;
                break;
            }
        }

        return $url;

    }

    /*
     * MÃ©thode setDefaultRoute : fixe la route par dÃ©fault
     * 
     * ParamÃ¨tres :
     * 
     * - $url (String) : l'URL de la route par default
     *
     * Algorthme:
     *  
     * - ajoute $url au tableau self::$aliases sous la clÃ© 'default'
     *
     */
    /*
    public function setDefaultRoute($url){
         self::$aliases['default'] =  $url;
    }
   
    /* 
     * MÃ©thode addRoute : ajoute une route a la liste des routes 
     *
     * ParamÃ¨tres :
     * 
     * - $name (String) : un nom pour la route
     * - $url (String)  : l'url de la route
     * - $ctrl (String) : le nom de la classe du ContrÃ´leur 
     * - $mth (String)  : le nom de la mÃ©thode qui rÃ©alise la fonctionalitÃ© 
     *                     de la route
     *
     *
     * Algorithme :
     *
     * - Ajouter le tablau [ $ctrl, $mth ] au tableau self::$route 
     *   sous la clÃ© $url
     * - Ajouter la chaÃ®ne $url au tableau self::$aliases sous la clÃ© $name
     *
     */
    /*
   public function addRoute($name, $url, $ctrl, $mth){

         self::$routes[$url] = [ $ctrl, $mth ] ;
         self::$aliases[$name] = $url; 

    }

    public static function executeRoute(){

        

        setDefaultRoute($url);




    }

    */


    public function __construct(){

        parent::__construct();

    }


    public function addRoute($name, $url, $ctrl, $mth){

        self::$routes[$url] = [ $ctrl, $mth ] ;
        self::$aliases[$name] = $url; 

    }


    public function setDefaultRoute($url){
        self::$aliases['default'] =  $url; 
    }


     public function run(){
         
        if(isset(self::$routes[$this->http_req->path_info])){
            
            $cntrl = new self::$routes[$this->http_req->path_info][0]();
            $var = self::$routes[$this->http_req->path_info][1];
            echo $cntrl->$var();
            
        }else{
            
            $cntrl = new self::$routes[self::$aliases['default']][0]();
            $var = self::$routes[self::$aliases['default']][1];
            echo $cntrl->$var();
            
        }

     }

     static function executeRoute($alias){
            $cntrl = new self::$routes[self::$aliases[$alias]][0]();
            $var = self::$routes[self::$aliases[$alias]][1];
            echo $cntrl->$var();
     }






     public function urlFor($route_name, $param_list=[]){
         $url = $this->http_req->script_name;
         $url .= self::$aliases[$route_name];

        if(!empty($param_list)){
            foreach($param_list as $key => $val){
                $url .= "?".$key."=".$val;
                break;
            }
        }
        return $url;
     }

}



   
