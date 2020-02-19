<?php

namespace app\controllers;


use exchanged\App;

class MainController extends AppController
{
    public function indexAction()
    {
        $name = 'Dima';
        $lastName = App::$app->getProper('nameAdmin');

        $this->setData(compact('name', 'lastName'));

    }
}