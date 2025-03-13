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
            REJESTR WYPOSAŻENIA
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
        <?php
            $id = $_GET['id'];
            require_once 'operations/database.php';
            $baza = new baza_operacje;
            $dane = $baza->przemiot($id);
            echo '<h1>ID: '.$dane['id'].' - '.$dane['nazwa'].'</h1>';
        ?>
        
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl"> Jan Wawrzyniak</a>
    </footer>
</body>
</html>