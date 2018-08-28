<?php 
session_start();
$_SESSION['wroc'] = $_SERVER['REQUEST_URI'];
include 'config.inc.php';
include 'gora.inc.php';
include 'funkcje.inc.php';


echo '<div class="container">';
include 'pobieranie_IMAP.inc.php';
include 'usun.inc.php';
include 'nieprzeczytana.inc.php';
echo '</div>';


/*Wyświetlana ilość maili na stronie*/
$ilosc_maili_na_stronie = 10;

if(isset($_GET['strona']))
{
    $strona = trim(strip_tags($_GET['strona']));
    $limit_strona = ($strona-1)*$ilosc_maili_na_stronie;
}
else
{
    $limit_strona = 0;
    $strona = 1;   
}

$zapytanie = "SELECT * FROM lista_mail";
$result = $conn->query($zapytanie);
$num_mail = mysqli_num_rows($result);

echo '<div class="container">';
    echo '<div class="row">';
        echo '<div class="col">';
            $zapytanie_d = "SELECT * FROM data_polaczenia ORDER BY d_data DESC";
            $result_d = $conn->query($zapytanie_d);
            $wynik_d = mysqli_fetch_array($result_d);
                echo '<span style="font-style: italic; color: gray; font-size: 10px; line-height: 40px;">Ostatnie połączenie z serwerem poczty: '.date('d.m.Y H:i:s',strtotime($wynik_d['d_data'])).'</span>';
            echo '</div>';

stronicowanie($num_mail,$ilosc_maili_na_stronie,$strona);
        
    echo '</div>';
echo '</div>';


echo'
<div class="container">
    <div class="row">
        <div class="col">
            <table class="table table-hover border-bottom">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th scope="col">#</th>
                        <th scope="col">Temat</th>
                        <th scope="col" style="width: 220px;"><a href="'.sortowanie_url().'" style="color: black;">Data</a> <span class="sort '.sortowanie_icon().'"></span></th>
                        <th scope="col" class="my_width">Opcje</th>
                    </tr>
                </thead>
            <tbody>
            ';

//$conn = new mysqli($servername, $username, $password, $baza);

$i=$limit_strona+1;

$zapytanie = "SELECT * FROM lista_mail ORDER BY m_data ".sortowanie()." LIMIT ".$limit_strona.", ".$ilosc_maili_na_stronie."";
$result = $conn->query($zapytanie);
while($wynik = mysqli_fetch_array($result))
{
    if(in_array($wynik['m_id'],$nowa,true)){$m_nowa='alert-primary';}else{$m_nowa='';}
    
    if($wynik['m_flaga'] == 1){$flaga='font-weight-bold';}else{$flaga='';}
    
    echo '<tr class=" '.$m_nowa.' ">';
        echo '<th scope="col">'.$i.'</th>';
            echo '<td><a class="text-dark '.$flaga.'" href="wiadomosc.php?m_id='.$wynik['m_id'].'">'.$wynik['m_temat'].'</a></td>';
            echo '<td>'.data_czas($wynik['m_data']).'</td>';
            echo '<td class="my_width">';
                echo '<div class="dropdown dropleft">';
                echo '<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    echo '<img src="https://png.icons8.com/ios/50/ffffff/settings.png" width="21" height="21">';
                echo '</button>';
                echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    echo '<a class="dropdown-item" href="wiadomosc.php?m_id='.$wynik['m_id'].'">Otwórz wiadomość</a>';
                    if($wynik['m_flaga'] == 0)
                    {echo '<a class="dropdown-item" href="index.php?unseen='.$wynik['m_id'].'">Oznacz jako nieprzeczytana</a>';}
                    echo '<div class="dropdown-divider"></div>';
                    echo '<a class="dropdown-item" href="index.php?usun='.$wynik['m_id'].'" onclick=" return confirm(\'Jesteś pewny że chcesz skasować tego e-maila?\')">Usuń wiadomość</a>';
                    echo '</div>';
                echo '</div>';
            echo '</td>';
    echo '</tr>';
    
    $i++;
}
    
    
echo '
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col">
            <form id="form_pobierz" method="post" action="index.php">
                <input type="hidden" name="pobierz_maile" value="1" />
            </form>
            <button type="submit" class="btn btn-outline-primary" form="form_pobierz">Pobierz wiadomości ze skrzynki odbiorczej</button>
        </div>';


stronicowanie($num_mail,$ilosc_maili_na_stronie,$strona);


    echo '</div>';
echo '</div>';

include 'dol.inc.php';
        
?>