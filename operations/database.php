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
        $query = 'SELECT id, nazwa, ilosc, faktura_id, miejsce, stan, srodek_trwaly FROM rzecz ORDER BY id DESC LIMIT 6';
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
        $query = 'SELECT wypozyczenia.email, rzecz.nazwa, wypozyczenia.data_zwrotu FROM wypozyczenia, rzecz WHERE rzecz.id = wypozyczenia.id_rzeczy ORDER BY wypozyczenia.data_wypozyczenia ASC LIMIT 6';
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

    public function wyswielt_fakture($id){
        $nazwa = '';
        $typ = '';
        $blob = '';
        $this->otworz_polaczenie();
        $stmt = $this->db->prepare('select nazwa, typ, faktura_blob from faktury where id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($nazwa, $typ, $blob);
        $stmt->fetch();
        $stmt->close();
        $this->zamknij_polaczenie();

        if($blob){ #nie wiem czemu wyświetla błąd ale działa więc nie ma czym się przejmować
            header('Content-Type: '.$typ);
            header('Content-Disposition: attachment; filename="'.$nazwa.'"');
            header('Content-Length: '.strlen($blob));
            header('Accept-Ranges: bytes');
            ob_clean();
            flush();
            echo $blob;
        }
    }
    public function dodaj_fakture($faktura){
        if($faktura['name'] === '') return null;
        else{ 
            $fakturaNazwa = time().'_'.$faktura['name'];
            $fakturaTyp = $faktura['type'];
            $fakturaDane = file_get_contents($faktura['tmp_name']);

            $stmt_faktura = $this->db->prepare('INSERT INTO faktury (nazwa, typ, faktura_blob) VALUES (?, ?, ?)');
            $stmt_faktura->bind_param('sss', $fakturaNazwa, $fakturaTyp, $fakturaDane);
            if($stmt_faktura->execute()){
            $query = 'SELECT id FROM faktury WHERE nazwa = "'.$fakturaNazwa.'"';
                $result = $this->db->query($query);
                $row = $result->fetch_assoc();
                return $row['id'];
            }
        }
    }
    public function dodaj_przedmiot($nazwa, $ilosc, $miejsce, $stan, $srodek, $faktura)
    {
        $this->otworz_polaczenie();
        $faktura_id = $this->dodaj_fakture($faktura);

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
    public function nazwa_faktury($id){
        $this->otworz_polaczenie();
        $nazwa = '';
        $stmt = $this->db->prepare('SELECT nazwa FROM faktury WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($nazwa);
        $stmt->fetch();
        $stmt->close();
        $this->zamknij_polaczenie();
        return substr($nazwa, -12, 12);
    }
    public function edytuj_przedmiot($id, $ilosc, $miejsce, $stan, $srodek)
    {
        $this->otworz_polaczenie(); #TODO: zmiana faktury na inną
        $stmt = $this->db->prepare('UPDATE rzecz SET ilosc = ?, miejsce = ?, stan = ?, srodek_trwaly = ? WHERE id = ?');
        $stmt->bind_param('issii', $ilosc, $miejsce, $stan, $srodek, $id);
        $stmt->execute();
        $stmt->close();
        $this->zamknij_polaczenie();
    }
}
?>