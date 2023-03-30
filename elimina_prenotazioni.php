<?php
echo "<a href='http://localhost:3000/main.php'>Home</a><br>";

$conn = new mysqli("localhost","root","","db_bed_and_breakfast");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$sql = "SELECT p.id, c.Cognome, c.Nome, cam.Descrizione, p.DataArrivo, p.DataPartenza
        FROM Prenotazioni p 
        JOIN Clienti c ON p.Cliente = c.Codice 
        JOIN Camere cam ON p.Camera = cam.Numero";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>ID Prenotazione</th><th>Cognome</th><th>Nome</th><th>Camera</th><th>Data arrivo</th><th>Data partenza</th><th>Elimina</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["Cognome"]."</td><td>".$row["Nome"]."</td><td>".$row["Descrizione"]."</td><td>".$row["DataArrivo"]."</td><td>".$row["DataPartenza"]."</td><td><form method='post' action='http://localhost:3000/elimina.php'><input type='hidden' name='id_prenotazione' value='".$row["id"]."'/><input type='submit' value='Elimina'/></form></td></tr>";
    }
    echo "</table>";
} else {
    echo "Nessuna prenotazione trovata.";
}

echo "<a href='http://localhost:3000/aggiungi_prenotazione.php'>Aggiungi una prenotazione</a><br>";


$conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="style.css">
  </head>
  <body>
    
    
  </body>
</html>