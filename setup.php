<?php


if(!defined('GLPI_ROOT')){
    die('No access');
}

// myplugin/setup.php
// function plugin_myplugin_setup() {
//     Plugin::registerClass('PluginaAntiviruscheckerAntiviruschecker', [
//         'add' => true,  // Allow adding instances
//         'update' => true,  // Allow updating instances
//         'delete' => true,  // Allow deleting instances
//         'export' => true,  // Allow exporting instances
//         'import' => true,  // Allow importing instances
//         'massiveactions' => true,  // Allow massive actions
//         'rulecollections_types' => true  // Allow rule collections if applicable
//     ]);
//     Plugin::registerClass('PluginaAntiviruscheckerAntiviruschecker',[
//         'addtabon' => 'Config',
//         'rightcom' => 'true',
//         'right'=> [
//             'plugin_antiviruschecker_access'=> [
//                 'title'=> 'Antivirus Status Checker',
//                 'name' => 'Antivirus Status Checker',
//                 'comment'=> 'Allows access to the Antivirus Checker',
//             ],
//         ],
//         ] );
//}

function plugin_init_antiviruschecker(){
    
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['antiviruschecker'] = true;
    Plugin::registerClass('PluginaAntiviruscheckerAntiviruschecker');
    // Config page
   if (Session::haveRight('config', UPDATE)) {
    $PLUGIN_HOOKS['config_page']['antiviruschecker'] = 'front/antiviruschecker.php';
    }

    //fetchdatapage
    // if (Session::haveRight('config', UPDATE)) {
    //     $PLUGIN_HOOKS['config_page']['antiviruschecker'] = 'front/antiviruschecker.php';
    //     }
    

    $PLUGIN_HOOKS['menu_entry']['antiviruschecker'] = [
        'title' => 'Check Antivirus Status',
        'page' => 'antiviruschecker/front/antiviruschecker.php',
        'right' => 'plugin_antiviruschecker', // Set permissions
        'section' => 'Assets', // Ensure it appears under the assets section
    ];

    // $PLUGIN_HOOKS['menu']['antiviruschecker'] = [
    //     'title' => 'My Plugin',
    //     'page' => 'antiviruschecker/front/antiviruschecker.php',
    //     'right' => 'plugin_antiviruschecker' // Set permissions
    // ];
    //$PLUGIN_HOOKS['menu_toadd']['antiviruschecker'] = ['tools' => 'front/myplugin.php'];
   // $PLUGIN_HOOKS['menu_toadd']['antiviruschecker'] = ['tools' => 'plugin_antiviruschecker_check'];

}

$PLUGIN_HOOKS['init']['antiviruschecker'] = 'plugin_init_antiviruschecker';
function plugin_version_antiviruschecker(){
    return [
        'name'           => 'Antivirus Checker',
        'version'        => '1.1.14',
        'author'         => 'Vishnu | MBI',
        'license'        => 'GPLv2+',
        'homepage'       => '#',
        'minGlpiVersion' => '9.4'  // Minimum compatible GLPI version
    ];
}

function plugin_antiviruschecker_check_prerequisites() {
    // Check if GLPI version is compatible
    // if (GLPI_VERSION >= 4) {
          return true;
    // } else {
    //     return false;
    // }
}
function plugin_antiviruschecker_check_config() {
    return true; // Return true if configuration is correct, otherwise false
}

function plugin_version_antiviruschecker_getRights() {
    return [
        'plugin_antiviruschecker' =>[
            'name' => 'View Antivirus Status',
            'type'=> 'read',
            'section'=> 'plugin',
        ]
    ];
}