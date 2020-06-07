<?php
  $session=true;
  if(session_status()==PHP_SESSION_DISABLED){
    $session=false;
  }elseif(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
    if(!isset($_REQUEST["procediAcquisto"])){
      die("Errore nella comunicazione tra le pagine.");
    }else {
      $prodotti=$_SESSION["procediAcquisto"];
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
      $con=mysqli_connect('172.17.0.60','uReadWrite','SuperPippo!!!','automobili');
      if (mysqli_connect_errno()){//Testare l'errore del db, FUNZIONA
        //die ('<p class="err">Failed to connect to MySQL: ' . mysqli_connect_error()."</p>");
        printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
      }
      $successo=TRUE;//flag per evitare la stampa dell'avvenuto acquisto con successo in caso di errori
      foreach ($prodotti as $key => $value) {
        if($key!="acquista" && $key!="PHPSESSID" && $value>0){
          $totProdottiDaAcquistare++;//è il numero esatto di prodotti che l'utente vuole acquistare
          $query="UPDATE `auto_nuove` SET `QUANTITA`=(`QUANTITA`-?) WHERE `ID`=".$key;
          if($stmt=mysqli_prepare($con,$query)){
            mysqli_stmt_bind_param($stmt,"d",$value);//associo a ? il $value
            if(mysqli_stmt_execute($stmt)==TRUE){//eseguo lo statement
              echo '<p>Acquistato il prodotto con ID='.$key.'</p>';
              $totProdottiAcquistati++;//è il numero di prodotti per i quali la query è andata a buon fine
            }else{
              echo '<p>Non acquistato il prodotto con ID='.$key.' per un errore nella query.</p>';
            }
          }else{
            echo '<p class="err">Errore nell\'esecuzione della query: Error code-->'.mysqli_stmt_errno($stmt).' '.mysqli_stmt_error($stmt).'</p>';
          }
        }elseif ($value<0){
          echo '<p>Il prodotto con ID='.$key.' aveva una quantit&agrave negativa, per questo non ne &egrave stato effettuato l\'acquisto.</p>';
          $successo=FALSE;
        }
      }
      if($totProdottiAcquistati==$totProdottiDaAcquistare && $successo==TRUE){
        echo '<p class="costoFinale">Acquisto avvenuto con successo!</p>';
      }else{
        echo '<p class="err">Attenzione! L\'acquisto di alcuni prodotti non è andato a buon fine.</p>';
      }
      //rilascio la memoria associata al result set
      mysqli_stmt_close($stmt);
      //chiudo la connessione
      if(!mysqli_close($con)){
        printf("<p>Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
      }
       ?>
  </body>
</html>
