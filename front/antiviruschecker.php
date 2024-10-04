<?php

include_once "../inc/antiviruschecker.class.php";

// if(!defined('GLPI_ROOT')){
//     die('No access here');
// }
//instantiate the db connection
global $DB;

//Instantiate the antivirus class
$antivirusChecker = new PluginaAntiviruscheckerAntiviruschecker();#$DB);
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
            foreach($devices as $device):
                if ($currentGroup !== $device['groupName']):
                    $currentGroup= $device['groupName'];
                    if ($currentGroup !== null):
        ?>
                        </table>
                    </div>
                    <?php endif; ?>
                <h2 style = "background-color: #c3d2e0;"><?php echo htmlspecialchars($currentGroup); ?></h2>
                <div class="display-table" style="display:flex; justify-content: center;">
                    <table style = "background-color:#9ed6ff; width: 70vw;">
                        <tr style = "background-color: #758ba0;">
                            <th>Computer Name</th>
                            <th> Status</th>
                            <th>Last Active Date</th>
                        </tr>
        <?php
                endif;
        ?>            
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
         Html::closeForm();
        endif;
        ?> 
    </div>       
</body>
</html>