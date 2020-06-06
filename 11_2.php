<!DOCTYPE html>
<html lang="it">
  <head>
    <title>PHP 11_2</title>
    <meta charset="utf-8">
    <meta name="author" content="Lorenzo Costa" >
    <link rel=stylesheet href=fogliodistile.css>
  </head>
  <?php
  $con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','automobili');
  if (mysqli_connect_errno()){//Testare l'errore del db
    //die ('<p class="err">Failed to connect to MySQL: ' . mysqli_connect_error()."</p>");
    printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
  }elseif(empty($_REQUEST["maxPrezzo"])) {
    echo '<p class="err">Non hai inserito alcun valore di filtraggio per il prezzo</p>';
  }else{
    //acquisisco la variabile che influenza la mia query sql
    $maxPrezzo=$_REQUEST["maxPrezzo"];
    //operazioni sul db
    $query="SELECT `NOME`,`QUANTITA`,`COSTO` FROM `auto_nuove` WHERE `COSTO`<?";
    if($stmt=mysqli_prepare($con,$query)){
      mysqli_stmt_bind_param($stmt,"d",$maxPrezzo);//associo a ? il $maxPrezzo
      mysqli_stmt_execute($stmt);//eseguo lo statement
      //operazioni sul result set
      //voglio controllare il risultato: se ricevo un risultato con 0 righe allora stampo un messaggio di errore
      if(mysqli_num_rows(mysqli_stmt_get_result($stmt))==0){die('<p class="err">Nessuna auto disponibile con un prezzo minore di quello selezionato</p>'); }
      mysqli_stmt_execute($stmt);//chiamo di nuovo l'execute perche altrimenti devo avere a che fare con un result_set e non con uno statement
      mysqli_stmt_bind_result($stmt,$nome,$quantita,$costo);//eseguo bind tra colonne ottenute e variabili dentro le quali voglio avere i valori delle colonne ottenute
   ?>
  <body>
      <h1>Pagina realizzata con PHP</h1>
      <p>Autore della pagina: Lorenzo Costa</p>
      <table>
        <thead>
          <th>Prodotto</th><th>Quantit&agrave disponibile</th><th>Costo</th>
        </thead>
        <?php
          while(mysqli_stmt_fetch($stmt)){//stmt_fetch associa per ogni riga i valori di una colonna alla variabile definita dentro stmt_bind_result
            echo("<tr><td>".$nome."</td><td>".$quantita."</td><td>".$costo."</td></tr>");
            //printf("<tr><td>%s</td><td>%d</td><td>%f</td></tr>",$row["NOME"],$row["QUANTITA"],$row["COSTO"]);
          }
        }else{
         ?>
      </table>
      <?php

        echo '<p class="err">Errore nell\'esecuzione della query: Error code-->'.mysqli_stmt_errno($stmt).'\n'.mysqli_stmt_error($stmt).'</p>';
      }
          //rilascio la memoria associata al result set
          mysqli_stmt_close($stmt);
          //chiudo la connessione
          if(!mysqli_close($con)){
            printf("<p>Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
          }
        }
       ?>
  </body>
</html>
