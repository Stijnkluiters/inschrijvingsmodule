<?php


$db = db();

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $naam => $rol_id) {

        if ($naam != 'submit') {
            // switch case for every number in rol number
            switch ($rol_id) {
                case 1:
                    $rol_id = check_if_role_exists('leerling');
                    break;
                case 2:
                    $rol_id = check_if_role_exists('externbedrijf');
                    break;
                case 3:
                    $rol_id = check_if_role_exists('docent');
                    break;
                case 4:
                    $rol_id = check_if_role_exists('beheerder');
                    break;
            }

            $account_id = explode('-',$naam)[1];

            $stmt = $db->prepare('UPDATE account SET 
            rol_id = :rol_id
            WHERE account_id = :account_id
            ');
            $stmt->bindParam('rol_id',$rol_id);
            $stmt->bindParam('account_id', $account_id);
            $stmt->execute();
        }
    }
}
$stmt = $db->prepare('select * from account WHERE account_id <> :account_id ORDER BY gebruikersnaam');
$stmt->bindParam('account_id', $_SESSION[authenticationSessionName]);
$stmt->execute();
$accounts = $stmt->fetchAll();

foreach ($accounts as $account) {
    $users[] = get_user_info($account);
}

?>
<div class="card">

    <form action="<?= route('/index.php?gebruiker=roltoewijzen'); ?>" method="post" enctype="multipart/form-data"
          class="form-horizontal">
        <table class="table">
            <caption>Lijst met gebruikers</caption>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Naam + gebruikersnaam</th>
                <th scope="col">Student</th>
                <th scope="col">Extern contactpersoon</th>
                <th scope="col">Docent</th>
                <th scope="col">Beheerder</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(!empty($users)) {
                foreach ($users as $regelnummer => $account) {
                    ?>
                    <tr>
                        <td><?= ++$regelnummer; ?></td>
                        <td><?= formatusername($account) . ' ' . $account['gebruikersnaam'] ?></td>
                        <td>
                            <input <?= ($account['rolnaam'] == 'leerling') ? 'checked=""' : ''; ?> type="radio"
                                                                                                   name="rol-<?= $account[0] ?>"
                                                                                                   value="<?= 1 ?>">
                        </td>
                        <td>

                            <input <?= ($account['rolnaam'] == 'externbedrijf') ? 'checked=""' : ''; ?> type="radio"
                                                                                                        name="rol-<?= $account[0] ?>"
                                                                                                        value="<?= 2 ?>">
                        </td>
                        <td>
                            <input <?= ($account['rolnaam'] == 'docent') ? 'checked=""' : ''; ?> type="radio"
                                                                                                 name="rol-<?= $account[0] ?>"
                                                                                                 value="<?= 3 ?>">
                        </td>
                        <td>
                            <input <?= ($account['rolnaam'] == 'beheerder') ? 'checked=""' : ''; ?> type="radio"
                                                                                                    name="rol-<?= $account[0]; ?>"
                                                                                                    value="<?= 4 ?>">
                        </td>
                    </tr>

                    <?php
                }
            }
            else{
                print("<p class='bg-danger'>! er zijn op dit moment geen gebruikers behalve jezelf!</p>");
            }
            ?>

            </tbody>
        </table>
        <div class="card-footer">
            <button type="submit" class="btn btn-sm btn-primary" name="submit"><i class="fa fa-dot-circle-o"></i>
                Toewijzen
            </button>
        </div>
    </form>
</div>
