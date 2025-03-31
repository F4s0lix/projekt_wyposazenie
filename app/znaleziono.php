<?php
    if(isset($_POST['co_wyszukac']))
    {
        #import klasy i dostanie odpowiednich danych
        require_once '../operations/database.php';
        $db = new baza_operacje();
        $data = [];
        if($_POST['co_wyszukac'] == 'rzecz')
        {
            $qt = isset($_POST['qt'])?1:0;
            $data = $db->wyszukaj('rzecz', $_POST['qn'], $qt, $_POST['qm'], $_POST['qs']);
        }
        else if($_POST['co_wyszukac'] == 'osoby')
        {
            $data = $db->wyszukaj('osoby', $_POST['qn'], $_POST['qt']);
        }
    }
    else header('Location: ../index.php');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wyszukaj</title>
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
        <div class="wyniki">
            <?php
                #wyświetla wynik wyszukiwania
                if($_POST['co_wyszukac'] == 'rzecz')
                {
                    echo '<table>';
                    echo '<tr><th>nazwa</th><th>faktura</th><th>miejsce</th><th>stan</th><th>srodek trwały</th></tr>';
                    foreach ($data as $k => $dane) {
                        $id = $dane['id'];
                        $nazwa = $dane['nazwa'];
                        $faktura = $dane['faktura'];
                        $miejsce = $dane['miejsce'];
                        $stan = $dane['stan'];
                        $srodek = $dane['srodek_trwaly']?'TAK':'NIE';
                        echo "<tr><td>$nazwa</td><td>$faktura</td><td>$miejsce</td><td>$stan</td><td>$srodek</td><td><a href=../karta_przedmiotu.php?id=$id>zobacz</a></td></tr>";
                    }
                    echo '</table>';
                }else if(isset($_POST['co_wyszukac'])){
                    echo '<table>';
                    echo '<tr><th>email</th><th>numer</th><th>wypożyczone</th></tr>';
                    foreach ($data as $k => $dane) {
                        $email = $dane['email'];
                        $numer = $dane['numer'];
                        echo "<tr><td>$email</td><td>$numer</td><td><a href='wypozyczone.php?email=$email'>zobacz</a></td></tr>";
                    }
                    echo '</table>';
                }
            ?>
        </div>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl">Jan Wawrzyniak</a>
    </footer>
</body>
</html>