<?php
if (isset($_POST['submit'])) {
    include_once('./controller/connect.php');
    $dbs = new database();
    $db = $dbs->connection();

    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $bdate = $_POST['bdate'];
    $gender = $_POST['gender'];
    $mnumber = $_POST['mnumber'];
    $email = $_POST['email'];
    $password = $_POST['password']; // No hashing
    $role = $_POST['role'];

    $imageName = '';
    if (!empty($_FILES['pfimg']['name'])) {
        $imageName = rand(222, 333333) . $_FILES['pfimg']['name'];
        move_uploaded_file($_FILES['pfimg']['tmp_name'], "image/" . $imageName);
    }

    $roleid = 1; // Assuming roleid for admin is 1
    date_default_timezone_set("Africa/Nairobi");
    $datetime = date("Y-m-d H:i:s");

    $sql = "INSERT INTO employee 
                (EmpId, FirstName, MiddleName, LastName, Birthdate, Gender, Mobile, Email, Password, CreatedBy, CreatedDate, JoinDate, StatusId, RoleId, ImageName) 
            VALUES 
                (NULL, '$fname', '$mname', '$lname', '$bdate', '$gender', '$mnumber', '$email', '$password', '$roleid', '$datetime', '$datetime', 1, '$roleid', '$imageName')";

    $query = mysqli_query($db, $sql);

    if ($query) {
        echo '<script>alert("Registration successful!");</script>';
    } else {
        echo '<script>alert("Registration failed! Please try again.");</script>';
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Registration Page - HRM</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Pooled Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="css/jquery-ui.css"> 
<script src="js/jquery-2.1.4.min.js"></script>
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />

<style>
    html {
        min-height: 100%;
        width: 100%;
    }
    body, .main-wthree {
        width: 100%;
        min-height: 100vh;
    }
    .main-wthree {
        padding-bottom: 2em;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .footer {
        width: 100%;
        position: fixed;
        bottom: 0;
        left: 0;
    }

    .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            width: 100%;
        }
        .form-row .form-group {
            flex: 1;
        }
        .form-left,
        .form-right {
            flex: 1;
        }
        .form-left {
            max-width: 45%;
        }
        .form-right {
            max-width: 45%;
        }

    .register-form {
        background-color: #010101;
        background-image: linear-gradient(160deg, #010101 0%, #4e6865 100%);
        padding: 20px;
        border-radius: 10px;
        color: white;
        width: 100%;
        max-width: 500px;
    }
    .register-form h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .register-form .form-group {
        margin-bottom: 15px;
    }
    .register-form input[type="submit"]:hover {
        background: #3e5250;
    }
</style>
</head> 
<body>
    <div class="main-wthree">
        <div class="container">
            <h1 class="text-center text-white">Human Resource Management System</h1>
            <div class="register-form">
                <h2>Register</h2>
                <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" id="fname" name="fname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="mname">Middle Name</label>
                            <input type="text" id="mname" name="mname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" id="lname" name="lname" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bdate">Birthdate</label>
                            <input type="date" id="bdate" name="bdate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="mnumber">Mobile</label>
                            <input type="text" id="mnumber" name="mnumber" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="1">Admin</option>
                            <!-- Add other roles if needed -->
                        </select>
                    </div>
                    </div>
                    <div class="form-group">
                        <label for="pfimg">Profile Image</label>
                        <input type="file" id="pfimg" name="pfimg" class="form-control" accept="image/*">
                    </div>
                    <input type="submit" name="submit" value="Register" class="btn btn-primary btn-block">
                </form>
                <div class="footer">
                    <p>Human Resource Management System. All Rights Reserved &copy; <?= date("Y") ?></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateForm() {
            const nameFields = ['fname', 'mname', 'lname'];
            for (const field of nameFields) {
                if (/\d/.test(document.getElementById(field).value)) {
                    alert('Name fields cannot contain numbers');
                    return false;
                }
            }

            const password = document.getElementById('password').value;
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!regex.test(password)) {
                alert('Password must be at least 8 characters long and contain an uppercase letter, a lowercase letter, a number, and a special character');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
