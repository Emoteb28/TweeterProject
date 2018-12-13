<?php

$id = 1;
$users = array (
                        array('follower'=>1, 'followee'=>2),
                        array('follower'=>2, 'followee'=>3),
                        array('follower'=>3, 'followee'=>2),
                        array('follower'=>2, 'followee'=>5),
                        array('follower'=>7, 'followee'=>5),
                        array('follower'=>5, 'followee'=>3),
                        array('follower'=>5, 'followee'=>2),
                        array('follower'=>3, 'followee'=>1),
                        array('follower'=>4, 'followee'=>2)
                );
 
foreach($users as $user){
	$userTemp = array();
   if($user['followee'] == 2){
     echo $user['follower'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$user['followee'].'</br>';
      

   }
}

echo '</br></br></br></br></br>';
   foreach($users as $user){
   if($user['followee'] == 5){
     echo $user['follower'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$user['followee'].'</br>';
   }
}
