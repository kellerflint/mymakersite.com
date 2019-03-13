<?php require_once '../../private/initialize.php' ?>
<?php 
$page_title = 'Signup';
$page_style = 'signup'; 
?>

<?php include_once '../../private/shared/default_header.php'; ?>

<?php 

$new_user = [];

$new_user['user_name'] = '';
$new_user['user_first'] = '';
$new_user['user_last'] = '';
$new_user['user_email'] = '';

if(request_is_post()) {
    
    $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $new_user['user_name'] = hsc($_POST['username']);
    $new_user['user_first'] = hsc($_POST['firstname']);
    $new_user['user_last'] = hsc($_POST['lastname']);
    $new_user['user__hashed_password'] = $hashed_password;
    $new_user['user_password'] = $_POST['password'];
    $new_user['user_email'] = hsc($_POST['email']);
    
    $result = add_new_user($new_user);

    if ($result !== true) {
        $errors = $result;
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
        <label for="email">Email</label>
        <br>
        <input type="text" name="email" id="email" value="<?php echo $new_user['user_email']; ?>">
        <br>
        <label for="password">Password</label>
        <br>
        <input type="password" name="password" id="password">
        <br>
        <button name="submit" id="submitBtn">Create Account</button>
    </form>

</div>

<?php include_once '../../private/shared/default_footer.php'; ?>