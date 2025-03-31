<?php
    #plik edytuje dany przedmiot i przekierowuje na jego kartę
    if(isset($_POST['id'], $_POST['ilosc'], $_POST['miejsce'], $_POST['stan'])){
        require_once 'database.php';
        $baza = new baza_operacje;
        $id = htmlspecialchars($_POST['id']);
        $ilosc = htmlspecialchars($_POST['ilosc']);
        $plik = $_FILES['plik'];
        $miejsce = htmlspecialchars($_POST['miejsce']);
        $stan = htmlspecialchars($_POST['stan']);
        $srodek = isset($_POST['srodek'])?1:0;
        $baza->edytuj_przedmiot($id, $ilosc, $miejsce, $stan, $srodek, $plik);
            header("Location: ../karta_przedmiotu.php?id=$id");
    }
    else header("Location: ../app/edytuj.php?id=$id");
?>