<!doctype html>
<html lang="pl">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/my_style.css" />

    <title>Poczta e-mail</title>
  </head>
  <body>
      
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="text-center mt-3 mb-3">Poczta e-mail</h1>
        </div>
    </div>
</div>
      
<div class="container">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb bg-light border border-secondary" >
                <li class="breadcrumb-item"><a href="index.php">Poczta e-mail</a></li>
                <?php
                  if(isset($_SESSION['breadcrumb']))
                  {
                      echo '<li class="breadcrumb-item active" aria-current="page">'.$_SESSION['breadcrumb'].'</li>';
                  }
                ?>
              </ol>
            </nav>
        </div>
    </div>
</div>