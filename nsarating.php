<?php

require_once 'nsarating.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function nsarating_civicrm_config(&$config) {
  _nsarating_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function nsarating_civicrm_xmlMenu(&$files) {
  _nsarating_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function nsarating_civicrm_install() {
  _nsarating_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function nsarating_civicrm_postInstall() {
  _nsarating_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function nsarating_civicrm_uninstall() {
  _nsarating_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function nsarating_civicrm_enable() {
  _nsarating_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function nsarating_civicrm_disable() {
  _nsarating_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function nsarating_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _nsarating_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function nsarating_civicrm_managed(&$entities) {
  _nsarating_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function nsarating_civicrm_caseTypes(&$caseTypes) {
  _nsarating_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function nsarating_civicrm_angularModules(&$angularModules) {
  _nsarating_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function nsarating_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _nsarating_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function nsarating_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function nsarating_civicrm_navigationMenu(&$menu) {
  _nsarating_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'org.example.nsarating')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _nsarating_civix_navigationMenu($menu);
} // */

function nsarating_civicrm_buildForm($formName, &$form) {
  // dpm(array($formName, $form));
  if ($formName == 'CRM_Activity_Form_Activity') {
    Civi::resources()
      ->addScriptFile('org.example.nsarating', 'js/activity.js')
      ->addStyleFile('org.example.nsarating', 'css/activity.css');

  }
}

function nsarating_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Activity_Form_Activity') {
    nsarating_lookup_security_rating($form->_activityId);
  }
}

function nsarating_lookup_security_rating($activityId) {
  $locField = 'custom_7';
  $ratingField = 'custom_8';

  $activity = civicrm_api3('Activity', 'getsingle', array(
    'id' => $activityId,
    'return' => array($locField, $ratingField, 'activity_type_id'),
    'sequential' => 1,
  ));

  list ($lat, $long) = explode(',', $activity[$locField]);

  $response = file_get_contents(sprintf(
    'http://think.hm/secrate.php?activity_type=%s&long=%s&lat=%s',
    urlencode($activity['activity_type_id']),
    urlencode($long),
    urlencode($lat)
  ));

  $responseData = json_decode($response, TRUE);

  print_r(array(
    'activity' => $activity,
    '$responseData' => $responseData,
  ));

  civicrm_api3('Activity', 'create', array(
    'id' => $activityId,
    $ratingField => $responseData['color'],
  ));
}

function nsarating_civicrm_post($op, $objectName, $objectId, &$objectRef) {
//  dpm(array(__FUNCTION__, $op, $objectName, $objectId, $objectRef));
}

function nsarating_civicrm_custom($op, $groupID, $entityID, &$params) {
//  dpm(array(__FUNCTION__, $op, $groupID, $entityID, &$params));
}
