<?php



/*
 /*this demonstrates setting a custom field through the API 
 */
function contact_get_example(){
$params = array( 
  'first_name' => 'abc1',
  'contact_type' => 'Individual',
  'last_name' => 'xyz1',
  'version' => 3,
  'custom_2' => 'custom string',
);

  require_once 'api/api.php';
  $result = civicrm_api( 'contact','get',$params );

  return $result;
}

/*
 * Function returns array of result expected from previous function
 */
function contact_get_expectedresult(){

  $expectedResult = array( 
  'is_error' => 0,
  'version' => 3,
  'count' => 1,
  'id' => 1,
  'values' => array( 
      '1' => array( 
          'contact_id' => '1',
          'contact_is_deleted' => 0,
          'civicrm_value_testgetwithcustom_2_id' => '1',
          'custom_2' => 'custom string',
        ),
    ),
);

  return $expectedResult  ;
}




/*
* This example has been generated from the API test suite. The test that created it is called
* contact_get 
* You can see the outcome of the API tests at 
* http://tests.dev.civicrm.org/trunk/results-api_v3
* and review the wiki at
* http://wiki.civicrm.org/confluence/display/CRMDOC40/CiviCRM+Public+APIs
* Read more about testing here
* http://wiki.civicrm.org/confluence/display/CRM/Testing
*/