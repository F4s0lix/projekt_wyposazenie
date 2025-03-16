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

    private function zamknij_polaczenie()
    {
        $this->db->close();
    }

    public function ostatnie_dodania()
    {
        #funkcja zwraca listę z słownikami zawierającymi dane osatnich 7 dodanych rzeczy
        $this->otworz_polaczenie();
        $query = 'SELECT id, nazwa, ilosc, faktura_id, miejsce, stan, srodek_trwaly FROM rzecz ORDER BY id DESC LIMIT 7';
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
                $data['faktura'] = $row['faktura_id'];
                $data['miejsce'] = $row['miejsce'];
                $data['stan'] = $row['stan'];
                $data['srodek_trwaly'] = $row['srodek_trwaly'];
                $ostatnie[] = $data;
            }
        }
        $this->zamknij_polaczenie();
        return $ostatnie;
    }
    public function ostatnie_wypozyczenia()
    {
        $this->otworz_polaczenie();
        $query = 'SELECT wypozyczenia.email, rzecz.nazwa, wypozyczenia.data_zwrotu FROM wypozyczenia, rzecz WHERE rzecz.id = wypozyczenia.id_rzeczy ORDER BY wypozyczenia.data_wypozyczenia ASC LIMIT 7';
        $result = $this->db->query($query);
        $ostatnie = [];
        if($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $data = [];
                $data['email'] = $row['email'];
                $data['nazwa'] = $row['nazwa'];
                $data['zwrot'] = $row['data_zwrotu'];
                $ostatnie[] = $data;
            }
        }
        $this->zamknij_polaczenie();
        return $ostatnie;
    }
    public function przemiot($id)
    {
        $this->otworz_polaczenie();
        $query = 'SELECT id, nazwa, ilosc, faktura_id, miejsce, stan, srodek_trwaly FROM rzecz WHERE id = '.$id;
        $result = $this->db->query($query);
        $this->zamknij_polaczenie();
        if($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $data = [];
                $data['id'] = $row['id'];
                $data['nazwa'] = $row['nazwa'];
                $data['ilosc'] = $row['ilosc'];
                $data['faktura'] = $row['faktura_id'];
                $data['miejsce'] = $row['miejsce'];
                $data['stan'] = $row['stan'];
                $data['srodek_trwaly'] = $row['srodek_trwaly']?'tak':'nie';
                return $data;
            }
        }
    }

    public function dodaj_przedmiot($nazwa, $ilosc, $miejsce, $stan, $srodek, $faktura)
    {
        $this->otworz_polaczenie();
        if($faktura['name'] !== ''){
            $fakturaNazwa = time().'_'.$faktura['name'];
            $fakturaTyp = $faktura['type'];
            $fakturaDane = file_get_contents($faktura['tmp_name']);

            $stmt_faktura = $this->db->prepare('INSERT INTO faktury (nazwa, typ, faktura_blob) VALUES (?, ?, ?)');
            $stmt_faktura->bind_param('sss', $fakturaNazwa, $fakturaTyp, $fakturaDane);
            if($stmt_faktura->execute()){
             $query = 'SELECT id FROM faktury WHERE nazwa = "'.$fakturaNazwa.'"';
                $result = $this->db->query($query);
                $row = $result->fetch_assoc();
                $faktura_id = $row['id'];
            }
        }
        $stmt_przedmiot = $this->db->prepare('INSERT INTO rzecz (nazwa, ilosc, faktura_id, miejsce, stan, srodek_trwaly) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt_przedmiot->bind_param('siissi', $nazwa, $ilosc, $faktura_id, $miejsce, $stan, $srodek);
        if($stmt_przedmiot->execute()){
            $this->zamknij_polaczenie();
            return true;
        }
        else{
            $this->zamknij_polaczenie();
            return $stmt_przedmiot->error;
        }
    }
}
?>