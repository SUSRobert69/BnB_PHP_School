<?php
$conn = new mysqli("localhost","root","","db_bed_and_breakfast");

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
        $query_cliente = "SELECT Codice FROM Clienti WHERE Nome = '$nome_cliente' AND Cognome = '$cognome_cliente'";
        $result_cliente = $conn->query($query_cliente);

        if ($result_cliente->num_rows > 0) {
            $row_cliente = $result_cliente->fetch_assoc();
            $codice_cliente = $row_cliente['Codice'];
        } else {
            $query_nuovo_cliente = "INSERT INTO Clienti (Nome, Cognome) VALUES ('$nome_cliente', '$cognome_cliente')";
            $conn->query($query_nuovo_cliente);

            $codice_cliente = $conn->insert_id;
        }

        $query_prenotazione = "INSERT INTO Prenotazioni (Cliente, Camera, DataArrivo, DataPartenza) VALUES ('$codice_cliente', '$numero_camera', '$data_arrivo', '$data_partenza')";
        $conn->query($query_prenotazione);

        header("Location: prenotazioni.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
  <header>   <link rel="stylesheet" href="style.css">
</header>
<body>
  <h1>Aggiungi Prenotazione</h1>
  <?php if (count($errors) > 0) : ?>
    <div class="error">
      <?php foreach ($errors as $error) : ?>
        <p><?php echo $error ?></p>
      <?php endforeach ?>
    </div>
  <?php endif ?>
  <form method="POST" action="">
    <div>
      <label for="numero_camera">Numero Camera:</label>
      <input type="text" id="numero_camera" name="numero_camera" value="<?php echo $numero_camera ?>">
    </div>
    <div>
      <label for="data_arrivo">Data Arrivo:</label>
      <input type="date" id="data_arrivo" name="data_arrivo" value="<?php echo $data_arrivo ?>">
    </div>
    <div>
      <label for="data_partenza">Data Partenza:</label>
      <input type="date" id="data_partenza" name="data_partenza" value="<?php echo $data_partenza ?>">
    </div>
    <div>
      <label for="nome_cliente">Nome:</label>
      <input type="text" id="nome_cliente" name="nome_cliente" value="<?php echo $nome_cliente ?>">
    </div>
    <div>
      <label for="cognome_cliente">Cognome:</label>
      <input type="text" id="cognome_cliente" name="cognome_cliente" value="<?php echo $cognome_cliente ?>">
    </div>
    <div>
  <button type="submit" value="Invia"> Invia </button>
    </div>
  </form>
</body>
</html>
