<?php
    if(!isset($_GET['id'])) header('Location: index.php');      
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>przedmiot</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <header>
            <a href="index.php">REJESTR WYPOSAŻENIA</a> 
        </header>
        <div>
            <div><a href="app/dodaj.php">dodaj</a></div>
            <div><a href="app/edytuj.php">edytuj</a></div>
            <div><a href="app/wypozycz.php">wypożycz</a></div>
            <div><a href="app/wyszukaj.php">wyszukaj</a></div>
            <div><a href="app/osoba.php">osoby</a></div>
        </div>
    </nav>
    <main>
        <div>
            <?php
                $id = $_GET['id'];
                require_once 'operations/database.php';
                $baza = new baza_operacje;
                $dane = $baza->przemiot($id);
                echo '<span id="naglowek-produktu"><h1>ID: '.$dane['id'].' - '.$dane['nazwa'].'</h1></span>';
                
                $ilosc = $dane['ilosc'];
                $faktura = $dane['faktura'];
                $miejsce = $dane['miejsce'];
                $stan = $dane['stan'];
                $srodek = $dane['srodek_trwaly'];
                $faktura_wyswietl = empty($faktura)?'brak':"<a href='karta_przedmiotu.php?id=$id&faktura_id=$faktura'>pobierz</a>";
                echo "<span><div class='naglowek-informacji'>ilość</div><div>$ilosc</div></span>";
                echo "<span><div class='naglowek-informacji'>faktura</div><div>$faktura_wyswietl</div></span>";
                echo "<span><div class='naglowek-informacji'>miejsce</div><div>$miejsce</div></span>";
                echo "<span><div class='naglowek-informacji'>stan</div><div>$stan</div></span>";
                echo "<span><div class='naglowek-informacji'>środek trwały</div><div>$srodek</div></span>";

                if(isset($_GET['faktura_id'])) $baza->wyswielt_fakture($_GET['faktura_id']);
            ?>
        </div>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl"> Jan Wawrzyniak</a>
    </footer>
</body>
</html>