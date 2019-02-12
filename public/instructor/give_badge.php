<?php require_once '../../private/initialize.php'; ?>

<?php 
$page_title = 'Give Badge';
$page_style = 'give';
?>

<?php include_once SHARED_PATH . '/default_header.php'; ?>

<?php 

// Default form submit values
$for_rank = 'Unranked';
$user_name = '';
$badge_title = '';

// Defaults are overriden on submission if they are set
if (request_is_post()) {

    if (isset($_POST['rank']))
        $for_rank = $_POST['rank'];

    if (isset($_POST['username']))
        $user_name = $_POST['username'];

    if (isset($_POST['badge']))
        $badge_title = $_POST['badge'];

}
?>

<div class="content">

    <div id="user-box">
        <h2>Users</h2>
        <?php 

        // Get and confirm user set
        $user_set = find_all_users();
        confirm_result($user_set);

        // Iterate over all users, display name
        while ($user = mysqli_fetch_assoc($user_set)) { ?>
        <div data-user="<?php echo $user['user_name'] ?>" class="user-item">
            <p class="user-p">
                <?php echo $user['user_first'] . ' ' . $user['user_last']; ?>
            </p>
        </div>
        <?php 
    } ?>
        <!-- End of userbox -->
    </div>

    <div id="form-box">
        <form action="give_badge.php" method="POST" id="badge-form">
            <label for="for-rank">Badge Rank: </label>
            <select name="rank" id="for-rank" onchange="this.form.submit();">
                <?php
                // Display all rank options in dropdown
                $ranks_set = find_all_ranks();
                confirm_result($ranks_set);
                while ($rank = mysqli_fetch_assoc($ranks_set)) { ?>
                <option value="<?php echo $rank['rank_title'] ?>">
                    <?php echo $rank['rank_title']; ?>
                </option>
                <?php 
            } ?>
            </select>
            <br>
            <label for="username">Username: </label>
            <input type="text" name="username" id="username" value="">
            <br>
            <label for="badge">Badge: </label>
            <input type="text" name="badge" id="badge" value="">
            <br>
            <button name="submit-option" value="give" onclick="this.form.submit()">Give Badge</button>
            <button name="submit-option" value="remove" onclick="this.form.submit()">Remove Badge</button>
        </form>
        <!--End of form div-->
    </div>

    <!-- Displays result of give badge query -->
    <div id="result-box">
        <?php 
        if (request_is_post()) {
            // if submit-option is set then run give or remove, else run update
            if (isset($_POST['submit-option'])) {
                if ($_POST['submit-option'] == 'give') {
                    echo "give";
                //give_badge($user, $badge);
                } else if ($_POST['submit-option'] == 'remove') {
                    echo "remove";
                //remove_badge($user, $badge);
                }
            } else {
                echo "update";
            }

        } ?>
    </div>

    <div id="badge-box">
        <h2>Badges user doesn't have</h2>

        <div data-rank="" class="badge-item">
            <p>
            </p>
        </div>
    </div>

    <script src="<?php echo '../../private/scripts/give_fill_form.js'; ?>"></script>

    <?php include_once SHARED_PATH . '/default_footer.php'; ?>



    <?php 

// Page function definitions

// Given user_name and badge_title gives user badge or displays errors
    function give_badge($user, $badge)
    {
        $user = find_user($user_name);
        confirm_result($user);

        $badge = find_badge($badge_title);
        confirm_result($badge);

        $sql = "INSERT INTO User_Badge VALUES (" . $user['user_id'] . ", " . $badge['badge_id'] . ", now())";
        $result = mysqli_query($db, $sql);

        if ($result) {
            echo "Badge " . $badge_title . " given to user " . $user_name;
        } else {
            echo "Badge insert failed: " . mysqli_error($db);
        }
    }

    function remove_badge($user, $badge)
    {
        $user = find_user($user_name);
        confirm_result($user);

        $badge = find_badge($badge_title);
        confirm_result($badge);

        $sql = "DELETE FROM User_Badge WHERE user_id = " . $user['user_id'] . " AND badge_id = " . $badge['badge_id'] . ";";
        $result = mysqli_query($db, $sql);

        if ($result) {
            echo "Rank " . $badge_title . " removed from user " . $user_name;
        } else {
            echo "Rank deletion failed: " . mysqli_error($db);
        }
    }

    ?>