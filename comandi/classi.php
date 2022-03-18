<?php

class Comandi
{
    public $connessione;
    public function __construct($connessione)
    {
        $this->connessione = $connessione;
    }

    private function calcolo_prezzo($prezzo_acquisto)
    {
        return $prezzo_acquisto + ($prezzo_acquisto / 100) * 10;
    }
    //Funzione carica dati manualmente
    public function carica_dati($prodotto, $prezzoa, $quantita)
    {
        $prezzov = $this->calcolo_prezzo($prezzoa);
        $sql = "INSERT INTO deposito SET nome_prodotto = :prodotto, prezzo_vendita = :vendita, prezzo_acquisto = :acquisto, quantita = :quantita;";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam("prodotto", $prodotto, PDO::PARAM_STR);
        $stm->bindParam("vendita", $prezzov);
        $stm->bindParam("acquisto", $prezzoa);
        $stm->bindParam("quantita", $quantita, PDO::PARAM_INT);
        $stm->execute();
    }
    //Funzione estrae prezzo prodotto
    public function estrai_prezzo($prodotto)
    {
        $sql = "SELECT prezzo_vendita FROM deposito WHERE nome_prodotto= :prodotto;";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam("prodotto", $prodotto, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetchAll();
    }
    //Funzione aggiorno prezzo vendita se maggiore di quello precedente
    public function cambio_prezzo($prezzo_acquisto, $nome_prodotto)
    {
        $new_prezzo = $this->calcolo_prezzo($prezzo_acquisto);
        $old_prezzo = $this->estrai_prezzo($nome_prodotto);
        if ($new_prezzo > $old_prezzo[0]['prezzo_vendita']) {
            $prezzov = $new_prezzo;
        } else {
            $prezzov = $old_prezzo[0]['prezzo_vendita'];
        }
        return $prezzov;
    }
    //Funzione verifica prodotto già presente db
    public function verifica_prodotto($nome_prodotto)
    {
        $sql = "SELECT id FROM deposito WHERE nome_prodotto= :prodotto;";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam("prodotto", $nome_prodotto, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetchAll();
    }
    //Funzione se prodotto è presente aggiorno i campi
    public function aggiorna_prodotto($nome_prodotto, $prezzo_acquisto, $quantita)
    {
        $quantita_db = $this->calcola_quantita($nome_prodotto);
        $new_quantita = $quantita_db[0]['quantita'] + $quantita;
        $prezzov = $this->cambio_prezzo($prezzo_acquisto, $nome_prodotto);
        $id = $this->verifica_prodotto($nome_prodotto);
        $sql = "UPDATE deposito SET prezzo_vendita= :vendita, prezzo_acquisto= :acquisto, quantita=:quantita WHERE id= :id;";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam("vendita", $prezzov);
        $stm->bindParam("acquisto", $prezzo_acquisto);
        $stm->bindParam("quantita", $new_quantita, PDO::PARAM_INT);
        $stm->bindParam("id", $id[0]['id'], PDO::PARAM_INT);
        $stm->execute();
    }
    //Funzione calcola quantità prodotto
    public function calcola_quantita($nome_prodotto)
    {
        $sql = "SELECT quantita FROM deposito WHERE nome_prodotto= :prodotto;";
        $stm = $this->connessione->prepare($sql);
        $stm->bindParam("prodotto", $nome_prodotto, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetchAll();
    }
    //Funzione carica file file csv
    public function carica_file($target_file)
    {
        $myfile = fopen($target_file, "r") or die("unable to open file!");
        while (!feof($myfile)) {
            $dati = explode(',', fgets($myfile));
            $deposito[] = $dati;
        }
        foreach ($deposito as $key => $prodotto) {
            $a = $this->verifica_prodotto($prodotto[0]);
            if (empty($a)) {
                $this->carica_dati($prodotto[0], $prodotto[1], $prodotto[2]);
            } else {
                $this->aggiorna_prodotto($prodotto[0], $prodotto[1], $prodotto[2]);
            }
        }
        fclose($myfile);
        unlink($target_file);
    }
    //Funzione stampa opzioni prodotti
    public function stampa_opzioni(){
        $sql= "SELECT id, nome_prodotto, quantita, prezzo_vendita FROM deposito";
        $result= $this->connessione->query($sql);
        return $result->fetchAll();
    }
    
}
