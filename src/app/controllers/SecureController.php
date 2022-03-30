<?php
use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

class SecureController extends Controller
{
    public function buildACLAction()
    {
        $aclFile=APP_PATH.'/security/acl.cache';
        if (is_file($aclFile)==!true) {
            $acl=new Memory();
            //add roles
            $components=Components::find();
            $roles=Roles::find();
            foreach ($roles as $value) {
                $acl->addRole($value->role);
                // die(var_dump($value->role));
            }
            foreach ($components as $val) {
                $acl->addComponent($val->name, [$val->actions]);
                // die(var_dump($val->actions));
            }
            $acl->allow('admin', '*', '*');
            // $acl->allow('guest', 'product', 'index');
            // $acl->allow('manager', 'product', 'add');
            // $acl->allow('manager', 'product', 'index');
            file_put_contents(
                $aclFile,
                serialize($acl)
            );
        } else {
            $acl=unserialize(
                file_get_contents($aclFile)
            );
        }
    }
}
