<?php ob_start();

// Apply the API that will get the Token
if( !function_exists('_guesty_get_bearer_token') ){
    
    function _guesty_get_bearer_token() {
        
        $bearer_token = get_option('_guesty_calendar_bearer_token');
        
        return !empty($bearer_token) ? $bearer_token : "";
    }
}


function _guesty_calendar_load_template(){

		$page = isset($_GET['page']) ? $_GET['page'] : "";

		include_once(GUESTY_DIR.'/settings.php');
	}

function _guesty_calendar_register_menu(){
	add_menu_page( 
		__( 'Guesty Calendar Widgets', 'textdomain' ),
		'Calendar Widgets',
		'manage_options',
		'calendar_widgets',
		'_guesty_calendar_load_template',
		'dashicons-store',
	); 
}
add_action( 'admin_menu', '_guesty_calendar_register_menu' );


function _guesty_calendar_generate_token($client_id, $client_secret){

    if(empty($client_id) || empty($client_secret)){
        $err = "<div id=\"message\" class=\"error\"><p>Client ID and Client Secret fields are required!</p></div>";
    }else{
             
        $data = array(
            "grant_type"  => "client_credentials",
            "scope" => "booking_engine:api",
            "client_secret" => $client_secret,
            "client_id" => $client_id,
        );
            
        $ch = curl_init();            
            
        curl_setopt($ch, CURLOPT_URL, 'https://booking.guesty.com/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HEADER , 0 );
            
        $headers = array();
        $headers[] = 'accept: application/json';
        $headers[] = 'cache-control : no-cache,no-cache';
        $headers[] = 'content-type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        $curl_close = curl_errno($ch);
        curl_close ($ch);
        
        if ($curl_close) {
            
            $err = "<div id=\"message\" class=\"error\"><p>Error:" . curl_error($ch)."</p></div>";
                
        } else {
            
            $json_result = json_decode($result);
            
            if(empty($json_result->error->code)){
                
                $bearer_token = $json_result->access_token;

                update_option('_guesty_calendar_bearer_token',$bearer_token);
                update_option('_guesty_calendar_bearer_token_expiry_date',date("Y-m-d H:i:s", time() + $json_result->expires_in));
                update_option('_guesty_calendar_client_id',$client_id);
                update_option('_guesty_calendar_client_secret',$client_secret);
                
            }else{
                
                $err = "<div id=\"message\" class=\"error\"><p>Sorry but you are unable to get the bearer token this time. Error: " . $json_result->error->code."</p></div>";
            }               
        }
    }
}

function _guesty_calendar_renew_token() {
    $client_id = get_option('_guesty_calendar_client_id');
    $client_secret = get_option('_guesty_calendar_client_secret');

    _guesty_calendar_generate_token($client_id, $client_secret);
}
add_action( 'renew_token', '_guesty_calendar_renew_token' );


// get availability of a specific property

function _guesty_calendar_get_availability($listingID){

    $token = _guesty_get_bearer_token();

    // only check availability within current day to end of the year
    $today = date("Y-m-d");
    $yearEnd = date('Y-m-d', strtotime('+3 months', strtotime('12/31')));

    // initialize array for available dates
    $availableDates = array();


    $data = array(
        'from' => $today,
        'to' => $yearEnd,
    );

    $ch = curl_init();    

    curl_setopt($ch, CURLOPT_URL,"https://booking.guesty.com/api/listings/".$listingID."/calendar?".http_build_query($data));

    $headers = array();
    $headers[] = 'Accept: application/json; charset=utf-8';
    $headers[] = 'cache-control : no-cache,no-cache';
    $headers[] = 'Content-Type : application/x-www-form-urlencoded';
    $headers[] = "Authorization: Bearer ".$token;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER , 0 );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


    $result = curl_exec($ch);
    $curl_close = curl_errno($ch);
    curl_close ($ch);
    
    if ($curl_close) {
        $err = "<div id=\"message\" class=\"error\"><p>Error:" . curl_error($ch)."</p></div>";
    } else {
        
        $json_result = json_decode($result, true);

        foreach($json_result as $date) {
            if($date['status'] == 'available') {
                array_push($availableDates, $date['date']);
            }
        }

    }

    return $availableDates;
}


