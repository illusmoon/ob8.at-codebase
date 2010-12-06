<?php
// $Id: flattr_handler_field_flattr.inc,v 1.1.2.1 2010/05/21 13:46:43 dereine Exp $

class flattr_handler_field_flattr extends views_handler_field {
  function construct() {
    parent::construct();
    $this->additional_fields['nid'] = 'nid';
    $this->additional_fields['uid'] = 'uid';
  }

  function option_definition() {
    $options = parent::option_definition();

    return $options;
  }

  function query() {
    $this->ensure_my_table();
    $this->add_additional_fields();
  }

  function render($values) {
    $nid = $values->{$this->aliases['nid']};
    $uid = $values->{$this->aliases['uid']};
    $node = node_load($nid);
    $account = user_load($uid);

    return theme('flattr_button', $node, $account);
  }
}