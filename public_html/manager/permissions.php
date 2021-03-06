<?php require_once '../../private/initialize.php'; ?>
<?php 
    require_permission(MNG);
    $page_title = 'Permissions';
    $page_style = 'permissions';
?>

<?php include_once SHARED_PATH . '/default_header.php'; ?>

<?php 
// Default form submit values
$user_id = '';

// Defaults are overriden on submission if they are set
if (request_is_post()) {
    if (isset($_POST['user_id']))
        $user_id = $_POST['user_id'];
}
?>

<div class="content">
    <div id="user-box">
        <h2>Users</h2>
        <?php 
        $user_set = find_users_by_session($_SESSION['session_id']);
        $user_count = 0;
        while ($user = mysqli_fetch_assoc($user_set)) {
            ?>

        <div data-user="<?php echo $user['user_id']; ?>" class="user-item 
            <?php echo even_odd($user_count); ?>">
            <p>
                <?php echo $user['user_first'] . ' ' . $user['user_last']; ?>
            </p>
        </div>

        <?php
        $user_count++;
    } ?>
    </div>

    <div id="form-box">
        <form action="permissions.php" method="POST" id="permission-form">
            <?php $permissions = get_session_permissions($_SESSION['session_id'], $user_id); ?>
            <label class="permission-checkbox-label" for="viewer">Viewer</label>
            <input type="checkbox" class="permission-checkbox" id="viewer"
                <?php if (in_array(VWR, $permissions)) echo 'checked' ?>>
            <label class="permission-checkbox-label" for="user">User</label>
            <input type="checkbox" class="permission-checkbox" id="user"
                <?php if (in_array(USR, $permissions)) echo 'checked' ?>>
            <label class="permission-checkbox-label" for="promoter">Promoter</label>
            <input type="checkbox" class="permission-checkbox" id="promoter"
                <?php if (in_array(PMT, $permissions)) echo 'checked' ?>>
            <label class="permission-checkbox-label" for="manager">Manager</label>
            <input type="checkbox" class="permission-checkbox" id="manager"
                <?php if (in_array(MNG, $permissions)) echo 'checked' ?>>
            <?php if (check_permission(ADM) || check_permission(OWN)) { ?>
            <label class="permission-checkbox-label" for="admin">Admin</label>
            <input type="checkbox" class="permission-checkbox" id="admin"
                <?php if (in_array(ADM, $permissions)) echo 'checked' ?>>
            <?php } ?>
            <?php if (check_permission(OWN)) { ?>
            <label class="permission-checkbox-label" for="owner">Owner</label>
            <input type="checkbox" class="permission-checkbox" id="owner"
                <?php if (in_array(OWN, $permissions)) echo 'checked' ?>>
            <?php } ?>
            <input type="hidden" name="permissions_string" id="permissions_string" value="">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
            <button name="submit-option" value="update" id="update">Update Permissions</button>
        </form>

        <div id="result">
            <?php
            if (request_is_post()) {
                if(isset($_POST['submit-option'])) {
                    $permission_array = explode(',', $_POST['permissions_string']);
                    $result = update_permissions($permission_array, $_POST['user_id'], 
                                                    $_SESSION['session_id'], $_SESSION['user_id']);
                        // redirect only works if I delete buttons and input due to headers already sent.
                        // But this works perfectly on give_rank.php so I have no idea what the problem is.
                        // So until I figure it out the page has to be reloaded to show changes to the permissions.
                        //redirect_to(url_for('/manager/permissions.php'));
                }
            }
            ?>
        </div>
    </div>
</div>

<?php include_once SHARED_PATH . '/default_footer.php'; ?>