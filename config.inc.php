<?php  
$servername="cikin.atthost24.pl";
$username="3931_mail_opt";
$password="Password123";
$baza="3931_mail_opt";
/*mysql_connect($cfgHote, $cfgUser, $cfgPass) or die("Nie można połączyć się z bazą danych w tym momencie. Spróbuj później. Przepraszamy.");
mysql_query('SET character_set_connection=utf8_polish_ci');
mysql_query('SET character_set_client=utf8_polish_ci');
mysql_query('SET character_set_result=utf8_polish_ci');
mysql_query('SET NAME utf8');
mysql_select_db($baza);*/

$conn = new mysqli($servername, $username, $password, $baza);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//mysql_close();
?>