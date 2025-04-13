<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Sessions</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
</style>
</head>
<body>
<?php
// Start session
session_start();

if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" || $_SESSION["usertype"] != 'p') {
        header("Location: ../login.php");
        exit();
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("Location: ../login.php");
    exit();
}

// Import database connection
include("../connection.php");

// Fetch patient details securely
$sqlmain = "SELECT * FROM patient WHERE pemail = ?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$userrow = $stmt->get_result();

if ($userrow->num_rows == 0) {
    die("Error: Patient not found.");
}

$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

// ✅ Handle appointment booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["booknow"], $_POST["apponum"], $_POST["scheduleid"], $_POST["date"])) {
        $apponum = $_POST["apponum"];
        $scheduleid = $_POST["scheduleid"];
        $date = $_POST["date"];

        // ✅ Secure insertion using prepared statements
        $sql2 = "INSERT INTO appointment (pid, apponum, scheduleid, appodate) VALUES (?, ?, ?, ?)";
        $stmt_insert = $database->prepare($sql2);
        $stmt_insert->bind_param("iiis", $userid, $apponum, $scheduleid, $date);

        if ($stmt_insert->execute()) {
            // ✅ Redirect to `booking-complete.php` after successful booking
            header("Location: booking-complete.php?id=" . $apponum);
            exit();
        } else {
            die("Error: " . $stmt_insert->error);
        }
    } else {
        die("Error: Missing booking details.");
    }
}
    


    //echo $userid;
    //echo $username;
    


    date_default_timezone_set('Asia/Kolkata');

    $today = date('Y-m-d');


 //echo $userid;
 ?>
 <div class="container">
     <div class="menu">
     <table class="menu-container" border="0">
             <tr>
                 <td style="padding:10px" colspan="2">
                     <table border="0" class="profile-container">
                         <tr>
                             <td width="30%" style="padding-left:20px" >
                                 <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                             </td>
                             <td style="padding:0px;margin:0px;">
                                 <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                 <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
                             </td>
                         </tr>
                         <tr>
                             <td colspan="2">
                                 <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                             </td>
                         </tr>
                 </table>
                 </td>
             </tr>
             <tr class="menu-row" >
                    <td class="menu-btn menu-icon-home " >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                <td width="13%">
    <a href="schedule.php">
        <button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
            <span class="tn-in-text">Back</span>
        </button>
    </a>
</td>
<td>
    <form action="schedule.php" method="post" class="header-search">
        <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name, Email, or Date (YYYY-MM-DD)" list="doctors">
        <datalist id="doctors">
            <?php
            // Securely fetch unique doctor names
            $stmt1 = $database->prepare("SELECT DISTINCT docname FROM doctor");
            $stmt1->execute();
            $result1 = $stmt1->get_result();

            while ($row1 = $result1->fetch_assoc()) {
                echo "<option value='{$row1["docname"]}'>";
            }

            // Securely fetch unique schedule titles
            $stmt2 = $database->prepare("SELECT DISTINCT title FROM schedule");
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            while ($row2 = $result2->fetch_assoc()) {
                echo "<option value='{$row2["title"]}'>";
            }
            ?>
        </datalist>

        <input type="submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
    </form>
