<?php
/**
 * Copyright (C) 2013 peredur.net 
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
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Registration Form</title>
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
        <link rel="stylesheet" href="styles/main.css" />
        <link rel="stylesheet" href="password_meter.css" />
    </head>
    <body>
        <!-- Registration form to be output if the POST variables are not
        set or if the registration script caused an error. -->
        <h1>Register with us</h1>
        <?php
        if (!empty($error_msg)) {
            //echo $tmp;
            echo $error_msg;
            //echo $check;
            if($check==1)
            {
                //echo '<li><span style="color:green;">'.$suggestions[$i]."</span></li>"; Password - oninput="myFunction(this.form.password)"
                
                $str1 = getSuggestions($tmp);
                $str2 = getSuggestions($tmp);
                while($str2 == $str1)
                    $str2 = getSuggestions($tmp);
                $str3 = getSuggestions($tmp);
                while($str3 == $str1 || $str3 == $str2)
                    $str3 = getSuggestions($tmp);

                echo '<span style="color:blue;">'."You could instead use "."</span>";
                echo '<span style="color:green;">'.$str1."</span>"."\r";  //print usernames
                echo '<span style="color:blue;">'."\r or "."</span>";
                echo '<span style="color:green;">'.$str2."</span>"."\r";
                echo '<span style="color:blue;">'."\r or "."</span>";
                echo '<span style="color:green;">'.$str3."</span>"."\r";
                
            }
        }
        ?>
        <ul>
            <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
            <li>Emails must have a valid email format</li>
            <li>Passwords must be at least 6 characters long</li>
            <li>Passwords must contain
                <ul>
                    <li>At least one upper case letter (A..Z)</li>
                    <li>At least one lower case letter (a..z)</li>
                    <li>At least one number (0..9)</li>
                </ul>
            </li>
            <li>Your password and confirmation must match exactly</li>
        </ul>
        <form method="post" name="registration_form" action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>">
            <div id="register-fields">
                Username: <input type='text' name='username' id='username' /><br></br>
                Email: <input type="text" name="email" id="email" /><br></br>
                Password: <input type="password"
                                name="password" 
                                id="password"
                                oninput="myFunction(this.form.password)"/><br>
                </br>
                <meter max="4" id="password-strength-meter">
                    <div id="meter-gauge">
                            <div id="meter-red" style="width: 25%; float:left"></div>
                            <div id="meter-yellow" style="width: 25%; float:left"></div>
                            <div id="meter-orange" style="width: 25%; float:left"></div>
                            <div id="meter-green" style="width: 25%; float:left"></div>
                            <div style="clear: both;"></div>
                    </div>
                </meter>
                <p id="warning-text"><p>
                <p id="password-strength-text"></p>
                
                Confirm password: <input type="password" 
                                        name="confirmpwd" 
                                        id="confirmpwd" /><br></br>
            </div>
            <input type="button" 
                   value="Register" 
                   onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpwd);" /> 
        </form>
        <p>Return to the <a href="index.php">login page</a>.</p>
    </body>
</html>

