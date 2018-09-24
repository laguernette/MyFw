<?php

//
// Objectif à atteindre
//
// Si
//   l'utilisateur va sur http://localhost/rest/users/1 en mode GET sans arguments
// Alors
//   renvoyer en JSON les champs de la table users correspondant à l'utilisateur #1

$r = new \MyFW\Request();

echo 'Method : ' . $r->getMethod() . '<br>';
echo 'Controller : ' . $r->getController() . '<br>';
echo 'Arguments : <br>';
echo '<pre>';
print_r($r->getArguments());
echo '</pre>';
echo '<br>';
echo 'Action : ' . $r->getAction() . '<br>';

/*
  echo '<hr>';
  echo '<pre>';
  print_r($_SERVER);
  echo '</pre>';
*/
 
  

  
  
  
  
  
