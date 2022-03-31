<?php
namespace App\Notification;

use OrderController;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use ProductController;
use Settings;

class NotificationListener extends Injectable
{
    /**
     * event handler to optimize the title based on settings
     *  Adding default value to price, stock if they are empty
     * @param Event $event
     * @param ProductController $product
     * @param [type] $data
     * @return void
     */
    public function titleOptimize(Event $event, ProductController $product, $data)
    {
        $setting=Settings::findFirst();

        if (isset($setting->id)) {
            if ($setting->title_optimization=='on' && $data['tags']!='') {
                $data['name'].='+'.$data['tags'];
            }
            if ($data['price']=='') {
                $data['price']=$setting->price;
            }
            if ($data['stock']=='') {
                $data['stock']=$setting->stock;
            }
        }
        return $data;
    }

    /**
     * event handler to add zipcode if it is empty
     *
     * @param Event $event
     * @param OrderController $order
     * @param [type] $data
     * @return void
     */
    public function defaultOrderData(Event $event, OrderController $order, $data)
    {
        $setting=Settings::findFirst();
        if ($data['zipcode']=='' && isset($setting->zipcode)) {
            $data['zipcode']=$setting->zipcode;
        }
        return $data;
    }

    public function beforeHandleRequest(Event $event, \Phalcon\Mvc\Application $application)
    {
        $controller=$this->router->getControllerName() ?? 'index';
        $action=$this->router->getActionName() ?? 'index';
        $aclFile=APP_PATH.'/security/acl.cache';
        if (is_file($aclFile)==true) {
            $acl=unserialize(
                file_get_contents($aclFile)
            );
            $role=$application->request->get('role');
            if ($acl->isAllowed($role, $controller, $action)!==true) {
                die('<h1 style="color:red;">Access denied!</h1>');
            }
        } else {
            $this->response->redirect('secure/buildACL');
        }
        
    }
}
