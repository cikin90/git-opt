<?php
if(isset($_GET['unseen']))
{
    $unseen = trim(strip_tags($_GET['unseen']));

    $zapytanie_up = "UPDATE lista_mail SET m_flaga = '1' WHERE m_id = '$unseen'";
    $conn->query($zapytanie_up);

    if(!$conn->query($zapytanie_up))
    {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Nie udało się zaktualizować bazy danych.';
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    else
    {
        echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">Wiadomość zostałą oznaczona jako nieprzeczytana.';
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
}

?>