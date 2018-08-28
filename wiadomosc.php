<?php
session_start();
include 'config.inc.php';

if(isset($_GET['m_id']))
{
    $m_id = trim(strip_tags($_GET['m_id']));

    $zapytanie = "SELECT * FROM lista_mail WHERE m_id = '$m_id'";
    $result = $conn->query($zapytanie);
    $wynik = mysqli_fetch_array($result);
    
    $_SESSION['breadcrumb'] = $wynik['m_temat'];
}

include 'gora.inc.php';
include 'funkcje.inc.php';


if(isset($m_id) and $wynik['m_id']==$m_id)
{
    
    $zapytanie_up = "UPDATE lista_mail SET m_flaga = '0' WHERE m_id = '$m_id'";
    $conn->query($zapytanie_up);
    
echo '<div class="container mt-5">';
    echo '<div class="row">';
        echo '<div class="col">';
              echo '<div class="form-group row">';
                echo '<label for="staticEmail" class="col-sm-2 col-form-label">Nadawca</label>';
                echo '<div class="col-sm-10">';
                  echo '<input type="email" class="form-control bg-light" value="'.htmlspecialchars($wynik['m_nadawca']).'" readonly>';
                echo '</div>';
              echo '</div>';
              echo '<div class="form-group row">';
                echo '<label for="inputPassword" class="col-sm-2 col-form-label">Temat</label>';
                echo '<div class="col-sm-10">';
                echo '<input type="text" class="form-control bg-light" value="'.$wynik['m_temat'].'" readonly>';
                echo '</div>';
              echo '</div>';
              echo '<div class="form-group row">';
                echo '<label for="inputPassword" class="col-sm-2 col-form-label">Treść wiadmości</label>';
                echo '<div class="col-sm-10">';
                echo '<span class="bg-light" >'.$wynik['m_tresc'].'</span>';
                echo '</div>';
              echo '</div>';
              echo '<div class="form-group row">';
                echo '<label for="inputPassword" class="col-sm-2 col-form-label"></label>';
                echo '<div class="col-sm-10">';
                if(isset($_SESSION['wroc'])){$wroc = $_SESSION['wroc'];}else{$wroc = 'index.php';}
                  echo '<a href="'.$wroc.'" class="btn btn-outline-primary">Wróć do skrzynki odbiorczej</a>';
                echo '</div>';
              echo '</div>';
        echo '</div>';
    echo '</div>';
echo '</div>';
}
else
{
    echo '<div class="container mt-5">';
        echo '<div class="row">';
            echo '<div class="col">';
            echo '<div class="alert alert-danger" role="alert">Nie znaleziono wiadomości o podanym identyfikatorze.</div>';
                echo '<div class="form-group row">';
                    echo '<div class="col-sm-10">';
                    if(isset($_SESSION['wroc'])){$wroc = $_SESSION['wroc'];}else{$wroc = 'index.php';}
                        echo '<a href="'.$wroc.'" class="btn btn-outline-primary">Wróć do skrzynki odbiorczej</a>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
    
}  

if(isset($_SESSION['breadcrumb'])){unset($_SESSION['breadcrumb']);}
include 'dol.inc.php';
?>