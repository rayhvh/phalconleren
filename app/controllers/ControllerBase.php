<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    public function initialize() {
        //$this->view->setVar('secure', false);
    }

    public function handleSecurity($getrank)
    {
        $rank = $this->session->get("rank");

        if ($getrank != $rank) {
            $this->dispatcher->forward([
                "controller" => "index",
                "action" => "index"
            ]);

            $this->flash->error("You do not have access to this page");
//            $this->view->setVar('secure', false);
        }

//        $this->view->setVar('secure', true);

    }
}