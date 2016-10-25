<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CharactersController extends ControllerBase
{

//    Show the index of characters
    public function IndexAction()
    {
        $userid = $this->session->get("user_id");

        if (!isset($userid))
       {
               $this->dispatcher->forward([
                   "controller" => "index",
                   "action" => "index"
               ]);

           $this->flash->error("You do not have access to this page");

           return;
       }

//        $userid = $this->session->get("user_id");
        $characters = Characters::find("users_id = '" . $userid . "'");

//        var_dump($characters);
        $this->view->setVar('characters', $characters);

        if (count($characters) == 0) {
            $this->flash->notice("You do not have any characters");

            $this->dispatcher->forward([
                "controller" => "news",
                "action" => "index"
            ]);

            return;
        }
    }

    public function NewAction()
    {

    }

    public function ConfirmAction()
    {

    }
}