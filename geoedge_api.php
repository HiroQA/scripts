<?php

require __DIR__ . '/../geoedge_api/bootstrap.php';

$conf = parse_ini_file( __DIR__ . '/GeoEdge.ini' );
$geoEdgeClient = new GeoEdgeClient( $conf['authorization_key']);


function get_all_data($client)
{
$get_project_list = $client->ListProjects();
$yesterday = date('Y-m-d 00:00:00',strtotime("yesterday"));


// get project_id from SearchAds()
  foreach ( $get_project_list->projects as $project_meta_data ) 
  {
  	$ads_meta_data = $client->SearchAds( $yesterday, null, null, $project_meta_data->id );

// get ad_id from all_ad_info of each ad occurrence on each project + set available values for file appending.
  	foreach ( $ads_meta_data->ads as $ad_id ) 
  	{
  		$all_ad_info = $client->GetAd( $ad_id->id, '7');
        $ad_traffic = $all_ad_info->capture_requests;
        
	// set project_name var - from object to array and withdraw value        
        $ads_project_data = get_object_vars($all_ad_info->ad->project_name);
        $project_array = array_values($ads_project_data);
        $project_name = "PROJECT_NAME - " . $project_array[0] . " , ";

	// set additional vars for value retrieval #1
        $network_name = "NETWORK_NAME - " . $all_ad_info->ad->network->network_name. " , ";		
		$network_id = "NETWORK_ID - " . $all_ad_info->ad->network->network_id . " , ";
		$ad_id = "AD_ID - " . $all_ad_info->ad->ad_id . " , ";
		$ad_type = "AD_TYPE - " . $all_ad_info->ad->type. " , ";
 		$screen_shot_url = "SCREEN_SHOT_URL - " . $all_ad_info->ad->scan_screenshot_url . " , ";
        $creative_url = "CREATIVE_URL - " . $all_ad_info->ad->creative_url . " , ";
        $creative_screen_shot_url = "CREATIVE_SCREENSHOT_URL - " . $all_ad_info->ad->creative_screenshot_url . " , ";  
		$landing_page_url = "LANDING_PAGE_URL - " . $all_ad_info->ad->landing_page_url . " , ";
		$landing_page_screen_shot_url = "LANDING_PAGE_SCREEN_SHOT_URL - " . $all_ad_info->ad->landing_page_screenshot_url . " , ";
		$more_info_link = "GUI - " . $all_ad_info->ad->more_info_link . " \n ";

	// set additional vars for value retrieval #2
      foreach ($ad_traffic as $key) 
      {
		 $start = "START - " . $key->Start . " , ";
		 $end = "END - " . $key->End . " , ";
  		 $response_status = "RESPONSE_STATUS - " . $key->ResponseStatus . " , ";
  		 $url = "URL - " . $key->Url . " , ";
  		 $content_type = "CONTENT_TYPE - " . $key->ContentType . " , ";
		//"METHOD - " . $key->Method . " , ";
		//"SOURCE_F - " . $key->SourceF . " , ";
		//"POST_F - " . $key->PostF . " , ";

//create file and append the values.
		 $yesterday = date('Y-m-d',strtotime("yesterday"));
		 $new_file = fopen($yesterday, "a");

		 fwrite($new_file, $network_name);
		 fwrite($new_file, $network_id);
         fwrite($new_file, $project_name);
		 fwrite($new_file, $ad_id);
		 fwrite($new_file, $ad_type);
		 fwrite($new_file, $start);
		 fwrite($new_file, $end);
		 fwrite($new_file, $response_status);
		 fwrite($new_file, $url);
		 fwrite($new_file, $content_type);
		 fwrite($new_file, $screen_shot_url);
	     //fwrite($new_file, $creative_url);
		 fwrite($new_file, $creative_screen_shot_url);
		 fwrite($new_file, $landing_page_url);
		 fwrite($new_file, $landing_page_screen_shot_url);
		 fwrite($new_file, $more_info_link);
		 fclose($new_file);
   	    }
        }
    }
}

get_all_data($geoEdgeClient);


// $response = $client->SearchAds( '2015-07-09 00:00:00', 'f0e7a35c85dcb6f950899afaef8b7387' );
// $response = $client->GetAd( '1teEgrFreMQnoMGyFB_Xfw', 7 );

// // // Projects
// $response = $client->ListProjects();
// $response = $client->GetProject( $project_id );
// $response = $client->earchProjects();
// $response = $client->ScanStatus( $scan_id );

// // // Alerts
// $response = $client->ListAlerts();
// $response = $client->TriggerTypes();
// $response = $client->GetAlertHistory( $alerts_history_id );
// $response = $client->SearchAlertsHistory();

// // // ADS
// $response = $client->GetAd( $ad_id );
//$response = $client->SearchAds( '2015-12-21 00:00:00' );
// $response = $client->SearchLPs( $min_datetime );

// // // Misc
// $response = $client->ListLocations();
// $response = $client->ListEmulators();
// $response = $client->ListNetworks();
// $response = $client->ListUsage();

// // //  API VARIABLE EXAMPLES:
//$project_id - 1a755a2adf6301380b5ed35fb303767c
//$scan_id - 1a755a2adf6301380b5ed35fb303767c
//$alerts_history_id - AuQh8Tnyk0QrDgEq0SzyBw
//$ad_id - 7iPbOgeHNBkeHSHaUy7RfA
//$min_datetime or $max_datetime - 2015-07-09 00:00:00
