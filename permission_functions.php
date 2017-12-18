<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 26-10-2017
 * Time: 23:11
 */

function PermissionBaseQuery()
{
    $sql = 'SELECT p.name,u.id FROM user u 
          JOIN user_role ur ON u.id = ur.user_id 
          JOIN role r ON r.id = ur.role_id
          JOIN role_permission rp ON rp.role_id = r.id
          JOIN permission p ON p.id = rp.permission_id 
          WHERE u.id = :user_id AND p.name = :name';
    return $sql;
}

function has_permission($compareRole)
{
    startsession();
    $role = get_account_his_role($_SESSION[authenticationSessionName])['rolnaam'];
    if(is_array($compareRole)) {

        // returns true or false
        return in_array($role,$compareRole);
    } else {
        // returns true or false
        return $role == $compareRole;
    }
    // still here?
}

function handleUnauthenticatedRole($compareRol) {
    if(!has_permission($compareRol)) {
        //logout();
        //redirect('/login.php','Je hebt geen rechten om deze pagina te bekijken');
        dump('derp? uitgelogd');
        exit;
    }
}

function viewEvent($event)
{
    if($event['account_id'] != $_SESSION[authenticationSessionName]) {
//        logout();
//        redirect('/login.php','Je hebt geen rechten om deze pagina te bekijken');
        dump('derp? uitgelogd');
        exit;
    }
}
