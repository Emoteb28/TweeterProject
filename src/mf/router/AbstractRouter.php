<?php

namespace mf\router;

abstract class AbstractRouter {

    /*   Une instance de HttpRequest */
    
    protected $http_req = null;
    
    /*
     * Attribut statique qui stocke les routes possibles de l'application 
     * 
     * - Une route est reprÃ©sentÃ©e par un tableau :
     *       [ le controlleur, la methode, niveau requis ]
     * 
     * - Chaque route est stokÃ¨e dans le tableau sous la clÃ© qui est son URL 
     * 
     */
    
    static protected $routes = array ();

    /* 
     * Attribut statique qui stocke les alias des routes
     *
     * - Chaque URL est stockÃ© dans une case ou la clÃ© est son alias 
     *
     */

    static protected $aliases = array ();
    
    /*
     * Un constructeur 
     * 
     *  - initialiser l'attribut httpRequest
     * 
     */ 

    public function __construct(){
        $this->http_req = new \mf\utils\HttpRequest();
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
    
    abstract public function run();

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
    
    abstract public function urlFor($route_name, $param_list=[]);

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

    abstract public function setDefaultRoute($url);
   
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

    abstract public function addRoute($name, $url, $ctrl, $mth);

}