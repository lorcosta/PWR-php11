<?php
  $session=true;
  if(session_status()==PHP_SESSION_DISABLED){
    $session=false;
  }elseif(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
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
  <?php
  $con=mysqli_connect('172.17.0.60','admin','Un9BI8FENOUD','automobili');
  if (mysqli_connect_errno()){//Testare l'errore del db
    //die ('<p class="err">Failed to connect to MySQL: ' . mysqli_connect_error()."</p>");
    printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
  }else {
    //operazioni sul db
    $query="SELECT `ID`,`NOME`,`COSTO` FROM `auto_nuove`";
    $result=mysqli_query($con,$query);
    if(!$result){
      printf('<p class="err">Errore: query fallita. %s</p>',mysqli_error($con));
    }
    else{
      //operazioni sul result set
   ?>
  <body>
    <?php
    if(!$session) echo '<p>Sessioni disabilitate, impossibile continuare</p>';
      ?>
      <h1>Pagina realizzata con PHP</h1>
      <p>Autore della pagina: Lorenzo Costa</p>
      <form action="11_5B.php" method="get">
      <table>
        <thead>
          <th>Identificativo Prodotto</th><th>Nome Prodotto</th><th>Costo unitario</th><th>Quantit&agrave desiderata</th>
        </thead>
        <?php
          while($row=mysqli_fetch_assoc($result)){
            echo("<tr><td>".$row["ID"]."</td><td>".$row["NOME"]."</td><td>".$row["COSTO"]."</td><td><input type=\"number\" name=".$row["ID"]." value=\"0\"></td></tr>");
            //printf("<tr><td>%s</td><td>%d</td><td>%f</td></tr>",$row["NOME"],$row["QUANTITA"],$row["COSTO"]);
          }
         ?>
      </table>
      <?php
          //rilascio la memoria associata al result set
          mysqli_free_result($result);
        }
        //chiudo la connessione
        if(!mysqli_close($con)){
          printf("<p>Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
        }
      }
       ?>

         <input type="submit" name="acquista" value="Procedi all'acquisto">
      </form>
  </body>
</html>
