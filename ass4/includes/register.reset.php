<?php
/* 
 * Copyright (C) 2013 peter
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
include_once 'db_connect.php';
include_once 'psl-config.php';
$error_msg = "";
$check = 0;

if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
    echo "Hello";
    // Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    //$tmp = $username;
    //$ptmp = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
    
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
    /*else { //not working, god knows why?
        //for password strength
        $dict = array("Password1","12345678");
        $max = 0.0;
        for ($i = 0; $i <= 1; $i++){
            similar_text($dict[$i], $ptmp, $perct);
            if ($perct >= $max) {
                $max = $perct;
            }
        }
        if ($max >= 70.0) {
            $error_msg .= '<p class="error">Your Password is Quite Common. Use another one!</p>';
        }
        elseif ($max >= 40.0 && $max < 70.0) {
            $error_msg .= '<p class="error">Your Password is Common. Use some special characters or numbers!</p>';
        }
    }*/
    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //

    //for same username
    /*$prep_stmt = "SELECT id FROM members WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            //echo $username
            $error_msg .= '<p class="error">A user with username  already exists.</p>';
            $check = 1;
        }
    } else {
        $error_msg .= '<p class="error">Database error</p>';
    }
    
    $prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg .= '<p class="error">A user with this email address already exists.</p>';
        }
    } else {
        $error_msg .= '<p class="error">Database error</p>';
    }*/
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.
    if (empty($error_msg)) {
        // Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        // Create salted password 
        $password = hash('sha512', $password . $random_salt);
        $secret=hash('sha512',$username.$password);
        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->prepare("UPDATE members SET password = ?,salt=?,secret=? WHERE username = ?")) {
            $insert_stmt->bind_param('ssss', $password,$random_salt,$secret, $username);
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                header('Location: ./error.php?err=Registration failure: INSERT');
                exit();
            }
        }
        header('Location: ./register_success.php');
        exit();
    }
}
?>