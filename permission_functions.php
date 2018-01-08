<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 26-10-2017
 * Time: 23:11
 */

// heeft recht
function has_permission($compareRole)
{

    startsession();
    if( isset($_SESSION[ authenticationSessionName ]) )
    {
        $role = get_account_his_role($_SESSION[ authenticationSessionName ])[ 'rolnaam' ];

        if( is_array($compareRole) )
        {

            // returns true or false
            return in_array($role, $compareRole);
        }
        else
        {
            // returns true or false
            return $role == $compareRole;
        }
    }
    else
    {
        echo 'No user has been defined';
        exit;
    }
}
// controleert of de rol recht heeft om de handeling uit te voeren
function handleUnauthenticatedRole($compareRol) {
    if(!has_permission($compareRol)) {
        logout();
        redirect('/login.php', 'Je hebt geen rechten om deze pagina te bekijken');

        exit;
    }
}
// bekijken of hij het evenement mag bekijken.
function viewEvent($event)
{

    if( $event[ 'account_id' ] != $_SESSION[ authenticationSessionName ] )
    {
        logout();
        redirect('/login.php', 'Je hebt geen rechten om deze pagina te bekijken');

        exit;
    }
}
