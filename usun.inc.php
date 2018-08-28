<?php
if(isset($_GET['usun']))
{
    $usun = trim(strip_tags($_GET['usun']));
    
    $zap_usun_test="SELECT m_id FROM lista_mail WHERE m_id = '$usun'";
    $result_usun_test = $conn->query($zap_usun_test);
    $num_mail_usun_test = mysqli_num_rows($result_usun_test);
    
    if($num_mail_usun_test == 1)
    {
        $zap_usun="DELETE FROM lista_mail WHERE m_id = '$usun'";
        if(!$conn->query($zap_usun))
        {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Nie udało się usunąć wiadomości.';
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        }
        else
        {
            echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">Wiadomość została usunięta.';
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        }
    }
    else
    {
        echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">Brak maili do usunięcia.';
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
        
}

?>