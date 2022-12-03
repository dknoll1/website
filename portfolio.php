<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Daniel Knoll - Portfolio</title>
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
            <div class="container-fluid htmlForm">
                <div class="row">
                    <div class="col-12">
                        <h1>Portfolio</h1>
                    </div>
                        <?php
                            require "/home/dknollgr/db.php";
                            
                            $sql = "SELECT * FROM project ORDER BY project_id ASC";
                            $result = @mysqli_query($cnxn, $sql);
                            
                            while($row = mysqli_fetch_assoc($result))
                            {
                                $project_id = $row['project_id'];
                                $name = $row['name'];
                                $lang = $row['lang'];
                                $desc = $row['description'];
                                $other = $row['other'];
                                $link = $row['link'];
                                $prev = $row['preview'];
                                
                                echo '<div class="col-12 col-md-4">';
                                echo "<b>$name</b> - $lang";
                                echo "<br>>$desc<br>";
                                if (isset($other)) {
                                    echo "+$other<br>";
                                }
                                echo "<a href='$link' target=0><img src='images/$prev' alt='$name preview' width='100%'></a><br><br>";
                                echo '</div>';
                            }
                            
                        ?>
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>