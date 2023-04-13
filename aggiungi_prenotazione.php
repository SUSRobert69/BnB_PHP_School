<?php
$conn = new mysqli("localhost", "root", "", "db_bed_and_breakfast");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$numero_camera = '';
$data_arrivo = '';
$data_partenza = '';
$nome_cliente = '';
$cognome_cliente = '';

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_camera = $_POST['numero_camera'];
    $data_arrivo = $_POST['data_arrivo'];
    $data_partenza = $_POST['data_partenza'];
    $nome_cliente = $_POST['nome_cliente'];
    $cognome_cliente = $_POST['cognome_cliente'];

    if (empty($numero_camera)) {
        $errors[] = "Inserire il numero della camera.";
    }

    if (empty($data_arrivo)) {
        $errors[] = "Inserire la data di arrivo.";
    }

    if (empty($data_partenza)) {
        $errors[] = "Inserire la data di partenza.";
    }

    if (empty($nome_cliente)) {
        $errors[] = "Inserire il nome del cliente.";
    }

    if (empty($cognome_cliente)) {
        $errors[] = "Inserire il cognome del cliente.";
    }

    if (count($errors) == 0) {
        // Check if room is available for the selected dates
        $query_prenotazione = "SELECT * FROM Prenotazioni WHERE Camera = $numero_camera AND ((DataArrivo <= '$data_arrivo' AND DataPartenza > '$data_arrivo') OR (DataArrivo < '$data_partenza' AND DataPartenza >= '$data_partenza'))";
        $result_prenotazione = $conn->query($query_prenotazione);
        if ($result_prenotazione->num_rows > 0) {
            $errors[] = "La camera selezionata non è disponibile per le date indicate.";
        } else {
           // Check if the client already exists, otherwise insert it
            $query_cliente = "SELECT Codice FROM Clienti WHERE Nome = '$nome_cliente' AND Cognome = '$cognome_cliente'";
            $result_cliente = $conn->query($query_cliente);

            if ($result_cliente->num_rows > 0) {
                $row_cliente = $result_cliente->fetch_assoc();
                $codice_cliente = $row_cliente['Codice'];
            } else {
                // Create a new temporary customer entry
                $query_nuovo_cliente = "INSERT INTO ClientiTemp (Nome, Cognome) VALUES ('$nome_cliente', '$cognome_cliente')";
                $conn->query($query_nuovo_cliente);
                $codice_cliente = $conn->insert_id;
            }

            // Insert the new reservation
            $query_prenotazione = "INSERT INTO Prenotazioni (Cliente, Camera, DataArrivo, DataPartenza) VALUES ('$codice_cliente', '$numero_camera', '$data_arrivo', '$data_partenza')";
            $conn->query($query_prenotazione);

            header("Location: prenotazioni.php");
            exit();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Aggiungi Prenotazione</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

</head>
<body>
<div class="container mt-4">
<h2>Aggiungi Prenotazione</h2>
<?php if (count($errors) > 0) : ?>
<div class="alert alert-danger">
<ul>
<?php foreach ($errors as $error) : ?>
<li><?php echo $error; ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
<form method="post">
<div class="form-group">
<label for="numero_camera">Numero Camera:</label>
<input type="text" class="form-control" id="numero_camera" name="numero_camera" value="<?php echo $numero_camera; ?>">
</div>
<div class="form-group">
<label for="data_arrivo">Data Arrivo:</label>
<input type="date" class="form-control" id="data_arrivo" name="data_arrivo" value="<?php echo $data_arrivo; ?>">
</div>
<div class="form-group">
<label for="data_partenza">Data Partenza:</label>
<input type="date" class="form-control" id="data_partenza" name="data_partenza" value="<?php echo $data_partenza; ?>">
</div>
<div class="form-group">
<label for="nome_cliente">Nome Cliente:</label>
<input type="text" class="form-control" id="nome_cliente" name="nome_cliente" value="<?php echo $nome_cliente; ?>">
</div>
<div class="form-group">
<label for="cognome_cliente">Cognome Cliente:</label>
<input type="text" class="form-control" id="cognome_cliente" name="cognome_cliente" value="<?php echo $cognome_cliente; ?>">
</div>
<button type="submit" class="btn btn-primary">Aggiungi</button>
</form>
</div>
</body>

</html>