<?php
  $session=true;
  if(session_status()==PHP_SESSION_DISABLED){
    $session=false;
  }elseif(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
    $prodotti=$_SESSION["procediAcquisto"]=$_REQUEST;
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
  foreach ($_REQUEST as $key => $value) {
    if($value!=0){
      $prodotti[$key]=$value;
    }
  }
   ?>
  <body>
    <?php
    if(!$session) echo '<p>Sessioni disabilitate, impossibile continuare</p>';
      ?>
      <h1>Pagina realizzata con PHP</h1>
      <p>Autore della pagina: Lorenzo Costa</p>
      <table>
        <thead>
          <th>Identificativo Prodotto</th><th>Nome Prodotto</th><th>Costo unitario</th><th>Quantit&agrave desiderata</th><th>Costo parziale</th>
        </thead>
        <?php
        $con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','automobili');
        if (mysqli_connect_errno()){//Testare l'errore del db
          //die ('<p class="err">Failed to connect to MySQL: ' . mysqli_connect_error()."</p>");
          printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
        }
          foreach ($prodotti as $key => $value) {
            if($key!="acquista" && $key!="PHPSESSID" && $value!=0){
            $query="SELECT `ID`,`NOME`,`COSTO` FROM `auto_nuove` WHERE `ID`=".$key;
            $result=mysqli_query($con,$query);
            if(!$result){
              printf('<p class="err">Errore: query fallita.Non riesco a trovare il prodotto con ID=%d selezionato. %s</p>',$key,mysqli_error($con));
            }else {
              while($row=mysqli_fetch_assoc($result)){
                echo("<tr><td>".$row["ID"]."</td><td>".$row["NOME"]."</td><td>".$row["COSTO"]."</td><td>".$value."</td><td>".($row["COSTO"]*$value)."</td></tr>");
                $totalCost+=($row["COSTO"]*$value);
              }
            }
          }
        }
          //rilascio la memoria associata al result set
          mysqli_free_result($result);
          //chiudo la connessione
          if(!mysqli_close($con)){
            printf("<p class=\"err\">Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
          }
         ?>
      </table>
      <?php
      echo "<p class=\"costoFinale\">Costo totale degli oggetti selezionati: ".$totalCost."€</p>";
      //$_SESSION["procediAcquisto"]=$prodotti;
       ?>
       <form action="11_5C.php" method="get">
         <input type="submit" name="procediAcquisto" value="Procedi">
       </form>

  </body>
</html>
