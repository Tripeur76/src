<?php
  try{
        $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8','root', 'password');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
  catch(Exception $e){
        die('Erreur :'.$e->getMessage());
    }

   $req = $bdd->query('SELECT * FROM users ORDER BY id DESC');

   echo '<ul>';
   while ($data = $req->fetchAll())
   {
       echo '<li>'.$data['fname'].' '.strtoupper($data['.name.']).'</li>';
   }
   echo '</ul>';

   $req->closeCursor(); // Fermeture de la connexion
?>
