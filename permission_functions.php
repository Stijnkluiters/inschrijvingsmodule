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

function has_permission($permissionString)
{
    $dbh = db();
    $stmt = $dbh->prepare(PermissionBaseQuery());
    $stmt->bindParam('user_id', $_SESSION[authenticationSessionName], PDO::PARAM_STR);
    $stmt->bindParam('name',$permissionString,PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return count( $results ) > 0;
}