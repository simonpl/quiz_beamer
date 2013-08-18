<?php
/* 
    Mit quiz_beamer kann ein Quizabend, z.B. auf Jugendfreizeiten durchgeführt werden.
    Copyright (C) 2013 Simon Plasger

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require('settings.php');
require('lang/'.QUIZ_LANGUAGE.'.php');
$con = mysql_connect(QUIZ_DATABASE_HOST, QUIZ_DATABASE_USER, QUIZ_DATABASE_PASSWORD);
if(!$con)
{
    die(QUIZ_LANGUAGE_DATABASE_CONNECT_ERROR);
}
$sel = mysql_select_db(QUIZ_DATABASE);
if(!$sel)
{
    die(QUIZ_LANGUAGE_DATABASE_CHOOSE_ERROR);
}
session_start();
if(!isset($_SESSION['group']))
    $_SESSION['group'] = 0;
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo QUIZ_BROWSER_TITLE;?></title>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
    </head>
    <body style="margin:auto;margin-bottom:0px">
        <div style="margin-top:auto;margin-bottom:0px">
<?php
if(isset($_GET['action']) && $_GET['action'] == 'resetall')
{
    $queryerg = mysql_query('UPDATE `questions` SET `used`=0');
    $queryerg = mysql_query('UPDATE `groups` SET `points`=0');
    $_SESSION['group'] = 0;
    echo '<p>'.QUIZ_LANGUAGE_RESET.'</p>';
}
/* Frage stellen */
else if(isset($_GET['action']) && $_GET['action'] == 'question')
{
    if(!is_numeric($_GET['quest']))
    {
        die(QUIZ_LANGUAGE_SQL_INJECTION_DETECT);
    }
    $quest = (int) $_GET['quest'];
    $queryerg = mysql_query('SELECT * FROM `questions` WHERE `id`='.$quest, $con);
    $erg = mysql_fetch_assoc($queryerg);
    /* Frage mit vier Antwortmöglichkeiten */
    if($erg['type'] == 'question')
    {
        echo '<div style="margin-top:100px;margin-left:40px;margin-right:40px">';
        echo '<h2>'.$erg['text'].'</h2>';
        echo '<table>
            <tr>
                <td><h3>A:</h3></td>
                <td><h3><a href="quiz.php?action=answer&amp;quest='.$quest.'&amp;checked=A">'.$erg['answer_a'].'</a></h3></td>
            </tr>
            <tr>
                <td><h3>B:</h3></td>
                <td><h3><a href="quiz.php?action=answer&amp;quest='.$quest.'&amp;checked=B">'.$erg['answer_b'].'</a></h3></td>
            </tr>
            <tr>
                <td><h3>C:</h3></td>
                <td><h3><a href="quiz.php?action=answer&amp;quest='.$quest.'&amp;checked=C">'.$erg['answer_c'].'</a></h3></td>
            </tr>
            <tr>
                <td><h3>D:</h3></td>
                <td><h3><a href="quiz.php?action=answer&amp;quest='.$quest.'&amp;checked=D">'.$erg['answer_d'].'</a></h3></td>
            </tr>
        </table>';
    }
    /* Schätzfrage */
    else if($erg['type'] == 'estimate')
    {
        echo '<div style="margin-top:15%;margin-left:30%;margin-right:40px">';
        echo '<h1>'.$erg['text'].'</h1>';
        echo '<h3>'.QUIZ_LANGUAGE_TOLERANCE.' +/- '.$erg['marge'].'</h3>';
        echo '<form action=quiz.php?action=answer2&amp;quest='.$quest.' method="POST">';
        echo '<input type="text" name="value" /><br />';
        echo '<input type="submit" name="answer" value="'.QUIZ_LANGUAGE_ANSWER.'" />';
    }
    /* Schnellfragerunde */
    else if($erg['type'] == 'fast')
    {
        echo '<div style="margin-top:15%;margin-left:30%;margin-right:40px">';
        echo '<h1>'.$erg['text'].'</h1>';
        echo '<h4>'.QUIZ_LANGUAGE_HOW_MUCH.'</h4>';
        echo '<form action=quiz.php?action=answer3&amp;quest='.$quest.' method="POST">';
        echo '<input type="text" name="value" /><br />';
        echo '<input type="submit" name="answer" value="'.QUIZ_LANGUAGE_CONTINUE.'" />';
    }
    /* Frage zum Sortieren */
    else if($erg['type'] == 'sort')
    {
        echo '<div style="margin-top:15%;margin-left:30%;margin-right:40px">';
        echo '<h1>'.$erg['text'].'</h1>';
        echo '<p>'.$erg['text2'].'</p>';
        echo '<h3><a href="quiz.php?action=answer4&amp;answer=right&amp;quest='.$quest.'">'.QUIZ_LANGUAGE_SORT_CORRECT.'</a><br />';
        echo '<a href="quiz.php?action=answer4&amp;answer=false&amp;quest='.$quest.'">'.QUIZ_LANGUAGE_SORT_WRONG.'</a></h3>';
    }
    /* Aktion */
    else if($erg['type'] == 'action')
    {
        echo '<div style="margin-top:20%;margin-left:30%;margin-right:40px">';
        echo '<h1>'.$erg['text'].'</h1>';
        echo '<h6><small><a href="quiz.php?next=true">'.QUIZ_LANGUAGE_NEXT.'</small></h6>';
    	$queryerg = mysql_query('UPDATE `questions` SET used=1 WHERE `id`='.$quest, $con);
	}
    echo '</div>';
}
/* Antwort auf Multiple-Choice-Frage */
else if(isset($_GET['action']) && $_GET['action'] == 'answer')
{
    if(!is_numeric($_GET['quest']))
    {
        die(QUIZ_LANGUAGE_SQL_INJECTION_DETECT);
    }
    $quest = (int) $_GET['quest'];
    echo '<div style="margin-top:200px;margin-left:20%;margin-right:20%">';
    $queryerg = mysql_query('SELECT * FROM `questions` WHERE `id`='.$quest, $con);
    $erg = mysql_fetch_assoc($queryerg);
    $queryerg = mysql_query('UPDATE `questions` SET used=1 WHERE `id`='.$quest, $con);
    if($erg['correct'] == $_GET['checked'])
    {
        $queryerg = mysql_query('SELECT * FROM `groups` WHERE `id`='.$_SESSION['group']);
        $erg = mysql_fetch_assoc($queryerg);
        $points = $erg['points'];
        if($_GET['quest'] <= 4)
        {
            $points+=20;
            $points2 = 20;
        }
        else if($_GET['quest'] <= 8)
        {
            $points+=40;
            $points2 = 40;
        }
        else if($_GET['quest'] <= 12)
        {
            $points+=60;
            $points2 = 60;
        }
        else if($_GET['quest'] <= 16)
        {
            $points+=80;
            $points2 = 80;
        }
        else
        {
            $points+=100;
            $points2 = 100;
        }
        $queryerg = mysql_query('UPDATE `groups` SET `points`='.$points.' WHERE `id`='.$_SESSION['group']);
        echo '<h1 class="text-success" style="margin-left:0px">'.$points2.' '.QUIZ_LANGUAGE_POINTS_FOR_GROUP.' '.$_SESSION['group'].'.</h1>';
    }
    else
    {
        echo '<h1 class="text-error" style="margin-left:0px">0 '.QUIZ_LANGUAGE_POINTS_FOR_GROUP.' '.$_SESSION['group'].'.</h1>';
    }
    echo '<h6><small><a href="quiz.php?next=true">'.QUIZ_LANGUAGE_NEXT.'</small></h6>';
    echo '</div>';
}
/* Antwort auf Schätzfrage */
else if(isset($_GET['action']) && $_GET['action'] == 'answer2')
{
    if(!is_numeric($_GET['quest']))
    {
        die(QUIZ_LANGUAGE_SQL_INJECTION_DETECT);
    }
    $quest = (int) $_GET['quest'];
    echo '<div style="margin-top:200px;margin-left:20%;margin-right:20%">';
    $queryerg = mysql_query('SELECT * FROM `questions` WHERE `id`='.$quest, $con);
    $erg = mysql_fetch_assoc($queryerg);
    $queryerg = mysql_query('UPDATE `questions` SET used=1 WHERE `id`='.$quest, $con);
    if(($_POST['value'] == $erg['correct_value']) || ($_POST['value'] < $erg['correct_value'] && $_POST['value'] >= $erg['correct_value'] - $erg['marge_value']) || ($_POST['value'] > $erg['correct_value'] && $_POST['value'] <= $erg['correct_value'] + $erg['marge_value']))
    {
        
        $queryerg = mysql_query('SELECT * FROM `groups` WHERE `id`='.$_SESSION['group']);
        $erg = mysql_fetch_assoc($queryerg);
        $points = $erg['points'];
        if($quest <= 4)
        {
            $points+=20;
            $points2 = 20;
        }
        else if($quest <= 8)
        {
            $points+=40;
            $points2 = 40;
        }
        else if($quest <= 12)
        {
            $points+=60;
            $points2 = 60;
        }
        else if($quest <= 16)
        {
            $points+=80;
            $points2 = 80;
        }
        else
        {
            $points+=100;
            $points2 = 100;
        }
        $queryerg = mysql_query('UPDATE `groups` SET `points`='.$points.' WHERE `id`='.$_SESSION['group']);
        echo '<h1 class="text-success" style="margin-left:0px">'.$points2.' '.QUIZ_LANGUAGE_POINTS_FOR_GROUP.' '.$_SESSION['group'].'.</h1>';
    }
    else
    {
        echo '<h1 class="text-error" style="margin-left:0px">0 '.QUIZ_LANGUAGE_POINTS_FOR_GROUP.' '.$_SESSION['group'].'.</h1>';
    }
    echo '<h6><small><a href="quiz.php?next=true">'.QUIZ_LANGUAGE_NEXT.'</small></h6>';
    echo '</div>';
}
/* Nach Schnellfragerunde */ 
else if(isset($_GET['action']) && $_GET['action'] == 'answer3')
{
    if(!is_numeric($_GET['quest']) || !is_numeric($_POST['value']))
    {
        die(QUIZ_LANGUAGE_SQL_INJECTION_DETECT);
    }
    $quest = (int) $_GET['quest'];
    $number = (int) $_POST['value'];
    echo '<div style="margin-top:200px;margin-left:20%;margin-right:20%">';
    $queryerg = mysql_query('SELECT * FROM `questions` WHERE `id`='.$quest, $con);
    $erg = mysql_fetch_assoc($queryerg);
    $queryerg = mysql_query('UPDATE `questions` SET used=1 WHERE `id`='.$quest, $con);
    $queryerg = mysql_query('SELECT * FROM `groups` WHERE `id`='.$_SESSION['group']);
    $erg = mysql_fetch_assoc($queryerg);
    $points = $erg['points'];
    $points += $number * 20;
    $queryerg = mysql_query('UPDATE `groups` SET `points`='.$points.' WHERE `id`='.$_SESSION['group']);
    if($_POST['value'] > 0)
    {
        echo '<h1 class="text-success" style="margin-left:0px">'.$number * 20 .' '.QUIZ_LANGUAGE_POINTS_FOR_GROUP.' '.$_SESSION['group'].'.</h1>';
    }
    else
    {
        echo '<h1 class="text-error" style="margin-left:0px">0 '.QUIZ_LANGUAGE_POINTS_FOR_GROUP.' '.$_SESSION['group'].'.</h1>';
    }
    echo '<h6><small><a href="quiz.php?next=true">'.QUIZ_LANGUAGE_NEXT.'</small></h6>';
    echo '</div>';
}
/* Antwort auf Sortierfrage */
else if(isset($_GET['action']) && $_GET['action'] == 'answer4')
{
    if(!is_numeric($_GET['quest']))
    {
        die(QUIZ_LANGUAGE_SQL_INJECTION_DETECT);
    }
    $quest = (int) $_GET['quest'];
    echo '<div style="margin-top:200px;margin-left:20%;margin-right:20%">';
    $queryerg = mysql_query('SELECT * FROM `questions` WHERE `id`='.$quest, $con);
    $erg = mysql_fetch_assoc($queryerg);
    $queryerg = mysql_query('UPDATE `questions` SET used=1 WHERE `id`='.$quest, $con);
    if(isset($_GET['answer']) && $_GET['answer'] == 'right')
    {
        $queryerg = mysql_query('SELECT * FROM `groups` WHERE `id`='.$_SESSION['group']);
        $erg = mysql_fetch_assoc($queryerg);
        $points = $erg['points'];
        if($quest <= 4)
        {
            $points+=20;
            $points2 = 20;
        }
        else if($quest <= 8)
        {
            $points+=40;
            $points2 = 40;
        }
        else if($quest <= 12)
        {
            $points+=60;
            $points2 = 60;
        }
        else if($quest <= 16)
        {
            $points+=80;
            $points2 = 80;
        }
        else
        {
            $points+=100;
            $points2 = 100;
        }
        $queryerg = mysql_query('UPDATE `groups` SET `points`='.$points.' WHERE `id`='.$_SESSION['group']);
        echo '<h1 class="text-success" style="margin-left:0px">'.$points2.' '.QUIZ_LANGUAGE_POINTS_FOR_GROUP.' '.$_SESSION['group'].'.</h1>';
    }
    else
    {
        echo '<h1 class="text-error" style="margin-left:0px">0 '.QUIZ_LANGUAGE_POINTS_FOR_GROUP.' '.$_SESSION['group'].'.</h1>';
    }
    echo '<h6><small><a href="quiz.php?next=true">'.QUIZ_LANGUAGE_NEXT.'</small></h6>';
    echo '</div>';
}
/* Normaler Startscreen */
else
{
    if(isset($_GET['next']) && $_GET['next'] == 'true')
    {
        if($_SESSION['group'] == 4)
        {
            $_SESSION['group'] = 1;
        }
        else
        {
            $_SESSION['group']++;
        }
    }
    if($_SESSION['group'] == 0)
    {
        $_SESSION['group'] = 1;
    }
?>

            <table class="table table-bordered" style="margin-bottom:0px">
                <thead>
                    <tr>
                        <th><p class="text-center" style="margin:0px;font-family:inherit;font-weight:bold;line-height:40px;color:inherit;text-rendering:optimizelegibility;font-size:24.5px"><?php echo QUIZ_CATEGORY_1;?></p></th>
                        <th><p class="text-center" style="margin:0px;font-family:inherit;font-weight:bold;line-height:40px;color:inherit;text-rendering:optimizelegibility;font-size:24.5px"><?php echo QUIZ_CATEGORY_2;?></p></th>
                        <th><p class="text-center" style="margin:0px;font-family:inherit;font-weight:bold;line-height:40px;color:inherit;text-rendering:optimizelegibility;font-size:24.5px"><?php echo QUIZ_CATEGORY_3;?></p></th>
                        <th><p class="text-center" style="margin:0px;font-family:inherit;font-weight:bold;line-height:40px;color:inherit;text-rendering:optimizelegibility;font-size:24.5px"><?php echo QUIZ_CATEGORY_4;?></p></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
<?
                        $queryerg = mysql_query('SELECT * FROM questions');
                        for($i = 1;$i <= 20;$i++)
                        {
                            $erg = mysql_fetch_assoc($queryerg);
                            echo '                        <td';
                            if($erg['used'] == 1)
                            {
                                echo ' style="background-color:#999999"';
                            }
                            echo '><h1 class="text-center" style="margin-top:7px;margin-bottom:7px">';
                            if($erg['used'] == 0)
                            {
                                echo '<a href="quiz.php?action=question&amp;quest=';
                                echo $erg['id'];
                                echo '">';
                            }
                            if($i <= 4)
                                echo '20';
                            else if($i <= 8)
                                echo '40';
                            else if($i <= 12)
                                echo '60';
                            else if($i <= 16)
                                echo '80';
                            else
                                echo '100';
                            if($erg['used'] == 0)
                            {
                                echo '</a>';
                            }
                            echo '</h1></td>'."\n";
                            if($i == 4 || $i == 8 || $i == 12 || $i == 16)
                                echo '                    </tr>'."\n".'                    <tr>'."\n";
                         }
                         ?>
                    </tr>
                </tbody>
            </table>
            <br />
            <p style="margin-left:40%"><?php echo QUIZ_LANGUAGE_GROUP.' '.$_SESSION['group'].' '.QUIZ_LANGUAGE_MAY_CHOOSE;?>.</p>
            <br />
<?php
    echo '<div style="width:50%;margin-left:25%"><table class="table"><tr><td>'.QUIZ_LANGUAGE_GROUP.' 1</td><td>'.QUIZ_LANGUAGE_GROUP.' 2</td><td>'.QUIZ_LANGUAGE_GROUP.' 3</td><td>'.QUIZ_LANGUAGE_GROUP.' 4</td></tr><tr>';
    $queryerg = mysql_query('SELECT * FROM `groups`');
    while($erg = mysql_fetch_assoc($queryerg))
    {
        echo '<td>'.$erg['points'].'</td>';
    }
    echo '</table></div>';
}
?>
        </div>
    </body>
</html>
