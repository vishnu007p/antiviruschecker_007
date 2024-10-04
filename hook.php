<?php

function plugin_antiviruschecker_install() {
    global $DB;

    //instanciate migration with version
    $migration = new Migration(100);

    if (!$DB->tableExists('glpi_plugin_antiviruschecker')){
        //Create a table to store SentinelOne data
        $query = "
        CREATE TABLE `glpi_plugin_antiviruschecker` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `computerName` VARCHAR(255) NOT NULL,
            `antivirusStatus` VARCHAR(50) NOT NULL,
            `lastActiveDate` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
      ";

        //execute the table creation
        $DB->queryOrDie($query,"Error creating table glpi_plugin_antiviruschecker: " . $DB->error());
    }

    // Create a table to store the API token
    if (!$DB->tableExists('glpi_plugin_antiviruschecker_config')) {
        $query = "
        CREATE TABLE `glpi_plugin_antiviruschecker_config` (
            `id` INT NOT NULL,
            `token` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
      ";
        $DB->queryOrDie($query, "Error creating table glpi_plugin_antiviruschecker_config: " . $DB->error());
    }

    $migration->executeMigration();

    return true;
}

function plugin_antiviruschecker_uninstall() {
    global $DB;

    if ($DB->tableExists("glpi_plugin_antiviruschecker")){
        $DB->query("DROP TABLE `glpi_plugin_antiviruschecker`");
    }

    return true;
}

Plugin::registerClass('PluginaAntiviruscheckerAntiviruschecker',['addtabon' => 'Config'] );
