<?php
// $Id: flattr.views.inc,v 1.1.2.1 2010/05/21 13:46:43 dereine Exp $

/**
* Implements hook_views_data_alter().
*/
function flattr_views_data_alter(&$data) {
  $data['node']['flattr'] = array(
    'title' => t('Flattr button'),
    'help' => t('Displat the flattr button of the node'),
    'field' => array(
      'handler' => 'flattr_handler_field_flattr',
    ),
  );
}

/**
 * Implements hook_views_handlers().
 */
function flattr_views_handlers() {
  return array(
    'handlers' => array(
      'flattr_handler_field_flattr' => array(
        'parent' => 'views_handler_field',
      ),
    ),
  );
}