</td>

                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 

                                
                                echo $today;

                                

                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
                
                
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                        <!-- <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49);font-weight:400;">Scheduled Sessions / Booking / <b>Review Booking</b></p> -->
                        
                    </td>
                    
                </tr>
                
                
                
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                            
                        <tbody>
                        
                            <?php
                            
                            if ($_GET) {
                                if (isset($_GET["id"])) {
                                    $id = $_GET["id"];
                            
                                    // ✅ Ensure docid is explicitly retrieved
                                    $sqlmain = "SELECT doctor.docid, doctor.docname, doctor.docemail, schedule.* 
                                                FROM schedule 
                                                INNER JOIN doctor ON schedule.docid = doctor.docid 
                                                WHERE schedule.scheduleid = ? 
                                                ORDER BY schedule.scheduledate DESC";
                            
                                    $stmt = $database->prepare($sqlmain);
                                    $stmt->bind_param("i", $id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                            
                                    $row = $result->fetch_assoc();
                                    $scheduleid = $row["scheduleid"];
                                    $title = $row["title"];
                                    $docid = $row["docid"];  // ✅ Ensure correct doctor is captured
                                    $docname = $row["docname"];
                                    $docemail = $row["docemail"];
                                    $scheduledate = $row["scheduledate"];
                                    $scheduletime = $row["scheduletime"];
                            
                                    $sql2 = "SELECT * FROM appointment WHERE scheduleid = ?";
                                    $stmt2 = $database->prepare($sql2);
                                    $stmt2->bind_param("i", $id);
                                    $stmt2->execute();
                                    $result12 = $stmt2->get_result();
                                    $apponum = ($result12->num_rows) + 1;
                                    
                                    echo '
                                        <form action="booking-complete.php" method="post">
                                            <input type="hidden" name="scheduleid" value="'.$scheduleid.'" >
                                            <input type="hidden" name="apponum" value="'.$apponum.'" >
                                            <input type="hidden" name="date" value="'.$today.'" >

                                        
                                    
                                    ';
                                     

                                    echo '
                                    <form action="booking-complete.php" method="post">
                                        <input type="hidden" name="scheduleid" value="' . htmlspecialchars($scheduleid) . '">
                                        <input type="hidden" name="apponum" value="' . htmlspecialchars($apponum) . '">
                                        <input type="hidden" name="date" value="' . htmlspecialchars($today) . '">
                                
                                        <tr>
                                            <td style="width: 50%;" rowspan="2">
                                                <div class="dashboard-items search-items">
                                                    <div style="width:100%">
                                                        <div class="h1-search" style="font-size:25px;">Session Details</div>
                                                        <br><br>
                                                        <div class="h3-search" style="font-size:18px;line-height:30px">
                                                            Doctor Name: &nbsp;&nbsp;<b>' . htmlspecialchars($docname) . '</b><br>
                                                            Doctor Email: &nbsp;&nbsp;<b>' . htmlspecialchars($docemail) . '</b>
                                                        </div>
                                                        <br>
                                                        <div class="h3-search" style="font-size:18px;">
                                                            Session Title: ' . htmlspecialchars($title) . '<br>
                                                            Scheduled Date: ' . htmlspecialchars($scheduledate) . '<br>
                                                            Start Time: ' . htmlspecialchars($scheduletime) . '<br>
                                                            Channeling Fee: <b>KSHS. 2,000.00</b>
                                                        </div>
                                                        <br>
                                                    </div>
                                                </div>
                                            </td>
                                
                                            <td style="width: 25%;">
                                                <div class="dashboard-items search-items">
                                                    <div style="width:100%; padding-top: 15px; padding-bottom: 15px;">
                                                        <div class="h1-search" style="font-size:20px; line-height: 35px; text-align:center;">
                                                            Your Appointment Number
                                                        </div>
                                                        <center>
                                                            <div class="dashboard-icons" style="width:90%; font-size:70px; font-weight:800; text-align:center; color:var(--btnnictext); background-color: var(--btnice)">
                                                                ' . htmlspecialchars($apponum) . '
                                                            </div>
                                                        </center>
                                                        <br><br>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="submit" class="login-btn btn-primary btn btn-book" style="margin-left:10px; padding-left: 25px; padding-right: 25px; padding-top: 10px; padding-bottom: 10px; width:95%; text-align:center;" value="Book now" name="booknow">
                                            </td>
                                        </tr>
                                    </form>
                                            </td>
                                        </tr>
                                        '; 
                                        




                                }



                            }
                            
                            ?>
 
                            </tbody>

                        </table>
                        </div>
                        </center>
                   </td> 
                </tr>
                       
                        
                        
            </table>
        </div>
    </div>
    
    
   
    </div>

</body>
</html>