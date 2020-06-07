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
      //chek dei dati passati dal form
      if(!isset($_REQUEST["NOME"])){
        echo '<p class="err">Attenzione! Il campo "NOME" non è stato compilato.</p>';
      }elseif(!isset($_REQUEST["QUANTITA"])){
        echo '<p class="err">Attenzione! Il campo "QUANTIT&Agrave" non è stato compilato.</p>';
      }elseif(!isset($_REQUEST["COSTO"])){
        echo '<p class="err">Attenzione! Il campo "COSTO" non è stato compilato.</p>';
      }elseif($_REQUEST["QUANTITA"]<=0){
        echo '<p class="err">Non puoi inserire un nuovo prodotto che abbia una quantit&agrave minore o uguale a zero!</p>';
      }elseif($_REQUEST["COSTO"]<=0){
        echo '<p class="err">Non puoi inserire un nuovo prodotto che abbia una costo minore o uguale!</p>';
      }elseif($_REQUEST["COSTO"]<=0){
        //controllare che il costo rispetti la regex=^\d+(.\d{2})?$
      }else{
        $nome=$_REQUEST["NOME"];
        $quantita=$_REQUEST["QUANTITA"];
        $costo=$_REQUEST["COSTO"];
        $con=mysqli_connect('172.17.0.60','uReadWrite','SuperPippo!!!','automobili');
        if (mysqli_connect_errno()){//Testare l'errore del db, FUNZIONA
          //die ('<p class="err">Failed to connect to MySQL: ' . mysqli_connect_error()."</p>");
          printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
        }
        $query="INSERT INTO `auto_nuove`(`ID`, `NOME`, `QUANTITA`, `COSTO`) VALUES (`ID`,?,?,?)";
        if($stmt=mysqli_prepare($con,$query)){
          mysqli_stmt_bind_param($stmt,"sid",$nome,$quantita,$costo);
          if(mysqli_stmt_execute($stmt)){
            echo '<p class="costoFinale">Inserimento avvenuto con successo.</p>';
          }else{
            echo '<p class="err">Errore durante l\'inserimento del prodotto.</p>';
          }
        }else{
          echo '<p class="err">Errore nell\'esecuzione della query: Error code-->'.mysqli_stmt_errno($stmt).' '.mysqli_stmt_error($stmt).'</p>';
        }
      }
      mysqli_stmt_close($stmt);
      //chiudo la connessione
      if(!mysqli_close($con)){
        printf("<p>Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
      }
       ?>
  </body>
</html>
