<?php
/**
 *  ITEC 370: Spring 2018
 *	Final Code: Data Controller
 *  Andrew McGuiness, Andrew Albanese, Ryan Kelley, Michael Hall
*/

include_once "createConnection.php";

/**
 * Upload some data into the DB
 *
 * @param $input        Input device the user selected
 * @param $age_group    Age group the user selected
 * @param $skill        Level of experience the user selected
 * @param $theme        Theme that is in use by the player
 * @return int|mixed    ID of the row that was created
 */
function InsertData($input, $age_group, $skill, $theme)
{
    // Get the connection
    $conn = ConnectToDB();
    $query = "INSERT INTO `test_data` (`ID`, `input`, `age`, `skill`, `theme`, `score`) VALUES (NULL, '" . $input . "', '" . $age_group . "', '" . $skill . "', '" . $theme . "', 0 );";

    // Ensure the query was successful
    $valid = $conn->query($query);
    $ID = -1;
    if (!$valid) {
        die("Failed to insert into database");
    } else {
        $ID = $conn->insert_id;
    }

    $conn->close();

    // Return the ID for the data we uploaded
    return $ID;
}

/**
 * Insert the user's metrics into the DB and get a new ID.
 */
function getUserID()
{
    // Insert the data into the DB
    $ID = InsertData($_POST["inputSelect"], $_POST["ageSelect"], $_POST["skillSelect"], $_POST["usedTheme"]);

    // Print the ID that was obtained
    echo json_encode(
        array(
            "ID" => $ID
        )
    );
}

/**
 * Upload a hit event for a target to the database
 *
 * @param $ID               ID of the row to upload to
 * @param $number           Target number that was hit
 * @param $time             Time that the target was visible before destroyed
 * @param $misses           Number of input misses from player
 * @param $totalTargets     Total targets displayed so far
 */
function UploadTargetInfo($ID, $number, $time, $misses, $totalTargets)
{
    // Get the connection
    $conn = ConnectToDB();
    $query = "UPDATE `test_data` SET `T" . $number . "_Time` = '" . $time . "', `T" . $number . "_MISS` = '" . $misses . "', `Targets` = '" . $totalTargets . "'  WHERE `test_data`.`ID` = " . $ID . ";";

    // Ensure the query was successful
    $valid = $conn->query($query);
    if (!$valid) {
        die("Failed to insert into database");
    }

    $conn->close();
}


/**
 * Upload the player's score to the database.
 *
 * @param $ID       ID of the row to upload to.
 * @param $score    Score to upload.
 */
function UploadScore($ID, $score)
{
    // Get the connection
    $conn = ConnectToDB();
    $query = "UPDATE `test_data` SET `score` = '" . $score . "' WHERE `test_data`.`ID` = " . $ID . ";";

    // Ensure the query was successful
    $valid = $conn->query($query);
    if (!$valid) {
        die("Failed to insert into database");
    }

    $conn->close();
}

/**
 * Set the default theme for the game, this will restrict what themes the user can select.
 */
function SetTheme()
{
    // Get the connection
    $conn = ConnectToDB();
    $query = "UPDATE `game_preferences` SET `AvailableThemes` = '" . $_POST["theme"] . "' WHERE `game_preferences`.`ID` = 1;";

    // Ensure the query was successful
    $valid = $conn->query($query);
    if (!$valid) {
        die("Failed to insert into database");
    }

    $conn->close();
}

/**
 * Truncate the data from the database.  This will reset the ID and clear out all user data.
 */
function DeleteData()
{
    //function to create a table
    $conn = ConnectToDB();
    $query = 'TRUNCATE `test_data`';

    // Ensure the query was successful
    $valid = $conn->query($query);
    if (!$valid) {
        echo "failed";
    } else {
        echo "success";
    }
}

/**
 * Create a login session for an admin.
 */
function AdminLogin()
{
    session_start();

    if (isset($_POST['do_login'])) {

        // Check that login information against the database.
        $connect = ConnectToDB();

        $username = $_POST['username'];
        $password = $_POST['password'];

        $select_data = $connect->query("SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'");

        // Ensure the query was successful
        if ($row = $select_data->fetch_assoc()) {
            $_SESSION['username'] = $row['username'];
            echo "success";
        } else {
            echo "fail";
        }

        exit();
    }
}

//Direct the program flow based on a POST flag passed in from the caller.
if (isset($_POST["whatToDo"])) {
    $whatToDo = $_POST["whatToDo"];

    if ($whatToDo == "uploadScore") {
        UploadScore($_POST["ID"], $_POST["score"]);
    } elseif ($whatToDo == "uploadHit") {
        UploadTargetInfo($_POST["ID"], $_POST["number"], $_POST["time"], $_POST["misses"], $_POST["totalTargets"]);
    } elseif ($whatToDo == "generateID") {
        getUserID();
    } elseif ($whatToDo == "setTheme") {
        SetTheme();
    } elseif ($whatToDo == "deleteData") {
        DeleteData();
    } elseif ($whatToDo == "adminLogin") {
        AdminLogin();
    }
}
?>