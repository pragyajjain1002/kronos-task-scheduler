/*
 * Copyright (C) 2013 peredur.net
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
///////p
function  maximum(a,b)
{
    if (a>b)
        return a
    else
        return b
}

function string_match(first, second, percent)
{
    if (first === null ||
        second === null ||
        typeof first === 'undefined' ||
        typeof second === 'undefined') {
        return 0
    }

    first += ''
    second += ''

    var pos1 = 0
    var pos2 = 0
    var max = 0
    var Lf = first.length
    var Ls = second.length
    var i
    var j
    var k
    var sum
    for (i=0; i<Lf; i++)
    {
        for(j=0; j<Ls; j++)
        {
            for(k=0; (i+k < Lf) && (j+k < Ls) && (first.charAt(i+k) === second.charAt(j+k)); k++)
            {}
            if (k > max)
            {
                max=k
                pos1=i
                pos2=j
            }
        }
    }
    sum=max
    if(sum)
    {
        if(pos1 && pos2)
        {
            sum+= string_match(first.substr(0,pos1), second.substr(0,pos2))
        }
        if ((pos1 + max < Lf) && (pos2+max < Ls))
        {
            sum+=string_match(first.substr(pos1+max, Lf-pos1-max), second.substr(pos2+max, Ls-pos2-max))
        }
    }
    if(!percent)
    {
        return sum
    }
    return (sum*200)/(Lf+Ls)
}

///////
function formhash(form, password) {
    // Create a new element input, this will be our hashed password field.
    var p = document.createElement("input");

    // Add the new element to our form.
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    // Make sure the plaintext password doesn't get sent.
    password.value = "";

    // Finally submit the form.
    form.submit();
}

//reset passwors hashing
function regformhash_recover(form, uid, email, password, conf) {
    // Check each field has a value
    var pass = password.value
    if ( password.value == '' || conf.value == '') {
        alert('You must provide all the requested details. Please try again');
        return false;
    }
    var user = uid;    //for checking the user value in password

    // Check the username

    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        alert('Passwords must be at least 6 characters long.  Please try again');
        form.password.focus();
        return false;
    }
    // At least one number, one lowercase and one uppercase letter
    // At least six characters
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
    if (!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
        return false;
    }
    if(pass == user)
    {
        alert('Your Username cannot be your Password!');
        form.password.focus();
        return false;
    }
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }

    // Create a new element input, this will be our hashed password field.
    var p = document.createElement("input");

    // Add the new element to our form.
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    var email_inp = document.createElement("input");

    // Add the new element to our form.
    form.appendChild(email_inp);
    email_inp.name = "email";
    email_inp.type = "hidden";
    email_inp.value=email;

    var user_inp = document.createElement("input");

    // Add the new element to our form.
    form.appendChild(user_inp);
    user_inp.name = "username";
    user_inp.type = "hidden";
    user_inp.value=user;
    // Make sure the plaintext password doesn't get sent.
    password.value = "";
    conf.value = "";
    console.log("Submitted successfully");
    // Finally submit the form.
    form.submit();
    return true;
}


function regformhash(form, uid, email, password, conf) {
    // Check each field has a value
    var pass = password.value
    if (uid.value == '' || email.value == '' || password.value == '' || conf.value == '') {
        alert('You must provide all the requested details. Please try again');
        return false;
    }
    var user = uid.value    //for checking the user value in password

    // Check the username
    re = /^\w+$/;
    if(!re.test(form.username.value)) {
        alert("Username must contain only letters, numbers and underscores. Please try again");
        form.username.focus();
        return false;
    }

    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        alert('Passwords must be at least 6 characters long.  Please try again');
        form.password.focus();
        return false;
    }
    // At least one number, one lowercase and one uppercase letter
    // At least six characters
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
    if (!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
        return false;
    }
    if(pass == user)
    {
        alert('Your Username cannot be your Password!');
        form.password.focus();
        return false;
    }
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }

    // Create a new element input, this will be our hashed password field.
    var p = document.createElement("input");

    // Add the new element to our form.
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    // Make sure the plaintext password doesn't get sent.
    password.value = "";
    conf.value = "";

    // Finally submit the form.
    form.submit();
    return true;
}

var dict=["123456","123456789","qwerty","12345678","111111","1234567890","1234567","password","123123","987654321","qwertyuiop","mynoob","123321","666666","18atcskd2w","7777777","1q2w3e4r","654321","555555","3rjs1la7qe", "google","1q2w3e4r5t","123qwe","zxcvbnm","1q2w3e"]
var len = dict.length
var strength = {
    0: "Worst",
    1: "Bad",
    2: "Weak",
    3: "Good",
    4: "Strong"
  }
function myFunction(password)
{
    //common passwords
    var tmp = 0
    var meter_val = 0;
    var pass = password.value;

    for(var i = 0;i<len;i++)
    {
        tmp = maximum(string_match(dict[i],pass,true),tmp)
        tmp = maximum(string_match(pass,dict[i],true),tmp)
    }
    var meter = document.getElementById('password-strength-meter');
    var text = document.getElementById('password-strength-text');
    var warning = document.getElementById('warning-text');

    var meter1 = document.getElementById('meter-red');
    var meter2 = document.getElementById('meter-yellow');
    var meter3 = document.getElementById('meter-orange');
    var meter4 = document.getElementById('meter-green');
    // Update the password strength meter

    if(pass.length < 6)
    {
        meter.value = 0;
        text.innerHTML = "";
        warning.innerHTML = "Passwords must be at least 6 characters long.";
        meter1.style.background = "whitesmoke";
        meter2.style.background = "whitesmoke";
        meter3.style.background = "whitesmoke";
        meter4.style.background = "whitesmoke";
    }
    else
    {
        tmp=tmp+40-pass.length*3;
        if(tmp >= 80)
        {
            meter_val = 1;
            meter1.style.backgroundColor = "red";
            meter2.style.background = "whitesmoke";
            meter3.style.background = "whitesmoke";
            meter4.style.background = "whitesmoke";
        }
        else if(tmp >= 60 && tmp < 80)
        {
            meter_val = 2;
            meter1.style.background = "yellow";
            meter2.style.background = "yellow";
            meter3.style.background = "whitesmoke";
            meter4.style.background = "whitesmoke";
        }
        else if(tmp >= 40 && tmp < 60)
        {
            meter_val = 3;
            meter1.style.background = "orange";
            meter2.style.background = "orange";
            meter3.style.background = "orange";
            meter4.style.background = "whitesmoke";
        }
        else
        {
            meter_val = 4;
            meter1.style.background = "green";
            meter2.style.background = "green";
            meter3.style.background = "green";
            meter4.style.background = "green";
        }
        meter.value = meter_val;
        text.innerHTML = "STRENGTH: " + "<strong>" + strength[meter.value];
        warning.innerHTML = "";
    }

}
