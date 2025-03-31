<?php
#jeżeli nie ma emaila przenosimy na stronę główną
if(!isset($_GET['email'])) header('Location: ../index.php');
#import klasy z pliku zewnętrznego
require_once '../operations/database.php';
$baza = new baza_operacje();
if(isset($_GET['email'], $_GET['id'], $_GET['dw'], $_GET['dz'])) 
{
    #usuwa podane wypożyczenie
    $baza->usun_wypozyczenie($_GET['email'], $_GET['id'], $_GET['dw'], $_GET['dz']);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wypożyczone</title>
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
            <h1>Wypożyczone przez <?php echo $_GET['email']?></h1>
            <table>
                <tr><th>nazwa</th><th>data wypożyczenia</th><th>data zwrotu</th><th></th></tr>
                <?php
                    #wyświetla wszystkie wypożyczenia osoby w kolejnych wierszach tabeli
                    $email = $_GET['email'];
                    $dane = $baza->wypozyczone($email);
                    foreach($dane as $k => $v) {
                        $id = $v['id'];
                        $nazwa = $v['nazwa'];
                        $wypozyczenie = $v['wypozyczenie'];
                        $zwrot = $v['zwrot'];
                        echo "<tr><td>$nazwa</td><td>$wypozyczenie</td><td>$zwrot</td><td><a href='wypozyczone.php?email=$email&id=$id&dw=$wypozyczenie&dz=$zwrot'>usuń</a></td></tr>";
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