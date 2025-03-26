<?php
    function pokaz_wiadomosc($wiadomosc, $error)
    {
        #funkcja wyświetla wiadomość o błędzie
        $klasa = $error?'error':'success';
        echo '<div class="'.$klasa.'" id="wiadomosc">'.$wiadomosc.'</div>';
        echo '';
    }
    if(isset($_POST['email'], $_POST['numer']))
    {
        $email = htmlspecialchars($_POST['email']);
        $numer = htmlspecialchars($_POST['numer']);
        if(empty($email)) pokaz_wiadomosc('błąd: pusty email', true);
        else
        {
            require_once '../operations/database.php';
            $baza = new baza_operacje;
            $zapis_status = $baza->dodaj_osobe($email, $numer);
            if($zapis_status) pokaz_wiadomosc('dodano osobę', false);
            else pokaz_wiadomosc("błąd: $zapis_status", true);
        }
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dodaj osobę</title>
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
        <form action="dodaj_osobe.php" method="post">
            <label for="email">email</label>
            <input type="email" name="email" id="email" required>
            <label for="numer">numer</label>
            <input type="text" name="numer" id="numer">
            <input type="submit" value="dodaj">
        </form>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl">Jan Wawrzyniak</a>
    </footer>
</body>
</html>