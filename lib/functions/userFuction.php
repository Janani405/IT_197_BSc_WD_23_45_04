<?php
// Start output buffering to avoid issues with header redirection
ob_start();

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include_once("db_conn.php");

// User registration function
function userRegistration($userName, $userEmail, $userPass, $userPhone, $userNic) {
    // Create database connection
    $db_conn = Connection();

    // Data insert query for user
    $insertSql = "INSERT INTO user_table(user_name, user_email, user_phone, user_nic, user_status)
                  VALUES('$userName', '$userEmail', '$userPhone', '$userNic', 1);";

    $sqlResult = mysqli_query($db_conn, $insertSql);

    // Check if the query was successful
    if (!$sqlResult) {
        echo mysqli_error($db_conn); // Print error if the query failed
        return "Error inserting user!";
    }

    // Use MD5 to hash the password
    $newPassword = md5($userPass);

    // Insert into login table
    $insertLogin = "INSERT INTO login_tbl(login_email, login_pwd, login_role, login_status)
                    VALUES('$userEmail', '$newPassword', 'user', 1);";

    $loginResult = mysqli_query($db_conn, $insertLogin);

    // Check if the login insert was successful
    if (!$loginResult) {
        echo mysqli_error($db_conn); // Print error if the query failed
        return "Error inserting login details!";
    }

    return "Your registration was successful!";
}

// Login (Authentication) function
function Authentication($userName, $userPass) {
    // Call database connection 
    $db_conn = Connection();

    // Fetch user record from the login table
    $sqlFetchUser = "SELECT * FROM login_tbl WHERE login_email = '$userName';";
    $sqlResult = mysqli_query($db_conn, $sqlFetchUser);

    // Check if the query failed
    if (!$sqlResult) {
        echo mysqli_error($db_conn); // Proper error handling
        return;
    }

    // Convert user password into hashed value using MD5
    $newpass = md5($userPass);

    // Check the number of rows
    $norows = mysqli_num_rows($sqlResult);

    // If record exists
    if ($norows > 0) {
        // Fetch user record
        $rec = mysqli_fetch_assoc($sqlResult);

        // Validate the password
        if ($rec['login_pwd'] == $newpass) {
            // Check if the user account is active
            if ($rec['login_status'] == 1) {
                if ($rec['login_role'] == "admin") {
                    // Redirect to the admin dashboard
                    header('Location: lib/views/dashboards/admin.php');
                    exit(); // Stop further script execution after redirect
                } else {
                    // Redirect to the user dashboard
                    header('Location: lib/views/dashboards/user.php');
                    exit(); // Stop further script execution after redirect
                }
            } else {
                return "Your Account Has Been Deactivated!";
            }
        } else {
            return "Your Password Is Incorrect! Please Try Again.";
        }
    } else {
        return "No Records Found!";
    }
}

// End output buffering and flush output
ob_end_flush();
?>
