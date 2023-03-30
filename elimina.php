<?php
$conn = new mysqli("localhost","root","","db_bed_and_breakfast");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if(isset($_POST["id_prenotazione"])) {
    $id_prenotazione = $_POST["id_prenotazione"];

    $sql = "DELETE FROM Prenotazioni WHERE id = $id_prenotazione";

    if ($conn->query($sql) === TRUE) {
        echo "Prenotazione eliminata con successo";
        
        header("refresh:1;url=http://localhost:3000/elimina_prenotazioni.php");
        
    } else {
        echo "Errore nell'eliminazione della prenotazione: " . $conn->error;
    }
} else {
    echo "Nessuna prenotazione selezionata per l'eliminazione";
}
$conn->close();
?>
