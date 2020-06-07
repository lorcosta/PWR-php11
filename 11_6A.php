<!DOCTYPE html>
<html lang="it">
  <head>
    <title>PHP 11_6A</title>
    <meta charset="utf-8">
    <meta name="author" content="Lorenzo Costa" >
    <link rel=stylesheet href=fogliodistile.css>
    <script>
    "use strict";
    function checkQuantita(quantita){
      var regex=/^\d+$/;
      var quant=document.getElementById(quantita).value;
      if(!regex.test(quant)){
        window.alert("Devi inserire un numero intero positivo maggiore di zero.");
        return;
      }
    }
    function checkCosto(costo){
      var regex=/^\d+(.\d{2})?$/;
      var costo=document.getElementById(costo).value;
      if(!regex.test(costo)){
        window.alert("Devi inserire un costo positivo e se inserisci i decimali questi devono essere con la precisione dei centesimi.");
        return;
      }
    }
    </script>
  </head>
  <?php
  $con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','automobili');
  if (mysqli_connect_errno()){//Testare l'errore del db
    //die ('<p class="err">Failed to connect to MySQL: ' . mysqli_connect_error()."</p>");
    printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
  }else {
    //operazioni sul db
    $query="SELECT `ID`,`NOME`,`QUANTITA`,`COSTO` FROM `auto_nuove`";
    $result=mysqli_query($con,$query);
    if(!$result){
      printf('<p class="err">Errore: query fallita. %s</p>',mysqli_error($con));
    }
    else{
      //operazioni sul result set
   ?>
  <body>
      <h1>Pagina realizzata con PHP</h1>
      <p>Autore della pagina: Lorenzo Costa</p>
      <p>Questi sono i prodotti disponibili: </p>
      <table>
        <thead>
          <th>ID</th><th>Nome Prodotto</th><th>Quantit&agrave disponibile</th><th>Costo</th>
        </thead>
        <?php
          while($row=mysqli_fetch_assoc($result)){
            echo("<tr><td>".$row["ID"]."</td><td>".$row["NOME"]."</td><td>".$row["QUANTITA"]."</td><td>".$row["COSTO"]."</td></tr>");
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
      <p>Se vuoi inserire un nuovo prodotto usa questo form:</p>
      <form action="11_6B.php" method="get">
        <label for="NOME">Nome:</label><input type="text"  id="NOME" name="NOME" placeholder="Inserisci il nome">
        <label for="QUANTITA">Quantit&agrave:</label><input type="number" id="QUANTITA" name="QUANTITA" value="1" onchange="checkQuantita('QUANTITA')">
        <label for="COSTO">Costo:</label><input type="number" id="COSTO" name="COSTO" placeholder="Inserisci il costo" onchange="checkCosto('COSTO')">
        <input type="submit" value="Inserisci prodotto">
      </form>
  </body>
</html>
