<?php

/* stronicowanie START */
function stronicowanie($pozycje, $naStronie, $aktualna){

$iloscStron = ceil($pozycje/$naStronie);
 /*
<div class="container">
    <div class="row">*/
    echo '<div class="col">
            <nav aria-label="Page navigation example">
              <ul class="pagination pagination-sm justify-content-end">';
                if($aktualna != 1)
                {    
                echo '<li class="page-item">
                  <a class="page-link" href="index.php?strona='.($aktualna-1).'" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                  </a>
                </li>';
                }
    
               /* if($iloscStron > 7)
                {
                    if($aktualna == 1 or $aktualna == $iloscStron)
                    {$}
                }*/
    
                $stopKropki = 1;
                $a_display=0;
                for($i=1 ; $iloscStron >= $i ; $i++)
                {
                    
                    if($i == $aktualna)
                    {$active = 'active';}
                    else
                    {$active = '';}
                    
                    
                    if($iloscStron > 7)
                    {
                        if($i == 1 or $i == $iloscStron or $aktualna == 1 and $i<3)
                        {echo '<li class="page-item '.$active.'"><a class="page-link" href="index.php?strona='.$i.'">'.$i.'</a></li>';
                        $stopKropki = 1;}
                        elseif($aktualna == $iloscStron and $i>($iloscStron-3))
                        {echo '<li class="page-item '.$active.'"><a class="page-link" href="index.php?strona='.$i.'">'.$i.'</a></li>';
                        $stopKropki = 1;}
                        elseif($aktualna > 1 and $aktualna < $iloscStron and ($aktualna-1 == $i or $aktualna == $i or $aktualna+1 == $i))
                        {echo '<li class="page-item '.$active.'"><a class="page-link" href="index.php?strona='.$i.'">'.$i.'</a></li>';
                        $stopKropki = 1;}
                        elseif($stopKropki==1)
                        {$a_display++;
                         echo '<li class="page-item"><a id="display_'.$a_display.'" class="page-link" href="#">...</a></li>';
                         echo '<li class="page-item a_display_'.$a_display.'" style="display: none;"><a class="page-link" href="index.php?strona='.$i.'">'.$i.'</a></li>';
                        $stopKropki=0;}
                        elseif($stopKropki==0)
                        {echo '<li class="page-item a_display_'.$a_display.'" style="display: none;"><a class="page-link" href="index.php?strona='.$i.'">'.$i.'</a></li>';}
                        
                    }
                    else
                    {
                        echo '<li class="page-item '.$active.'"><a class="page-link" href="index.php?strona='.$i.'">'.$i.'</a></li>';
                    }
                
                }
                /*<li class="page-item active"><a class="page-link" href="#">2</a></li>*/
                if($aktualna != $iloscStron)
                {
                echo '<li class="page-item">
                  <a class="page-link" href="index.php?strona='.($aktualna+1).'" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                  </a>
                </li>';
                }
              echo '</ul>
            </nav>
        </div>';/*
    </div>
</div>*/
}
/* stronicowanie KONIEC */

/* data_czas START */
function data_czas($item){
    
    $dzien = date('d', strtotime($item));
    $miesiac = date('n', strtotime($item));
    $rok = date('Y', strtotime($item));

    $czas = date('H:i', strtotime($item));
    $czas_s = date('H:i:s', strtotime($item));

    $miesiac_pl = array(1 => 'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia');

    if(date('Y:m:d', strtotime("now")) == date('Y:m:d', strtotime($item)))
    {
        return '<span title="'.$rok." ".$miesiac_pl[$miesiac]." ".$dzien." ".$czas_s.'">'.$czas.'</span>';
    }
    elseif(date('Y:m:d', strtotime("now")-(60*60*24)) == date('Y:m:d', strtotime($item)))
    {
        return '<span title="'.$rok." ".$miesiac_pl[$miesiac]." ".$dzien." ".$czas_s.'">Wczoraj</span>';
    }
    else
    {
        return '<span title="'.$rok." ".$miesiac_pl[$miesiac]." ".$dzien." ".$czas_s.'">'.$dzien.' '.$miesiac_pl[$miesiac].'</span>';
    }
}
/* data_czas KONIEC */

/* sortowanie START */
function sortowanie(){

    if(isset($_GET['sort']))
    {
        $sort = trim(strip_tags($_GET['sort']));
        if($sort == 'a')
        {
            $_SESSION['sort'] = 'ASC';
            return $_SESSION['sort'];
        }

        if($sort == 'd')
        {
            $_SESSION['sort'] = 'DESC';
            return $_SESSION['sort'];
        }
    }

    if(isset($_SESSION['sort']))
    {
        return $_SESSION['sort'];
    }
    else
    {
        $default_sort = 'DESC';
        return $default_sort;
    }
}
/* sortowanie KONIEC */

/* sortowanie url START */
function sortowanie_url(){

    if(isset($_GET['sort']))
    {
        $sort = trim(strip_tags($_GET['sort']));
        if($sort == 'a')
        {
            return $_SERVER['PHP_SELF'].'?sort=d';
        }

        if($sort == 'd')
        {
            return $_SERVER['PHP_SELF'].'?sort=a';
        }
    }

    if(isset($_SESSION['sort']))
    {
        if($_SESSION['sort'] == 'ASC')
        {
            return $_SERVER['PHP_SELF'].'?sort=d';
        }

        if($_SESSION['sort'] == 'DESC')
        {
            return $_SERVER['PHP_SELF'].'?sort=a';
        }
    }
    else
    {
        return $_SERVER['PHP_SELF'].'?sort=a';
    }
}
/* sortowanie url KONIEC */

/* sortowanie icon START */
function sortowanie_icon(){

    if(isset($_GET['sort']))
    {
        $sort = trim(strip_tags($_GET['sort']));
        if($sort == 'a')
        {
            return 'sort_up';
        }

        if($sort == 'd')
        {
            return 'sort_down';
        }
    }

    if(isset($_SESSION['sort']))
    {
        if($_SESSION['sort'] == 'ASC')
        {
            return 'sort_up';
        }

        if($_SESSION['sort'] == 'DESC')
        {
            return 'sort_down';
        }
    }
    else
    {
        return 'sort_down';
    }
}
/* sortowanie icon KONIEC */
?>
