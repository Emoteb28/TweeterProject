<?php

namespace mf\control;

abstract class AbstractController {
  
  /* Stocker l'objet HttpRequest */
  protected $request=null; 
  
  /*Constructeur : */
  public function __construct(){
      $this->request = new \mf\utils\HttpRequest() ;
  }
  
}


  