<!DOCTYPE html>
<html lang="it">
  <head>
    <title>PHP 11_4</title>
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
            $query="SELECT `ID`,`NOME`,`COSTO` FROM `auto_nuove` WHERE `ID`=".$key;
            $result=mysqli_query($con,$query);
            if(!$result){
              printf('<p class="err">Errore: query fallita.Non riesco a trovare il prodotto con ID=%d selezionato.\n %s</p>',$key,mysqli_error($con));
            }else {
              while($row=mysqli_fetch_assoc($result)){
                echo("<tr><td>".$row["ID"]."</td><td>".$row["NOME"]."</td><td>".$row["COSTO"]."</td><td>".$value."</td><td>".($row["COSTO"]*$value)."</td></tr>");
                $totalCost+=($row["COSTO"]*$value);
              }
            }
          }
          //rilascio la memoria associata al result set
          mysqli_free_result($result);
          //chiudo la connessione
          if(!mysqli_close($con)){
            printf("<p class=\"err\">Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
          }
            //printf("<tr><td>%s</td><td>%d</td><td>%f</td></tr>",$row["NOME"],$row["QUANTITA"],$row["COSTO"]);
            //echo("<tr><td>".$row["ID"]."</td><td>".$row["NOME"]."</td><td>".$row["COSTO"]."</td><td><input type=\"number\" name=".$row["ID"]." value=\"0\"></td></tr>");
         ?>
      </table>
      <?php
      echo "<p class=\"costoFinale\">Costo totale degli oggetti selezionati: ".$totalCost."€</p>";
       ?>
  </body>
</html>
