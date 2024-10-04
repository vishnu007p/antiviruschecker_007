<?php

include_once("../inc/antiviruschecker.class.php");

//instantiate the db connection
global $DB;

//Instantiate the antivirus class
$antivirusChecker = new PluginaAntiviruscheckerAntiviruschecker($DB);
$device = [];
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['fetch_data'])) {
    $devices = $antivirusChecker->fetch_display_data();
    if (empty($devices) ) {
        echo "<P>No devices.</P>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="box-wrapper">
        <h1>Antivirus Status</h1>
        <form method="post" >
            <button class="button-38" name="fetch_data" type="submit"> Fetch Data</button>
        </form>
        <?php
        if(!empty($devices)):
            $currentGroup = null;
        ?>
            <div class="display-table">
                <table style = "background-color:#bee4ff; margin:40px; width: 70vw;">
                    <tr style = "background-color: #758ba0;">
                        <th>Computer Name</th>
                        <th> Status</th>
                        <th>Last Active Date</th>
                    </tr>
                    <?php foreach($devices as $device): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($device['computerName']);?></td>
                            <?php $statusColor = ($device['antivirusStatus'] === 'Inactive') ? 'red' : 'green'; ?>
                            <td style="color: <?php echo $statusColor; ?>;"> <?php echo htmlspecialchars($device['antivirusStatus']); ?> </td>
                            <td><?php echo htmlspecialchars($device['lastActiveDate']); ?> </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php
        endif;
        ?> 
    </div>       
</body>
</html>