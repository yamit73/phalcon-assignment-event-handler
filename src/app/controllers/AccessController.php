<?php

use Phalcon\Mvc\Controller;

class AccessController extends Controller
{
    public function indexAction()
    {
        
    }

    /**
     * Add role to database
     *
     * @return void
     */
    public function permissionAction()
    {
        $components=Components::find();
        $this->view->roles=Roles::find();
        $controllers=array();
        $actions=array();
        foreach ($components as $val) {
            array_push($controllers, $val->name);
            $actions[$val->name]=explode(',', $val->actions);
        }
        echo(json_encode($controllers));
        echo(json_encode($actions['order']));
        
        $this->view->controllers=$controllers;
        $this->view->actions=$actions;
        // if ($this->request->isPost()) {
        //     $component=new Components();
        //     //code to sanitize data using escaper
        //     $postData = $this->request->getPost();
        //     $escaper=new \App\Components\MyEscaper();
        //     $data=$escaper->sanitize($postData);
        // }
    }
}
