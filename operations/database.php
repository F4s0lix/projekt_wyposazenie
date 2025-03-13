<?php
class baza_operacje
{
    #klasa pozwala na operacje na bazie danych
    private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'rejestr_wyposazenia';
    
    private $db;
    
    private function otworz_polaczenie()
    {
        $this->db = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }

    public function ostatnie_dodania()
    {
        #funkcja zwraca listę z listami
        $this->otworz_polaczenie();
        $query = 'SELECT id, nazwa, ilosc, faktura, miejsce, stan, srodek_trwaly FROM rzecz';
        $result = $this->db->query($query);
        $ostatnie = [];
        if($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $data = [];
                $data['id'] = $row['id'];
                $data['nazwa'] = $row['nazwa'];
                $data['ilosc'] = $row['ilosc'];
                $data['faktura'] = $row['faktura'];
                $data['miejsce'] = $row['miejsce'];
                $data['stan'] = $row['stan'];
                $data['srodek_trwaly'] = $row['srodek_trwaly'];
                $ostatnie[] = $data;
            }
        }
        return $ostatnie;
    }
}
?>