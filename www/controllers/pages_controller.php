<?php

class PagesController
{
    public function home()
    {
        require_once('views/pages/home.php');
    }
    public function deck()
    {
        require_once('views/pages/deck.php');
    }
    public function card()
    {
        require_once('views/pages/card.php');
    }
    public function format()
    {
        require_once('views/pages/format.php');
    }
    public function error()
    {
        require_once('views/pages/error.php');
    }
}
