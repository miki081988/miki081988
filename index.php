<?php
session_start();
include_once("comandi/classi.php");
$con = new PDO('mysql:host=localhost;dbname=magazzino;charset=utf8', 'root', 'password');
            $db = new Comandi($con);
?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!--File CSS Icon-->
    <link rel="stylesheet" href="../Icon/css/all.css">
    <!-- File css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- Ajax -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Magazzino</title>
</head>

<body class="lavander d-flex flex-column h-100">
    <header>
        <nav class="navbar navbar-expand-sm navbar-dark bg" aria-label="Third navbar example">
            <div class="container-fluid">
                <a class="navbar-brand  " href="#"><i class="fa-solid fa-box"></i> Deposito</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarsExample03">
                    <ul class="navbar-nav mb-2 mb-sm-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Magazzino</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ">Storico</a>
                        </li>
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown"
                                aria-expanded="false">Dropdown</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown03">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li> -->
                    </ul>
                    <!-- <form>
                        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                    </form> -->
                </div>
            </div>
        </nav>
    </header>
    <main class="container py-4">
        <div class="row align-items-md-stretch">
            <div class="col-md-6">
                <div class="h-100 p-5 text-white bg rounded-3">
                    <h2>Acquisti</h2>
                    <p>Carica i tuoi ultimi acquisti nel magazzino</p>
                    <form action="comandi/carica.php" method="POST">
                        <p>Inserisci i dati</p>
                        <input type="hidden" value="dati" name="controllo">
                        <input type="text" class="form-control mb-2" placeholder="Nome Prodotto" name="new_prodotto">
                        <input type="number" class="form-control mb-2" placeholder="Quantità" name="new_quantita">
                        <!-- <div class="input-group mb-2">
                            <span class="input-group-text">€</span>
                            <input type="text" placeholder="Prezzo Vendita" class="form-control" name="new_pvendita">
                        </div> -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">€</span>
                            <input type="text" placeholder="Prezzo Acquisto Unitario" class="form-control" name="new_pacquisto">
                        </div>
                        <button class="btn btn-outline-warning" type="submit">Carica</button>
                    </form>
                    <hr>
                    <form action="comandi/carica.php" method="POST" enctype="multipart/form-data">
                        <p>Seleziona il file da caricare</p>
                        <input type="hidden" value="file" name="controllo">
                        <div class="input-group">
                            <input type="file" class="form-control" name="fileToUpload">
                            <button class="btn btn-outline-warning" type="submit" id="inputGroupFileAddon04">Carica</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="h-100 p-5 bg-light border rounded-3">
                    <h2>Vendi</h2>
                    <p>Rendi felici i tuoi clienti</p>
                    <form action="comandi/vendi.php">
                        <p>Seleziona il prodotto venduto</p>
                        <input type="hidden" value="dati" name="controllo">
                        <div class="input-group mb-2">
                            <label class="input-group-text" for="inputGroupSelect01">Prodotto</label>
                            <select class="form-select" id="inputGroupSelect01" name="vendita_prodotto">
                                <option selected>Seleziona...</option>
                                <?php
                                    foreach($db->stampa_opzioni() as $opt){
                                        echo "<option value='{$opt['id']}'>{$opt['nome_prodotto']}({$opt['quantita']})</option>";
                                    }
                                ?>
                                
                            </select>
                        </div>

                        <input type="number" class="form-control mb-2" placeholder="Quantità" name="vendita_quantita">
                        <div class="input-group mb-3">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control">
                        </div>
                        <button class="btn btn-outline-success" type="submit">Vendi</button>
                    </form>
                    <hr>
                    <form action="comandi/vendi.php" method="POST">
                        <p>Seleziona il file delle vendite</p>
                        <input type="hidden" value="file" name="controllo">
                        <div class="input-group">
                            <input type="file" class="form-control" name="new_file">
                            <button class="btn btn-outline-success" type="submit" id="inputGroupFileAddon04">Carica</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php
    if (isset($_SESSION['error'])) {
    ?>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto"> <i class="fa-solid fa-triangle-exclamation"></i> ERROR!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            </div>
        </div>
    <?php
    }
    if (isset($_SESSION['aggiorno_carica'])) {
    ?>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto"> <i class="fa-solid fa-triangle-exclamation"></i>Magazzino Aggiornato</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php
                    echo $_SESSION['aggiorno_carica'];
                    unset($_SESSION['aggiorno_carica']);
                    ?>
                </div>
            </div>
        </div>
    <?php
    }
    if (isset($_SESSION['caricato'])) {
    ?>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto"> <i class="fa-solid fa-triangle-exclamation"></i>Prodotto caricato</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php
                    echo $_SESSION['caricato'];
                    unset($_SESSION['caricato']);
                    ?>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <script>
        (function() {
            var toastLiveExample = document.getElementById("liveToast");
            var toast = new bootstrap.Toast(toastLiveExample);
            toast.show();
        })();
    </script>

    <div class="container-fluid bg mt-auto ">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3">
            <div class="col-md-4 d-flex align-items-center">
                <a href="/" class="mb-3 me-2 mb-md-0 text-light text-decoration-none lh-1"><i class="fa-solid fa-box"></i></a>
                <span class="text-light">© 2021 Company, Inc</span>
            </div>
        </footer>
    </div>
</body>

</html>