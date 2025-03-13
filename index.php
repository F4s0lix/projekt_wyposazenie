<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TYTUŁ</title>
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
        <div>
            <h3>ostatnio dodane</h3>
            <table>
                <tr>
                    <th>nazwa</th>
                    <th>ilość</th>
                    <th>miejsce</th>
                    <th>stan</th>
                    <th>środek trwały</th>
                </tr>
                <?php
                    require_once 'operations/database.php';
                    $baza = new baza_operacje;
                    $dodania = $baza->ostatnie_dodania();
                    foreach ($dodania as $k => $dane) {
                        $nazwa = $dane['nazwa'];
                        $ilosc = $dane['ilosc'];
                        $miejsce = $dane['miejsce'];
                        $stan = $dane['stan'];
                        $srodek = $dane['srodek_trwaly']?'TAK':'NIE';
                        echo "<tr><td>$nazwa</td><td>$ilosc</td><td>$miejsce</td><td>$stan</td><td>$srodek</td></tr>";
                    }
                ?>
            </table>
        </div>
        <div>
            <h3>ostatnio wypożyczone</h3>
        </div>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl"> Jan Wawrzyniak</a>
    </footer>
</body>
</html>