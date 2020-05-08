<?php

//turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

//require auto load file
require_once("vendor/autoload.php");
require_once('model/validation-functions.php');

//instantiate f3 base class (create an instance of the base class)
$f3 = Base::instance();

$f3->set('colors', array('pink', 'green', 'blue'));

//define a default root (what the user sees when they go to index page)
$f3->route('GET /', function() {
    $view = new Template();
    echo $view->render('views/pet-home.html');
});

$f3-> route('GET|POST /order', function($f3) {

    $_SESSION = array();

    //check if the form has been posted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Validate the data
        //var_dump($_POST);
        $pets = $_POST['pet'];

        if(validString($pets)) {
           $_SESSION['pet'] = $pets;
           $f3->reroute('/order2');
        } else {
            $f3->set("errors['pet']", "Please enter an animal");
        }
    }

    $view = new Template();
    echo $view->render("views/order.html");
});

$f3->route('GET|POST /order2', function($f3){
    //echo "Thank You!";
    //echo "<p>" . $_SESSION['pet'] . $_SESSION['pets'] . "</p>";


    //check if the form has been posted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Validate the data
        //var_dump($_POST);

        $color = $_POST['color'];
        if(validColor($color)) {
            $_SESSION['color'] = $color;
            $f3->reroute('/summary');
        } else {
            $f3->set("errors['color']", "Please select a color");
        }
    }

    $view = new Template();
    echo $view->render('views/form2.html');
});

$f3->route('GET|POST /summary', function(){
    //var_dump($_SESSION);
    //echo "Thank You!";
    //echo "<p>" . $_SESSION['pet'] . $_SESSION['pets'] . "</p>";

    $view = new Template();
    echo $view->render('views/summary.html');
    session_destroy();
});

//run fat free
$f3->run();
