<?php
namespace tweeterapp\model;
class Tweet extends \Illuminate\Database\Eloquent\Model {

       protected $table      = 'tweet';  
       protected $primaryKey = 'id';     
       public    $timestamps = true;  
       
       public function author()
       {
           return $this->belongsTo('tweeterapp\model\User', 'author');
       }

       public function likedBy()
       {
           return $this->belongsToMany('tweeterapp\model\User', 'like', 'tweet_id', 'user_id');
       }
       
}