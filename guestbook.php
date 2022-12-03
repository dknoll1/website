<?php
    $name = $_POST['name'];
    $message = $_POST['message'];
    $link = $_POST['link'];
    $isApproved = 0;
    $gid = $_GET['gid'];
    if (isset($name)) {
        setcookie('name', $name, time() + 86400, "/");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Daniel Knoll - Guestbook</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/guestbook.css">
        <?php include 'includes/header.php'?>
    </head>
    
    <body>
        <?php
            include 'includes/nav.php';
        ?>
        <form name="guestbook" action="guestbook.php" method="POST">
            <div class="container-fluid htmlForm">
                <div class="row">
                    <div class="col-12">
                        <h1>Guestbook</h1>
                                <label class="fieldForm">Name:</label>
                                <input type="text" name="name" value="" class="fieldInput"><br>
                                <label class="fieldForm">Msg:</label>
                                <input type="text" name="message" value="" class="fieldMsg"><br>
                                <label class="fieldForm">Link:</label>
                                <input type="text" name="link" value="" class="fieldInput"><br>
                                <div class='text-center'>
                                <input type="submit" id="guestbookButton" name="submit" value="Submit"><br></div>
                    </div>

                        <?php
                            require "/home/dknollgr/db.php";
                        
                    if(isset($name)) {
                        $sname = mysqli_real_escape_string($cnxn, $name);
                        $smessage = mysqli_real_escape_string($cnxn, $message);
                        $slink = mysqli_real_escape_string($cnxn, $link);
                        
                        $sql = "INSERT INTO gbook (name, timeposted, message, link) VALUES('$sname', NOW(), '$smessage', '$slink')";
                        if ($cnxn->query($sql) === TRUE) {
                            echo '<div class="col-12 text-center">';
                            echo '(Your message is pending approval)<br><br>';
                            echo '</div>';
                        } else {
                            echo '<div class="col-12 text-center">';
                            echo "Error: ".$sql."<br>".$cnxn->error;
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="col-12 text-center">';
                        echo 'Welcome to the guestbook, etc<br><br>';
                        echo '</div>';
                    }
                            
                            $sql = "SELECT gid FROM gbook WHERE isApproved=0 ORDER BY gid DESC LIMIT 1";
                            $result = @mysqli_query($cnxn, $sql);
                            $pendinggid = mysqli_fetch_assoc($result)['gid'];
                            
                            if(isset($name)) {
                                $to = 'knollmdan@gmail.com';
                                $subject = $name.' Guestbook Post';
                                $emailMessage = '<html><h1>Guestbook Post</h1>
                                    <p><b>Name:</b> '. $name . '</p>
                                    <p><b>Message: </b>'. $message . '</p>
                                    <p><b>Link:</b> '. $link . '</p>
                                    <a href="dknoll.greenriverdev.com/guestbook.php?gid='.$pendinggid.'">Validate</a>
                                    </html>';
                                $headers = "From: Daniel Knoll <knoll.daniel@student.greenriver.edu>\r\n";
                                $headers .= "Reply-To: Daniel Knoll <knoll.daniel@student.greenriver.edu>\r\n";
                                $headers .= "Content-type: text/html\r\n";
                                mail($to, $subject, $emailMessage, $headers);
                            }
                            
                            if(isset($gid)) {
                                $sql = "UPDATE `gbook` SET `isApproved`=1 WHERE gid=$gid";
                                if ($cnxn->query($sql) === TRUE) {
                                    echo '<div class="col-12 text-center">';
                                    echo '(The message is posted at the end of the guestbook)<br><br>';
                                    echo '</div>';
                                } else {
                                    echo '<div class="col-12 text-center">';
                                    echo "Error: ".$sql."<br>".$cnxn->error;
                                    echo '</div>';
                                }
                            }
                            
                            $sql = "SELECT * FROM gbook WHERE isApproved=1 ORDER BY gid DESC";
                            $result = @mysqli_query($cnxn, $sql);
                            
                            while($row = mysqli_fetch_assoc($result))
                            {
                                $name = $row['name'];
                                $message = $row['message'];
                                $link = $row['link'];
                                $timeposted = strtotime($row['timeposted']);
                                
                                echo '<div class="col-12 gbEntry text-center">';
                                echo "<b>$name</b> (";
                                echo date("M j G:i:s Y", $timeposted);
                                echo ") <i><a href='$link' target=0>$name's link</a></i>";
                                
                                echo '<hr>';
                                
                                echo $message;
                                echo '</div>';
                            }
                        ?>
                </div>
            </div>
        </form>
        <?php include 'includes/footer.php' ?>
    </body>
</html>