<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 9-11-2017
 * Time: 20:50
 */


$db = db();


$docentenQuery = $db->prepare('SELECT DISTINCT 
gebruiker.naam as naam, 
gebruiker.email as email, 
gebruiker.docentcode as docentcode, 
gebruiker.geboortedatum as geboortedatum
FROM gebruiker 
join gebruiker_heeft_rol ON gebruiker.id = gebruiker_heeft_rol.gebruiker_id 
JOIN rol on gebruiker_heeft_rol.rol_id = rol.id
WHERE rol = "docent";');
$docentenQuery->execute();
$docenten = $docentenQuery->fetchAll();


?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Docenten
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Docentcode</th>
                        <th>e-mailadres</th>
                        <th>Geboortedatum</th>
                        <!--<th>Geslacht</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($docenten as $docent)
                    {
                        echo "<tr>
                            <td>" . $docent[ 'naam' ] . "</td>
                            <td>" . $docent[ 'docentcode' ] . "</td>
                            <td>" . $docent[ 'email' ] . "</td>
                            <td>" . $docent[ 'datum' ] . "</td>                            
                        </tr>";
                    }

                    ?>
                    </tbody>
                </table>
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--/.col-->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Striped Table
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Date registered</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Yiorgos Avraamu</td>
                        <td>2012/01/01</td>
                        <td>Member</td>
                        <td>
                            <span class="badge badge-success">Active</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Avram Tarasios</td>
                        <td>2012/02/01</td>
                        <td>Staff</td>
                        <td>
                            <span class="badge badge-danger">Banned</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Quintin Ed</td>
                        <td>2012/02/01</td>
                        <td>Admin</td>
                        <td>
                            <span class="badge badge-secondary">Inactive</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Enéas Kwadwo</td>
                        <td>2012/03/01</td>
                        <td>Member</td>
                        <td>
                            <span class="badge badge-warning">Pending</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Agapetus Tadeáš</td>
                        <td>2012/01/21</td>
                        <td>Staff</td>
                        <td>
                            <span class="badge badge-success">Active</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--/.col-->
</div>
