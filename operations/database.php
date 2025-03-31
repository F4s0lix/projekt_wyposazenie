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
        #otwiera połączenie z bazą danych
        $this->db = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }

    private function zamknij_polaczenie()
    {
        #zamyka połączenie z bazą danych
        $this->db->close();
    }

    public function ostatnie_dodania()
    {
        #funkcja zwraca listę z słownikami zawierającymi dane osatnich 6 dodanych rzeczy
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
        #funkcja zwraca listę z słownikami zawierającymi dane osatnich 5 wypożyczeń
        $this->otworz_polaczenie();
        $query = 'SELECT wypozyczenia.email, rzecz.nazwa, wypozyczenia.data_zwrotu FROM wypozyczenia, rzecz WHERE rzecz.id = wypozyczenia.id_rzeczy ORDER BY wypozyczenia.data_wypozyczenia ASC LIMIT 5';
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
        #funkcja zwraca słownik z danymi przedmiotu o danym ID
        $nazwa = '';
        $ilosc = '';
        $faktura = '';
        $miejsce = '';
        $stan = '';
        $srodek = '';
        $this->otworz_polaczenie();
        $stmt = $this->db->prepare('SELECT id, nazwa, ilosc, faktura_id, miejsce, stan, srodek_trwaly FROM rzecz WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($id, $nazwa, $ilosc, $faktura, $miejsce, $stan, $srodek);
        $stmt->fetch();
        $stmt->close();
        $this->zamknij_polaczenie();
        $data = [];
        $data['id'] = $id;
        $data['nazwa'] = $nazwa;
        $data['ilosc'] = $ilosc;
        $data['faktura'] = $faktura;
        $data['miejsce'] = $miejsce;
        $data['stan'] = $stan;
        $data['srodek_trwaly'] = $srodek;
        return $data;
    }

    public function wyswielt_fakture($id){
        #funkcja wyświetla fakturę o danym ID
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

        if($blob){ 
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
        #funkcja dodaje fakturę do bazy danych i zwraca ID faktury
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
        #funkcja dodaje przedmiot do bazy danych i zwraca true lub błąd dodawania
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
        #funkcja zwraca nazwę faktury o danym ID
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
    public function zmien_fakture($id_przedmiotu, $faktura)
    {
        #funkcja zmienia zawartość faktury o podanym ID
        $this->otworz_polaczenie();
        $faktura_id = '';
        $stmt = $this->db->prepare('SELECT faktura_id FROM rzecz WHERE id = ?');
        $stmt->bind_param('i', $id_przedmiotu);
        $stmt->execute();
        $stmt->bind_result($faktura_id);
        $stmt->fetch();
        $stmt->close();
        if($faktura_id != null){
            $stmt = $this->db->prepare('UPDATE faktury SET nazwa = ?, typ = ?, faktura_blob = ? WHERE id = ?');
            $stmt->bind_param('sssi', $faktura['name'], $faktura['type'], $faktura['tmp_name'], $faktura_id);
            $stmt->execute();
            $stmt->close();
        }else{
            $stmt = $this->db->prepare('INSERT INTO faktury (nazwa, typ, faktura_blob) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $faktura['name'], $faktura['type'], $faktura['tmp_name']);
            $stmt->execute();
            $stmt->close();
            $stmt2 = $this->db->prepare('SELECT id FROM faktury WHERE nazwa = ?');
            $stmt2->bind_param('s', $faktura['name']);
            $stmt2->execute();
            $stmt2->bind_result($faktura_id);
            $stmt2->fetch();
            $stmt2->close();
            $stmt3 = $this->db->prepare('UPDATE rzecz SET faktura_id = ? WHERE id = ?');
            $stmt3->bind_param('ii', $faktura_id, $id_przedmiotu);
            $stmt3->execute();
            $stmt3->close();
        }
        $this->zamknij_polaczenie();
    }
    public function edytuj_przedmiot($id, $ilosc, $miejsce, $stan, $srodek, $faktura)
    {
        #funkcja edytuje przedmiot o danym ID
        $this->otworz_polaczenie();
        $stmt = $this->db->prepare('UPDATE rzecz SET ilosc = ?, miejsce = ?, stan = ?, srodek_trwaly = ? WHERE id = ?');
        $stmt->bind_param('issii', $ilosc, $miejsce, $stan, $srodek, $id);
        $stmt->execute();
        $stmt->close();
        $this->zamknij_polaczenie();
        if($faktura['name'] !== '') $this->zmien_fakture($id, $faktura);
    }
    public function wyszukaj($tabela, $qn, $qt, $qm=null, $qs=null)
    {
        #funkcja wyszukuje przedmioty lub osoby w bazie danych
        $this->otworz_polaczenie();
        if($tabela == 'rzecz'){
            $data = $this->wyszukaj_rzecz($qn, $qm, $qs, $qt);
        }else{
            $data = $this->wyszukaj_osobe($qn, $qt);
        }
        $this->zamknij_polaczenie();
        return $data;
    }
    public function wyszukaj_rzecz($qn, $qm, $qs, $qt)
    {
        #funkcja wyszukuje przedmioty w bazie danych
        $id = '';
        $nazwa = '';
        $faktura = '';
        $miejsce = '';
        $stan = '';
        $srodek = '';
        $qn = '%'.$qn.'%';
        $qm = '%'.$qm.'%';
        $qs = '%'.$qs.'%';
        $stmt= $this->db->prepare('SELECT id, nazwa, faktura_id, miejsce, stan, srodek_trwaly FROM rzecz WHERE nazwa LIKE ? AND miejsce LIKE ? AND stan LIKE ? AND srodek_trwaly = ?');
        $stmt->bind_param('sssi', $qn, $qm, $qs, $qt);
        $stmt->execute();
        $data = [];
        $stmt->bind_result($id, $nazwa, $faktura, $miejsce, $stan, $srodek);
        while($stmt->fetch())
        {
            $tmp['id'] = $id;
            $tmp['nazwa'] = $nazwa;
            $tmp['faktura'] = $faktura;
            $tmp['miejsce'] = $miejsce;
            $tmp['stan'] = $stan;
            $tmp['srodek_trwaly'] = $srodek;
            $data[] = $tmp;
        }
        $stmt->close();
        return $data;
    }
    public function wyszukaj_osobe($qn, $qt)
    {
        #funkcja wyszukuje osoby w bazie danych
        $email = '';
        $numer = '';
        $stmt = $this->db->prepare('SELECT email, numer FROM osoby WHERE email LIKE ? AND numer LIKE ?');
        $qn = '%'.$qn.'%';
        $qt = '%'.$qt.'%';
        $stmt->bind_param('ss', $qn, $qt);
        $stmt->execute();
        $data = [];
        $stmt->bind_result($email, $numer);
        while($stmt->fetch())
        {
            $tmp['email'] = $email;
            $tmp['numer'] = $numer;
            $data[] = $tmp;
        }
        return $data;
    }
    public function wszystkie_osoby(){
        #funkcja zwraca listę z słownikami zawierającymi dane wszystkich osób
        $this->otworz_polaczenie();
        $email = '';
        $numer = '';
        $stmt = $this->db->prepare('SELECT email, numer FROM osoby');
        $stmt->execute();
        $data = [];
        $stmt->bind_result($email, $numer);
        while($stmt->fetch())
        {
            $tmp['email'] = $email;
            $tmp['numer'] = $numer; 
            $data[] = $tmp;
        }
        $stmt->close();
        $this->zamknij_polaczenie();
        return $data;
    }
    public function wypozyczone($email)
    {
        #funkcja zwraca listę z słownikami zawierającymi dane wypożyczonych przedmiotów przez daną osobę
        $id = '';
        $nazwa = '';
        $wypozyczenie = '';
        $zwrot = '';
        $email = htmlspecialchars($email);
        $this->otworz_polaczenie();
        $stmt = $this->db->prepare('SELECT rzecz.id, rzecz.nazwa, wypozyczenia.data_wypozyczenia, wypozyczenia.data_zwrotu FROM rzecz, wypozyczenia WHERE rzecz.id = wypozyczenia.id_rzeczy AND wypozyczenia.email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $data = [];
        $stmt->bind_result($id, $nazwa, $wypozyczenie, $zwrot);
        while($stmt->fetch())
        {
            $tmp['id'] = $id;
            $tmp['nazwa'] = $nazwa;
            $tmp['wypozyczenie'] = $wypozyczenie;
            $tmp['zwrot'] = $zwrot;
            $data[] = $tmp;
        }
        $stmt->close();
        $this->zamknij_polaczenie();
        return $data;
    }
    public function dodaj_osobe($email, $numer)
    {
        ##funkcja dodaje osobę do bazy danych i zwraca czy dodano
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) return false;
        $this->otworz_polaczenie();
        if($numer == '') $numer = null; 
        $stmt = $this->db->prepare('INSERT INTO osoby (email, numer) VALUES (?, ?)');
        $stmt->bind_param('si', $email, $numer);
        $stmt->execute();
        $this->zamknij_polaczenie();
        if($stmt->affected_rows > 0) return true;
        else return false;
        
    }
    public function wszystkie_rzeczy()
    {
        #funkcja zwraca listę z słownikami zawierającymi dane osatnich 7 dodanych rzeczy
        $this->otworz_polaczenie();
        $query = 'SELECT id, nazwa, ilosc, faktura_id, miejsce, stan, srodek_trwaly FROM rzecz';
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
    public function wypozycz($id_rzeczy, $email, $data_zwrotu)
    {
        #funkcja dodaje wypożyczenie do bazy danych
        $this->otworz_polaczenie();
        $id_rzeczy = htmlspecialchars($id_rzeczy);
        $email = htmlspecialchars($email);
        $data_zwrotu = htmlspecialchars($data_zwrotu);
        $stmt = $this->db->prepare('INSERT INTO wypozyczenia (id_rzeczy, email, data_wypozyczenia, data_zwrotu) VALUES (?, ?, NOW(), ?)');
        $stmt->bind_param('iss', $id_rzeczy, $email, $data_zwrotu);
        $stmt->execute();
        $stmt->close();
        $this->zamknij_polaczenie();
    }
    public function usun_wypozyczenie($email, $id, $dw, $dz)
    {
        #funkcja usuwa wypożyczenie z bazy danych
        $this->otworz_polaczenie();
        $id_rzeczy = htmlspecialchars($id);
        $email = htmlspecialchars($email);
        $data_zwrotu = htmlspecialchars($dz);
        $data_wypozyczenia = htmlspecialchars($dw);
        $stmt = $this->db->prepare('DELETE FROM wypozyczenia WHERE id_rzeczy = ? AND email = ? AND data_wypozyczenia = ? AND data_zwrotu = ?');
        $stmt->bind_param('isss', $id_rzeczy, $email, $data_wypozyczenia, $data_zwrotu);
        $stmt->execute();
        $stmt->close();
        $this->zamknij_polaczenie();
    }
}
?>