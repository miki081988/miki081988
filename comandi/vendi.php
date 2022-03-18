<?php
session_start();
include_once("classi.php");
$controllo = $_POST['controllo'];

if ($controllo == "dati") {
    $n_prodotto = $_POST['new_prodotto'];
    $n_quantita = $_POST['new_quantita'];
    $n_pacquisto = $_POST['new_pacquisto'];
    if (empty($n_prodotto) || empty($n_quantita) || empty($n_pacquisto)) {
        $_SESSION['error'] = "Devi compilare tutti i campi";
        header("Location: ../index.php");
    } else {
        try {
            $con = new PDO('mysql:host=localhost;dbname=magazzino;charset=utf8', 'root', 'password');
            $db = new Comandi($con);
            $id = $db->verifica_prodotto($n_prodotto);
            if (!empty($id)) {
                $db->aggiorna_prodotto($n_prodotto, $n_pacquisto, $n_quantita);
                $_SESSION['aggiorno_carica'] = "magazzino aggiornato";
            } else {
                $db->carica_dati($n_prodotto, $n_pacquisto, $n_quantita);
                $_SESSION['caricato'] = "prodotto inserito correttamente!";
            }
        } catch (PDOException $error) {
            echo $error;
        }
    }
} else {
    if ($_FILES["fileToUpload"]["size"] == 0) {
        $_SESSION['error'] = "Devi caricare il file";
    } else {




        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;



        // Check if file already exists
        if (file_exists($target_file)) {
            $_SESSION['error'] = "Il file è già presente.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 50000000) {
            $_SESSION['error'] = "Il file è troppo grande";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        try {
            $con = new PDO('mysql:host=localhost;dbname=magazzino;charset=utf8', 'root', 'password');
            $db = new comandi($con);
            $db->carica_file($target_file);
            $_SESSION['caricato'] = "file caricato correttamente";
        } catch (PDOException $error) {
            echo $error;
        }
    }
}
header("Location: ../index.php");
