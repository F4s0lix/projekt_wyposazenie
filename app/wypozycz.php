<?php
    function przenies($email)
    {
        #funkcja przenosi do wypożyczonych rzeczy przez daną osobę
        header("Location: wypozyczone.php?email=$email");
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wypożycz</title>
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
        <?php
            #blok importuje klasę z operacjami na bazie
            require_once '../operations/database.php';
            $baza = new baza_operacje();
            if(isset($_GET['id'], $_GET['osoba']))
            {
                #jeżeli podana jest ID i osoba to wprowadzane jest do bazy wypożyczenie
                $baza->wypozycz($_GET['id'], $_GET['osoba'], $_GET['data']);
                przenies($_GET['osoba']);
            }
            else if(isset($_GET['id']))
            {
                #jeżeli jest tylko ID wyświetla formularz do wybrania osoby
                echo '<form action="wypozycz.php" method="get">
                        <input type="hidden" name="id" value="'.$_GET['id'].'">
                        <label for="osoba">Osoba:</label>
                        <select name="osoba" id="osoba">';
                        $dane = $baza->wszystkie_osoby();
                        foreach($dane as $k => $v)
                        {
                            $email = $v['email'];
                            echo '<option value="'.$email.'">'.$email.'</option>';
                        }
                        echo '</select>
                        <label for="data">Data zwrotu:</label>
                        <input type="date" name="data" id="data" required>
                        <input type="submit" value="Wypożycz">
                    </form>';
            } 
            else
            {
            #jeżeli nie ma ani ID ani osoby wyświetla formularz do wybrania przedmiotu
            echo '<div class="wyniki">
            <h2>wybierz przedmiot</h2>
            <table>
                <tr>
                    <th>nazwa</th>
                    <th>ilość</th>
                    <th>miejsce</th>
                    <th>stan</th>
                    <th>wybierz</th>
                </tr>';
                    $dodania = $baza->wszystkie_rzeczy();
                    foreach ($dodania as $k => $dane) {
                        $id = $dane['id'];
                        $nazwa = $dane['nazwa'];
                        $ilosc = $dane['ilosc'];
                        $miejsce = $dane['miejsce'];
                        $stan = $dane['stan'];
                        $srodek = $dane['srodek_trwaly']?'TAK':'NIE';
                        echo "<tr><td>$nazwa</td><td>$ilosc</td><td>$miejsce</td><td>$stan</td><td><a href='wypozycz.php?id=$id'>wybierz</a></td></tr>";
                    }
            echo '</table>
        </div>';
            }
        ?>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl">Jan Wawrzyniak</a>
    </footer>
</body>
</html>