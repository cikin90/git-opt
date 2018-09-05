<?php

// Łączenie się z serwerem (IMAP), pobieranie poczty i zapisywanie na serwerze

if (isset($_POST['pobierz_maile']) and $_POST['pobierz_maile'] == 1) {


    //    if ($mbox = imap_open("{imap.poczta.onet.pl:993/ssl}INBOX", 'tom.tom.opt@onet.pl', 'Password123')) {
    /* $mailbox = new PhpImap\Mailbox('{imap.poczta.onet.pl:993/ssl}INBOX', 'tom.tom.opt@onet.pl', 'Password123', __DIR__);


      $mailsIds = $mailbox->searchMailbox('ALL');

      echo '<pre>';
      foreach ((array) $mailsIds as $id) {


      $mail = $mailbox->getMail($id);


      var_dump($mail->textPlain);
      }
      die;
     */

    if ($mbox = imap_open("{imap.poczta.onet.pl:993/ssl}INBOX", 'tom.tom.opt@onet.pl', 'Password123')) {
        //if ($mbox = imap_open("{imap.wp.pl:993/ssl}INBOX", 'tom.tom.opt@wp.pl', 'Password123')) {

        $conn = new mysqli($servername, $username, $password, $baza);
        $maile = imap_search($mbox, 'UNSEEN');
        $ilosc_wiadomosci = 0;
        if (is_array($maile)) {


            foreach ((array) $maile as $maile_numery) {

                $naglowek = imap_headerinfo($mbox, $maile_numery, 99, 99);
                $m_temat = imap_utf8($naglowek->fetchsubject);
                $m_data = date('Y-m-d H:i:s', $naglowek->udate);
                $m_nadawca_host = $naglowek->from[0]->mailbox . '@' . $naglowek->from[0]->host;
                $m_nadawca = imap_utf8($naglowek->fromaddress);

                $f_s = imap_fetchstructure($mbox, $maile_numery);

                $message1 = imap_fetchbody($mbox, $maile_numery, '1', FT_PEEK);
                //$message = imap_body($mbox, $maile_numery, FT_PEEK);
                //echo $message; die;
                //$message = quoted_printable_decode($message);
                //$message = iconv_mime_decode($message, 0, "UTF-8");
                //$message = iconv('ISO-8859-2', 'UTF-8', $message);
                //$message = imap_body($mbox, $maile_numery, FT_PEEK);

                /* $message = mb_convert_encoding($message, "UTF-8");  
                  $message = nl2br(quoted_printable_decode($message)); */

                //$message = utf8_encode(quoted_printable_decode($message));
                //$message = utf8_decode(imap_utf8($message));
                //$message = iconv('ISO-8859-2', 'UTF-8', $message);
                //$message = iconv_mime_decode($message, 2, "UTF-8");
                //echo $message;
                //$message = base64_decode($message);
                //$message = mb_detect_order($message);
                //$siteCoding = mb_detect_encoding($message, 'UTF-8, ISO-8859-1, ISO-8859-2', true);
                //$message =iconv("ISO-8859-2", "UTF-8", $message);
                //$message = iconv('utf-8','utf-8',  $message);
                //$message = iconv('UTF-8', 'UTF-8//IGNORE', $message);



                $message1 = (quoted_printable_decode($message1));
                //echo $message;
                //$siteCoding=mb_detect_encoding($message, 'UTF-8', true);
                //echo $siteCoding;
                if (mb_detect_encoding($message1, 'utf-8', true)) {
                    $message = imap_fetchbody($mbox, $maile_numery, '1', FT_PEEK); /* echo $message;echo '</br>'; */
                } else {
                    $message = $message1;
                    $message = iconv("ISO-8859-2", "UTF-8", $message);
                    ; /* echo $message;echo '</br>'; */
                }
                //echo '1: '. $message;
                //echo '</br>';
                //$message=mb_convert_encoding($message, "UTF-8");            
                //$message=str_replace( 'iso-8859-2','utf-8', $message);
                //$message = utf8_decode(imap_utf8($message));
                //echo '2: '.$message;
                //echo '</br>';
                // echo (imap_fetchbody($mbox, $maile_numery, '1', FT_PEEK));
                //echo imap_base64(imap_fetchbody($mbox, $maile_numery, '1', FT_PEEK));
                if ($f_s->parts[0]->encoding == 3) {
                    $message = imap_base64($message);
                } elseif ($f_s->parts[0]->encoding == 4) {
                    $message = imap_qprint($message);
                } else {
                    $message = imap_qprint($message);
                }


                //echo '</br>';
                //echo 'tresc: ' . htmlspecialchars(imap_fetchbody($mbox, $maile_numery, '1', FT_PEEK));

                /* echo '</br>';
                  echo $message; */
                //echo '</br></br></br>';
                $message = $conn->real_escape_string($message);
                $szukany = '/(O|o)(P|p)(T|t)(e|E)(A|a)(M|m)/';
                if(preg_match($szukany, $message) or preg_match($szukany, $m_temat)){$m_szukane='1';}else{$m_szukane='0';}
                $zapytanie_insert = "INSERT INTO lista_mail (m_temat, m_tresc, m_data, m_nadawca_host, m_nadawca, m_flaga, m_szukane) VALUE ('$m_temat', '$message', '$m_data', '$m_nadawca_host', '$m_nadawca', '1', '$m_szukane')";
                if (!$conn->query($zapytanie_insert)) {
                    echo '<div class="alert alert-danger" role="alert">Nie udało się pobrać wiadomości z serwera.</div>';
                } else {
                    $ilosc_wiadomosci++;

                    $zapytanie = "SELECT m_id FROM lista_mail ORDER BY m_id DESC LIMIT 1";
                    $result = $conn->query($zapytanie);
                    $wynik = mysqli_fetch_array($result);
                    $nowa[] = $wynik['m_id'];

                    imap_setflag_full($mbox, $maile_numery, "\\Seen");
                }
            }
        }

        if (is_array($maile) and $ilosc_wiadomosci == count($maile)) {
            $alert = 'alert-primary';
        } else {
            $alert = 'alert-warning';
        }

        if (!is_array($maile) or count($maile) == 0 or empty($maile)) {
            echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">Na serwerze nie ma nowych nieodebranych wiadomości.';
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            $zapytanie_insert_polaczenie = "INSERT INTO data_polaczenia (d_data, d_m_udane, d_m_dostepne) VALUE (NOW(), '$ilosc_wiadomosci', '0')";
        } else {
            echo '<div class="alert ' . $alert . ' alert-dismissible fade show" role="alert">Pobrano ' . $ilosc_wiadomosci . '/' . count($maile) . ' nieprzeczytach wiadomości.';
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            $zapytanie_insert_polaczenie = "INSERT INTO data_polaczenia (d_data, d_m_udane, d_m_dostepne) VALUE (NOW(), '$ilosc_wiadomosci', '" . count($maile) . "')";
        }
        $conn->query($zapytanie_insert_polaczenie);
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Nie udało się połączyć z serwerem. Spróbuj ponownie później lub skontaktuj się z administratorem.';
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }

    imap_close($mbox);
}
?>