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
}
