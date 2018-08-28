<?php

// Łączenie się z serwerem (IMAP), pobieranie poczty i zapisywanie na serwerze

if(isset($_POST['pobierz_maile']) and $_POST['pobierz_maile'] == 1)
{
    if($mbox = imap_open("{imap.poczta.onet.pl:993/ssl}INBOX", 'tom.tom.opt@onet.pl', 'Password123'))
    {

        $conn = new mysqli($servername, $username, $password, $baza);
        $maile = imap_search($mbox, 'UNSEEN');
        $ilosc_wiadomosci = 0;
        foreach($maile as $maile_numery)
        {
            $naglowek = imap_headerinfo($mbox, $maile_numery, 99, 99);
                $m_temat = imap_utf8($naglowek->fetchsubject);
                $m_data = date('Y-m-d H:i:s', $naglowek->udate);
                $m_nadawca_host = $naglowek->from[0]->mailbox.'@'.$naglowek->from[0]->host;
                $m_nadawca = imap_utf8($naglowek->fromaddress);
            
            $f_s = imap_fetchstructure($mbox,$maile_numery);
            //$part_array = $f_s->parts[1];
            
            /*if($f_s->encoding == 0){$section_encoding = 0;}
            elseif($f_s->encoding == 1){$section_encoding = 1;}
            elseif($f_s->encoding == 2){$section_encoding = 2;}
            elseif($f_s->encoding == 3){$section_encoding = 3;}
            elseif($f_s->encoding == 4){$section_encoding = 4;}
            elseif($f_s->encoding == 5){$section_encoding = 5;}*/
            
            $message = imap_fetchbody($mbox, $maile_numery, '1', FT_PEEK);
            
            if($f_s->parts[0]->encoding == 3){$message = imap_base64($message);}
            elseif($f_s->parts[0]->encoding == 4){$message = imap_qprint($message);}
            else{$message = imap_qprint($message);}
            
            $message = $conn->real_escape_string($message);
            
            $zapytanie_insert = "INSERT INTO lista_mail (m_temat, m_tresc, m_data, m_nadawca_host, m_nadawca, m_flaga) VALUE ('$m_temat', '$message', '$m_data', '$m_nadawca_host', '$m_nadawca', '1')";
            if(!$conn->query($zapytanie_insert))
            {echo '<div class="alert alert-danger" role="alert">Nie udało się pobrać wiadomości z serwera.</div>';}
            else
            {
                $ilosc_wiadomosci++;
                
                $zapytanie = "SELECT m_id FROM lista_mail ORDER BY m_id DESC LIMIT 1";
                $result = $conn->query($zapytanie);
                $wynik = mysqli_fetch_array($result);
                $nowa[]=$wynik['m_id'];
                
                imap_setflag_full($mbox, $maile_numery,  "\\Seen");
            }
            
        }
        
        if($ilosc_wiadomosci == count($maile))
        {$alert = 'alert-primary';}
        else
        {$alert = 'alert-warning';}
        
        if(count($maile) == 0 or empty($maile))
        {
            echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">Na serwerze nie ma nowych nieodebranych wiadomości.';
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            $zapytanie_insert_polaczenie = "INSERT INTO data_polaczenia (d_data, d_m_udane, d_m_dostepne) VALUE (NOW(), '$ilosc_wiadomosci', '0')";
        }    
        else
        {
            echo '<div class="alert '.$alert.' alert-dismissible fade show" role="alert">Pobrano '.$ilosc_wiadomosci.'/'.count($maile).' nieprzeczytach wiadomości.';
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            $zapytanie_insert_polaczenie = "INSERT INTO data_polaczenia (d_data, d_m_udane, d_m_dostepne) VALUE (NOW(), '$ilosc_wiadomosci', '".count($maile)."')";
        }
        $conn->query($zapytanie_insert_polaczenie);
        
    }
    else
    {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Nie udało się połączyć z serwerem. Spróbuj ponownie później lub skontaktuj się z administratorem.';
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    
    imap_close($mbox); 
}

?>