<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>osoby</title>
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
            <table>
                <tr><th>email</th><th>numer</th><th>wypożyczone</th></tr>
                <td colspan="3"><a href="dodaj_osobe.php">dodaj osobę</a></td>
                <?php
                    require_once '../operations/database.php';
                    $baza = new baza_operacje();
                    $dane = $baza->wszystkie_osoby();
                    foreach ($dane as $k => $v) {
                        $email = $v['email'];
                        $numer = $v['numer'];
                        echo "<tr><td>$email</td><td>$numer</td><td><a href='wypozyczone.php?email=$email'>zobacz</a></td></tr>";
                    }
                ?>
            </table>
        </div>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl">Jan Wawrzyniak</a>
    </footer>
</body>
</html>