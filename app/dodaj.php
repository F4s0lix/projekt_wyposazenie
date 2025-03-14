<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TYTUŁ</title>
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
        <form action="dodaj.php" method="post" enctype="multipart/form-data">
            <label for="nazwa">nazwa</label>
            <input type="text" name="nazwa" id="nazwa">
            <label for="ilosc">ilość</label>
            <input type="number" name="ilosc" id="ilosc">
            <label for="faktura">faktura</label>
            <input type="file" name="faktura" id="faktura">
            <label for="miejsce">miejsce</label>
            <input type="text" name="miejsce" id="miejsce">
            <label for="stan">stan</label>
            <input type="text" name="stan" id="stan">
            <label for="srodek">środek trwały</label>
            <input type="checkbox" name="srodek" id="srodek">
            <input type="submit" value="dodaj">
        </form>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl"> Jan Wawrzyniak</a>
    </footer>
</body>
</html>