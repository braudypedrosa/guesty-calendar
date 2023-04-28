<?php

// Display calendar shortcode
function display_calendar_func( $atts ) {

	// Shortcode attributes
	$data = shortcode_atts(
		array(
			'listingid' => '',
            'buttontext' => 'Book Now',
            'buttoncolor' => '#E19159',
            'textcolor' => 'white'
		),
        $atts, 
        'display_calendar'
	);


    // display a date picker

    if($data['listingid'] == '') {
        $output = '<div class="error-notice">Listing ID is required!</div>';
    } else {
    
    $availableDates = _guesty_calendar_get_availability($data['listingid']);
    $booking_url = get_option('_guesty_calendar_booking_url');
    
    $output = '<div class="gc_booking_widget gc_booking_widget_'.$data['listingid'].'" data-bookingUrl="'.$booking_url.'" data-listingid="'.$data['listingid'].'" data-availability="'.implode(",", $availableDates).'">'.
        '<div class="gc_form_errors"></div><div class="gc_arrival_date"><input type="text" name="arrival" id="gc_arrival_date" readonly="readonly" placeholder="Check in"/></div>'.
        '<div class="gc_departure_date"><input type="text" name="departure" id="gc_departure_date" readonly="readonly" placeholder="Check out"/></div>'.
        '<div class="gc_guests"><input type="number" name="guests" id="gc_guests" placeholder="Guests"> '.
        '</div><div class="gc_book_now"><button style="background-color:'.$data['buttoncolor'].'; color:'.$data['textcolor'].'!important;">'.$data['buttontext'].'</button></div></div>';
    }

    return $output;

}
add_shortcode( 'display_calendar', 'display_calendar_func' );