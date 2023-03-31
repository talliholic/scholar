<?php $user = $user->read($user_id) ?>
<h1 style="width:100%;text-align:center;margin:0">Account</h1>
<div class="mid-width column">
    <fieldset class="grey">
        <legend>Account Details</legend>
        <table>
            <tr>
                <th>Name: </th>
                <td><?= $user["name"] ?></td>
            </tr>
            <?php if ($role === "Teacher") : ?>
                <tr>
                    <th>Email: </th>
                    <td><?= $user["email"] ?></td>
                </tr>
            <?php endif ?>
            <tr>
                <th>Username: </th>
                <td><?= $user["username"] ?></td>
            </tr>
            <?php if ($role === "Student") : ?>
                <tr>
                    <th>Password: </th>
                    <td><?= $user["password"] ?></td>
                </tr>
            <?php endif ?>
        </table>
    </fieldset>
</div>