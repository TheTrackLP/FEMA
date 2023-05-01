<?php include 'db_connect.php' ?>
<?php 

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM borrowers where id=".$_GET['id']);
    foreach($qry->fetch_array() as $k => $val){
        $$k = $val;
    }
}

?>

<!DOCTYPE html>
<!--=== Coding by CodingLab | www.codinglabweb.com === -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEMA | Registration</title>
    <!----======== CSS ======== -->
    <link rel="stylesheet" href="assets/css/reg_style.css">
     
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!--<title>Responsive Regisration Form </title>--> 

</head>
<body>
    <div class="container">
        <header>Registration</header>
        <hr>
        <form action="registration.php" method="post">
            <div class="form first">
                <div class="details personal">
                    <span class="title">Personal Details</span>

                    <div class="fields">
                        <div class="input-field">
                            <label>Last Name</label>
                            <input name="lastname" class="form-control" placeholder="Enter Last Name" required>
                        </div>

                        <div class="input-field">
                            <label>First Name</label>
                            <input name="firstname" class="form-control" placeholder="Enter First Name" required>
                        </div>

                        <div class="input-field">
                            <label>Middle Name</label>
                            <input name="middlename" class="form-control" placeholder="Enter Middle Name" required>
                        </div>

                        <div class="input-field">
                            <label>Date of Birth</label>
                        <input type="date" class="form-control" name="date_birth" placeholder="Enter Date of Birth" required>
                        </div>

                        <div class="input-field">
                            <label>Mobile Number</label>
                            <input type="text" class="form-control" name="contact_no" placeholder="Enter Mobile Number" required>
                        </div>

                        <div class="input-field">
                            <label>Gender</label>
                            <select required>
                                <option disabled selected>Select gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div class="input-field">
                        </div>
                    </div>
                </div>

                <div class="details ID">
                    <span class="title">Identity Details</span>

                    <div class="fields">
                        <div class="input-field">
                            <label>ID Number</label>
                            <input type="text" class="form-control" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : '' ?>" placeholder="Enter Employee ID" required>
                        </div>
                        <div class="input-field">
                            <label>Office/Department</label>
                            <?php
                            $department = $conn->query("SELECT * from departments");
                            ?>
                            <select class="form-control" name="department">
                                <option value="" disabled selected>Show All Office Department</option>
                                <?php while($row = $department->fetch_assoc()): ?>
                                    <option value="<?php echo $row['department'] ?>"><?php echo $row['department'] ?></option>
                                <?php endwhile; ?>
                           </select>
                        </div>
                        <div class="input-field">
                            <label>Year of Service</label>
                            <select id="" class="form-control" name="year_service" required>
                                <option disabled selected>Select Year of Service</option>
                                <option>1-4 years</option>
                                <option>5-9 years</option>
                                <option>10 and above years</option>
                            </select>
                        </div>
                    </div>

                    <button class="nextBtn">
                        <span class="btnText">Next</span>
                        <i class="uil uil-navigator"></i>
                    </button>
                </div> 
            </div>

            <div class="form second">
                <div class="details address">
                    <span class="title">Address Details</span>

                    <div class="fields">
                        <div class="input-field">
                            <label>Barangay: </label>
                            <input type="text" name="barangay" placeholder="Permanent or Temporary" required>
                        </div>

                        <div class="input-field">
                            <label>Street:</label>
                            <input type="text" placeholder="Enter Street" name="street" required>
                        </div>

                        <div class="input-field">
                            <label>City</label>
                            <input type="text" placeholder="Enter your City" name="city" required>
                        </div>

                        <div class="input-field">
                            <label>Province</label>
                            <input type="text" placeholder="Enter your Province" name="province" required>
                        </div>

                        <div class="input-field">
                            <label>Zip</label>
                            <input type="number" placeholder="Enter ZIP number" name="zip" required>
                        </div>
                      <div class="input-field">
                            <select id="" class="form-control" name="stat" required>
                                <option selected>New</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <input type="text" name="date_created" value="<?php echo date('Y-m-d'), isset($_POST['date_created']) ? $_POST['date_created'] : ''?>" readonly>
                    </div>
                </div>
                <span class="title">Create new Account</span>

                    <div class="fields">
                        <div class="input-field">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                        </div>

                        <div class="input-field">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Enter Password" name="street" required>
                        </div>

                        <div class="input-field">
                            <label>Password</label>
                            <input type="password" name="confirm_password" placeholder="Confirm Password" name="city" required>
                        </div>
                    </div>
                    <div class="buttons">
                        <div class="backBtn">
                            <i class="uil uil-navigator"></i>
                            <span class="btnText">Back</span>
                        </div>
                        
                        <button class="sumbit"  name="submit" id="submit">
                            <span class="btnText">Submit</span>
                            <i class="uil uil-navigator"></i>
                        </button>
                </div>
            </div>
        </form>
    </div>
    <script src="assets/js/reg_script.js"></script>
</body>
</html>
<?php 
    if(isset($_POST['submit'])){
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $contact_no = $_POST['contact_no'];
        $year_service = $_POST['year_service'];
        $barangay = $_POST['barangay'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $zip = isset($_POST['zip']) ? $_POST['zip'] : '';
        $employee_id = $_POST['employee_id'];
        $date_birth = isset($_POST['date_birth']) ? $_POST['date_birth'] : '';
        $date_created = isset($_POST['date_created']) ? $_POST['date_created'] : '';
        $department = $_POST['department'];
        $stat = $_POST['stat'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_pass = $_POST['confirm_password'];

        if($password === $confirm_pass){
            $qry = mysqli_query($conn, "SELECT email,contact_no FROM borrowers WHERE email = '$email' OR contact_no = '$contact_no'");
            $result = mysqli_fetch_array($qry);
            if($result>0){
                echo "<script>alert('This email or Contact Number already associated with another account!.');</script>";
                echo "<script>location.href = 'registration.php'</script>";
            }
            else{
                $query=mysqli_query($conn, "INSERT INTO `borrowers` (`firstname`, `middlename`, `lastname`, `contact_no`, `year_service`, `barangay`, `street`, `city`, `province`, `zip`, `email`, `password`, `employee_id`, `date_birth`, `date_created`, `department`, `stat`) VALUES ('$firstname', '$middlename', '$lastname', '$contact_no', '$year_service', '$barangay', '$street', '$city', '$province', '$zip', '$email', '".md5($password)."', '$employee_id', '$date_birth', '$date_created', '$department', '$stat')");
            if ($query) {
                echo "<script>alert('You have successfully registered.')</script>";
                echo "<script>location.href = 'login_member.php'</script>";
            }else{
                echo "<script>alert('Something Went Wrong. Please try again.');</script>";
                }
            }
        }else{
            echo "<script>alert('Your Password Does not Match, Please Try again!');</script>";
            return;

        }
    }
?>