<?php
// Connessione al database
$conn = new mysqli("localhost", "root", "", "db_bed_and_breakfast");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if (isset($_POST['elimina'])) {
    $id_prenotazione = $_POST['id_prenotazione'];

    $query_elimina = "DELETE FROM Prenotazioni WHERE Id = $id_prenotazione";
    $conn->query($query_elimina);
}

// Ottieni l'elenco delle prenotazioni dal database
$query_prenotazioni = "SELECT Prenotazioni.Id, Clienti.Nome, Clienti.Cognome, Camere.NumeroCamera, Prenotazioni.DataArrivo, Prenotazioni.DataPartenza FROM Prenotazioni JOIN Clienti ON Prenotazioni.Cliente = Clienti.Codice JOIN Camere ON Prenotazioni.Camera = Camere.Id ORDER BY Prenotazioni.Id DESC";
$result_prenotazioni = $conn->query($query_prenotazioni);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Elimina prenotazioni</title>
</head>
<body>
    <h1>Elimina prenotazioni</h1>

    <?php if ($result_prenotazioni->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Camera</th>
                <th>Data di arrivo</th>
                <th>Data di partenza</th>
                <th>Azioni</th>
            </tr>
            <?php while ($row_prenotazione = $result_prenotazioni->fetch_assoc()): ?>
                <tr>
                    <td><?= $row_prenotazione['Id'] ?></td>
                    <td><?= $row_prenotazione['Nome'] ?></td>
                    <td><?= $row_prenotazione['Cognome'] ?></td>
                    <td><?= $row_prenotazione['NumeroCamera'] ?></td>
                    <td><?= $row_prenotazione['DataArrivo'] ?></td>
                    <td><?= $row_prenotazione['DataPartenza'] ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id_prenotazione" value="<?= $row_prenotazione['Id'] ?>">
                            <button type="submit" name="elimina">Elimina</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile ?>
        </table>
    <?php else: ?>
        <p>Nessuna prenotazione trovata.</p>
    <?php endif ?>

</body>
</html>
