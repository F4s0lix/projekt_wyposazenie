<?php
    echo $_POST['srodek'];
    if(isset($_POST['id'], $_POST['ilosc'], $_POST['miejsce'], $_POST['stan'])){
        require_once 'database.php';
        $baza = new baza_operacje;
        $id = htmlspecialchars($_POST['id']); #TODO: zmiana faktury na inną
        $ilosc = htmlspecialchars($_POST['ilosc']);
        #$plik = $_POST['plik'];
        $miejsce = htmlspecialchars($_POST['miejsce']);
        $stan = htmlspecialchars($_POST['stan']);
        $srodek = $_POST['srodek']?1:0;
        $baza->edytuj_przedmiot($id, $ilosc, $miejsce, $stan, $srodek);
            header("Location: ../karta_przedmiotu.php?id=$id");
    }
    else header("Location: ../app/edytuj.php?id=$id");
?>