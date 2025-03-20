<?php
    if(isset($_GET['id'])) $id = $_GET['id'];
    require_once '../operations/database.php';
    $baza = new baza_operacje;
    
    function brak_id(){
        echo <<<FORM
            <form method='GET' action='edytuj.php' class='formularz_id'>
                <label for='id'>Podaj ID przedmiotu: </label>
                <input type='number' name='id' id='id'>
                <input type='submit' value='Wyślij'>
            </form>
FORM;
    }
    function jest_id(){
        global $id;
        global $baza;
        $dane = $baza->przemiot($id);
        echo '<span id="naglowek-produktu"><h1>Edytuj: '.$dane['nazwa'].'</h1></span>';
        
        $ilosc = $dane['ilosc'];
        $faktura = $dane['faktura'];
        $miejsce = $dane['miejsce'];
        $stan = $dane['stan'];
        $srodek = $dane['srodek_trwaly']?'checked':'';
        $faktura_wyswietl = empty($faktura)?'brak':$baza->nazwa_faktury($faktura);
        echo <<<FORM
        <form class='edycjafaktury' method='POST' action='../operations/edytuj.php' enctype='multipart/form-data'>
        <input type="hidden" name="id" value="$id">
        <span><div class='naglowek-informacji'>ilość</div><div><input type="number" value="$ilosc" name="ilosc"></div></span>
        <span><div class='naglowek-informacji'>faktura</div><div><input type="file" value="$faktura_wyswietl" name="plik"></div></span>
        <span><div class='naglowek-informacji'>miejsce</div><div><input type="text" value="$miejsce" name="miejsce"></div></span>
        <span><div class='naglowek-informacji'>stan</div><div><input type="text" value="$stan" name="stan"></div></span>
        <span><div class='naglowek-informacji'>środek trwały</div><div><input type="checkbox" $srodek name="srodek"></div></span>
        <input type="submit" value="zapisz">
        </form>
FORM;    
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>przedmiot</title>
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
        <div>
            <?php
                if(isset($_GET['id'])) jest_id();
                else brak_id();
            ?>
        </div>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl"> Jan Wawrzyniak</a>
    </footer>
</body>
</html>