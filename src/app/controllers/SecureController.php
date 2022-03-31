<?php
use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

class SecureController extends Controller
{
    /**
     * Function to build ACL file
     * adding roles, components
     * allow access to roles
     *
     * @return void
     */
    public function buildACLAction()
    {
        $aclFile=APP_PATH.'/security/acl.cache';
        if (is_file($aclFile)==!true) {
            $acl=new Memory();
            //add roles
            $components=Components::find();
            $roles=Roles::find();
            $permissions=Permissions::find();
            foreach ($roles as $value) {
                $acl->addRole($value->role);
            }
            foreach ($components as $val) {
                $acl->addComponent($val->name, explode(',', $val->actions));
            }
            $acl->allow('admin', '*', '*');
            foreach ($permissions as $val) {
                $acl->allow($val->role, $val->components, $val->actions);
            }
            file_put_contents(
                $aclFile,
                serialize($acl)
            );
        } else {
            $acl=unserialize(
                file_get_contents($aclFile)
            );
        }
        $this->response->redirect('');
    }
}
