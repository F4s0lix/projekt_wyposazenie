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
        <form action="wyszukaj.php" method="get">
            <select name="co_wyszukac" class="zniknij">
                <option value="rzecz">przedmiot</option>
                <option value="osoby">osobę</option>
            </select>
            <input type="submit" value="dalej" class="zniknij">
            <?php 
                if(isset($_GET["co_wyszukac"], $_GET['q']))
                {
                    echo '<style>.zniknij{display:none;}</style>';

                }
                else if(isset($_GET['co_wyszukac']))
                {
                    echo '<style>.zniknij{display:none;}</style>';
                    echo '<form action="wyszukaj.php" method="get">';
                    echo '<input type="hidden" name="co_wyszukac" value="'.$_GET['co_wyszukac'].'">';
                    if($_GET['co_wyszukac'] == 'rzecz')
                    {
                        echo '<input type="search" name="qn" placeholder="nazwa przedmiotu">';
                        echo '<input type="search" name="qm" placeholder="miejsce przedmiotu">';
                        echo '<input type="search" name="qs" placeholder="stan przedmiotu">';
                        echo '<label for="srodek">Środek trwały:<input type="checkbox" name="qt" id="srodek"></label>';
                    }
                    else if($_GET['co_wyszukac'] == 'osoby')
                    {
                        echo '<input type="search" name="qn" placeholder="email osoby">';
                        echo '<input type="search" name="qt" placeholder="numer osoby">';
                    }
                    echo '<input type="submit" value="szukaj">';
                    echo '</form>';
                }
            ?>
        </form>
    </main>
    <footer>
        Stworzono przez: <a href="mailto:jan.wawrzyniak@zhp.pl">Jan Wawrzyniak</a>
    </footer>
</body>
</html>