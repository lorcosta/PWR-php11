<?php
  $session=true;
  if(session_status()==PHP_SESSION_DISABLED){
    $session=false;
  }elseif(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
    if(!isset($_REQUEST["procediAcquisto"])){
      die("Errore nella comunicazione tra le pagine.");
    }else {
      $prodotti=$_REQUEST["procediAcquisto"];
    }
  }
 ?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <title>PHP 11_5</title>
    <meta charset="utf-8">
    <meta name="author" content="Lorenzo Costa" >
    <link rel=stylesheet href=fogliodistile.css>
  </head>
  <body>
      <h1>Pagina realizzata con PHP</h1>
      <p>Autore della pagina: Lorenzo Costa</p>
      <?php
        foreach ($_REQUEST["procediAcquisto"] as $key => $value) {
          echo "<p>".$key."-->".$value."</p>";
        }
       ?>
  </body>
</html>
