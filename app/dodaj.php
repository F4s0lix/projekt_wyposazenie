<?php
    function pokaz_wiadomosc($wiadomosc, $error)
    {
        #funkcja wyświetla wiadomość o błędzie/sukcesie operacji
        $klasa = $error?'error':'success';
        echo '<div class="'.$klasa.'" id="wiadomosc">'.$wiadomosc.'</div>';
        echo '';
    }
    if (isset($_POST['nazwa'], $_POST['ilosc'], $_POST['miejsce'], $_POST['stan'], $_FILES['faktura']))
    {
        #blok dodaje przedmiot zapewniając ochronę przed XSS i pokazując ewentualne błędy
        $nazwa = htmlspecialchars($_POST['nazwa']);
        $ilosc = htmlspecialchars($_POST['ilosc']);
        $miejsce = htmlspecialchars($_POST['miejsce']);
        $stan = htmlspecialchars($_POST['stan']);
        $srodek = isset($_POST['srodek']);
        $faktura = $_FILES['faktura'];
        if(empty($nazwa)) pokaz_wiadomosc('błąd: pusta nazwa', true);
        else if(empty($ilosc)) pokaz_wiadomosc('błąd: pusta ilość', true);
        else if(empty($miejsce)) pokaz_wiadomosc('błąd: brak miejsca', true);
        else if(empty($stan)) pokaz_wiadomosc('błąd: brak stanu', true);
        else if(!$_FILES['faktura']['error'] === UPLOAD_ERR_OK) pokaz_wiadomosc('błąd: problem z przesłaniem faktury', true);
        else
        {
            require_once '../operations/database.php';
            $baza = new baza_operacje;
            $zapis_status = $baza->dodaj_przedmiot($nazwa, $ilosc, $miejsce, $stan, $srodek, $faktura);
            if($zapis_status) pokaz_wiadomosc('dodano przedmiot', false);
            else pokaz_wiadomosc("błąd: $zapis_status", true);
        }
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dodaj przedmiot</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav>
        <header>
            <a href="../index.php">REJESTR WYPOSAŻENIA</a>
        </header>
        <div>
            <div><a href="dodaj.php">dodaj</a></div>
            <div><a href="edytuj.php">edytuj</a></div>
            <div><a href="wypozycz.php">wypożycz</a></div>
            <div><a href="wyszukaj.php">wyszukaj</a></div>
            <div><a href="osoba.php">osoby</a></div>
        </div>
    </nav>
    <main>
        <form action="dodaj.php" method="post" enctype="multipart/form-data">
            <label for="nazwa">nazwa</label>
            <input type="text" name="nazwa" id="nazwa">
            <label for="ilosc">ilość</label>
            <input type="number" name="ilosc" id="ilosc">
            <label for="faktura">faktura</label>
            <input type="file" name="faktura" id="faktura">
            <label for="miejsce">miejsce</label>
            <input type="text" name="miejsce" id="miejsce">
            <label for="stan">stan</label>
            <input type="text" name="stan" id="stan">
            <label for="srodek">środek trwały</label>
            <input type="checkbox" name="srodek" id="srodek">
            <input type="submit" value="dodaj">
        </form>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl"> Jan Wawrzyniak</a>
    </footer>
    <script>
        //usuwa po 5 sekundach wiadomość
        setInterval(function(){
            var wiadomosc = document.getElementById('wiadomosc');
            if(wiadomosc) wiadomosc.remove();
        }, 5000);
    </script>
</body>
</html>