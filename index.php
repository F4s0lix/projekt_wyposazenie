<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>strona główna</title>
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
            <h2>ostatnio dodane</h2>
            <table>
                <tr>
                    <th>nazwa</th>
                    <th>ilość</th>
                    <th>miejsce</th>
                    <th>stan</th>
                    <th>karta przemiotu</th>
                </tr>
                <?php
                    require_once 'operations/database.php';
                    $baza = new baza_operacje;
                    $dodania = $baza->ostatnie_dodania();
                    foreach ($dodania as $k => $dane) {
                        $id = $dane['id'];
                        $nazwa = $dane['nazwa'];
                        $ilosc = $dane['ilosc'];
                        $miejsce = $dane['miejsce'];
                        $stan = $dane['stan'];
                        $srodek = $dane['srodek_trwaly']?'TAK':'NIE';
                        #TODO: LINK DO PRZEDMIOTU
                        echo "<tr><td>$nazwa</td><td>$ilosc</td><td>$miejsce</td><td>$stan</td><td><a href='karta_przedmiotu.php?id=$id'>kliknij</a></td></tr>";
                    }
                ?>
            </table>
        </div>
        <div>
            <h2>ostatnio wypożyczone</h2>
            <table>
                <tr>
                    <th>email</th>
                    <th>nazwa</th>
                    <th>data zwrotu</th>
                </tr>
                <?php
                    $wypozyczenia = $baza->ostatnie_wypozyczenia();
                    foreach ($wypozyczenia as $k => $dane) {
                        $email = $dane['email'];
                        $nazwa = $dane['nazwa'];
                        $zwrot = $dane['zwrot'];
                        echo "<tr><td>$email</td><td>$nazwa</td><td>$zwrot</td></tr>";
                    }
                ?>
            </table>
        </div>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl"> Jan Wawrzyniak</a>
    </footer>
</body>
</html>