<?php


function plugin_init_antiviruschecker(){
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['antiviruschecker'] = true;
    $PLUGIN_HOOKS['config_page']['antiviruschecker'] = 'front/antiviruschecker.php';

    // $PLUGIN_HOOKS['menu']['antiviruschecker'] = [
    //     'title' => 'My Plugin',
    //     'page' => 'antiviruschecker/front/antiviruschecker.php',
    //     'right' => 'plugin_antiviruschecker' // Set permissions
    // ];
    //$PLUGIN_HOOKS['menu_toadd']['antiviruschecker'] = ['config' => 'front/myplugin.php'];
}

function plugin_version_antiviruschecker(){
    return [
        'name'           => 'Antivirus Checker',
        'version'        => '1.0.5',
        'author'         => 'Vishnu gp MBI5',
        'license'        => 'GPLv2+',
        'homepage'       => '#',
        'minGlpiVersion' => '9.4'  // Minimum compatible GLPI version
    ];
}

function plugin_antiviruschecker_check_prerequisites() {
    // Check if GLPI version is compatible
    // if (GLPI_VERSION >= 9.4) {
         return true;
    // } else {
    //     return false;
    // }
}
function plugin_antiviruschecker_check_config() {
    return true; // Return true if configuration is correct, otherwise false
}