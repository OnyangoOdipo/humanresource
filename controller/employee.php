<?php
    include_once('connect.php');
    $dbs = new database();
    $db = $dbs->connection();
    session_start();

    if(isset($_POST['submit'])) {
        $data = $_POST;
        $editid = 0;

        if(isset($_GET['empedit']) && $_GET['empedit'] > 0) { 
            $editid = $_GET['empedit'];
        }

        $empid = $data['empid'];
        $img = $_FILES['pfimg']['name'];
        $gender = $data['gender'];
        $fname = $data['fname'];
        $mname = $data['mname'];
        $lname = $data['lname'];
        $bdate = $data['bdate'];
        $mnumber = $data['mnumber'];
        $email = $data['email'];
        $joindate = $data['joindate'];
        $leavedate = $data['leavedate'];
        $status = $data['status'];
        $role = $data['role'];
        $password = $data['password'];
        $tierid = $data['TierId'];
        $imagefilename = $data['imagefilename'];
        $ImageComplete = false;

        // Check if email is already in use
        if ($editid == 0) {
            $sql = mysqli_query($db, "SELECT * FROM employee WHERE Email='$email'");
        } else {
            $sql = mysqli_query($db, "SELECT * FROM employee WHERE Email='$email' AND EmpId != $editid");
        }

        if(mysqli_num_rows($sql) > 0) {
            header("location:../employeeadd.php?msg=Email address already exists!"); exit;
        } else {
            // Image validation and handling
            if (!empty($_FILES['pfimg']['name'])) {
                $name = $_FILES['pfimg']['name'];
                $temp = $_FILES['pfimg']['tmp_name'];
                $size = $_FILES['pfimg']['size'];
                $type = $_FILES['pfimg']['type'];

                if($type != "image/jpg" && $type != "image/png" && $type != "image/jpeg" && $type != "image/gif") {
                    header("location:../employeeadd.php?msg=Invalid image type!"); exit;
                } elseif($size > 1000000) { // File size should be less than 1MB
                    header("location:../employeeadd.php?msg=File size should be up to 1MB!"); exit;
                } else {
                    $ImageComplete = true;
                }               
            } else {
                $in = $_POST["imagefilename"];
                
                if(file_exists("../image/".$in)) {
                    $ImageComplete = true;
                } else {
                    header("location:../employeeadd.php?msg=Please select a profile image!"); exit;    
                }
            }   
        }

        if($ImageComplete) {
            $roleid = $_SESSION['User']['RoleId'];
            date_default_timezone_set("Africa/Nairobi");
            $datetime = date("Y-m-d H:i:s");

            if($editid == 0) { // Inserting a new employee
                if(!empty($_FILES['pfimg']['name'])) {
                    $name = rand(222, 333333) . $name;
                    move_uploaded_file($temp, "../image/" . $name);
                } else {
                    $name = $_POST["imagefilename"];
                }

                $sql = "INSERT INTO employee 
                            (EmpId, EmployeeId, FirstName, MiddleName, LastName, Birthdate, Gender, Mobile, Email, Password, CreatedBy, CreatedDate, JoinDate, LeaveDate, StatusId, RoleId, ImageName, TierId) 
                        VALUES 
                            (NULL, '$empid', '$fname', '$mname', '$lname', '$bdate', '$gender', '$mnumber', '$email', '$password', '$roleid', '$datetime', '$joindate', '$leavedate', '$status', '$role', '$name', '$tierid')";

                mysqli_query($db, $sql);
                header("location:../detailview.php?id=Success"); exit;
            } else { // Updating an existing employee
                if(!empty($_FILES['pfimg']['name'])) {
                    $name = rand(222, 333333) . $name;
                    move_uploaded_file($temp, "../image/" . $name);
                } else {
                    $name = $_POST["imagefilename"];
                }

                $sql = "UPDATE employee 
                        SET EmployeeId='$empid', 
                            FirstName='$fname', 
                            MiddleName='$mname', 
                            LastName='$lname', 
                            Birthdate='$bdate', 
                            Gender='$gender', 
                            Mobile='$mnumber', 
                            Email='$email', 
                            Password='$password', 
                            TierId='$tierid', 
                            ModifiedBy='$roleid', 
                            ModifiedDate='$datetime', 
                            JoinDate='$joindate', 
                            LeaveDate='$leavedate', 
                            StatusId='$status', 
                            RoleId='$role', 
                            ImageName='$name' 
                        WHERE EmpId='$editid'";

                mysqli_query($db, $sql);
                header("location:../detailview.php?employeeid=$editid"); exit;
            }
        }
    }
?>
