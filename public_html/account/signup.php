<?php require_once '../../private/initialize.php' ?>
<?php
$page_title = 'Signup';
$page_style = 'signup';
unset_session();
?>

<?php include_once '../../private/shared/default_header.php'; ?>

<?php

$new_user = [];

$new_user['user_name'] = '';
$new_user['user_first'] = '';
$new_user['user_last'] = '';
$new_user['user_email'] = '';

if (request_is_post()) {

    $user = find_user_by_username("SYSTEM");

    if (!password_verify($_POST['SYS_password'], $user['user_password'])) {
        redirect_to(url_for('/index.php'));
    } else {

        $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $new_user['user_name'] = hsc($_POST['username']);
        $new_user['user_first'] = hsc($_POST['firstname']);
        $new_user['user_last'] = hsc($_POST['lastname']);
        $new_user['user_hashed_password'] = $hashed_password;
        $new_user['user_password'] = $_POST['password'];
        //$new_user['user_email'] = hsc($_POST['email']);

        $result = add_new_user($new_user);

        if ($result !== true) {
            $errors = $result;
        }
    }
}
?>

<div class="content">

    <h2>Create a New Account</h2>

    <?php echo display_errors($errors); ?>

    <form action="signup.php" method="POST">
        <label for="firstname">First Name</label>
        <br>
        <input type="text" name="firstname" id="firstname" value="<?php echo $new_user['user_first']; ?>">
        <br>
        <label for="lastname">Last Name</label>
        <br>
        <input type="text" name="lastname" id="lastname" value="<?php echo $new_user['user_last']; ?>">
        <br>
        <label for="username">Username</label>
        <br>
        <input type="text" name="username" id="username" value="<?php echo $new_user['user_name']; ?>">
        <br>
        <!--
        <label for="email">Email</label>
        <br>
        <input type="text" name="email" id="email" value="<?php echo $new_user['user_email']; ?>">
        <br>
        -->
        <label for="password">Password</label>
        <br>
        <!--Change type back to password for beta version-->
        <input type="text" name="password" id="password">
        <br>


        <!--Just a temporary solution so kids can't get on and create an annoying numbers of fake accounts-->
        <label for="SYS_password">SYSTEM PASSWORD</label>
        <br>
        <!--Change type back to password for beta version-->
        <input type="password" name="SYS_password" id="SYS_password">
        <br>
        <button name="submit" id="submitBtn">Create Account</button>
    </form>

</div>

<?php include_once '../../private/shared/default_footer.php'; ?>