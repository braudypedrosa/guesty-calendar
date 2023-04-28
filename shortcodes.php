<?php

// Display calendar shortcode
function display_calendar_func( $atts ) {

	// Shortcode attributes
	$atts = shortcode_atts(
		array(
			'listingID' => '63d81106cd433a0036912495',
            'buttonText' => 'Book Now',
            'buttonColor' => '#E19159',
            'textColor' => 'white'
		),$atts
	);

    $availableDates = _guesty_calendar_get_availability($atts['listingID']);
    $booking_url = get_option('_guesty_calendar_booking_url');


    // display a date picker

    $output = '<div class="gc_booking_widget gc_booking_widget_'.$atts['listingID'].'" data-bookingUrl="'.$booking_url.'" data-listingId="'.$atts['listingID'].'" data-availability="'.implode(",", $availableDates).'">'.
        '<div class="gc_form_errors"></div><div class="gc_arrival_date"><input type="text" name="arrival" id="gc_arrival_date" readonly="readonly" placeholder="Check in"/></div>'.
        '<div class="gc_departure_date"><input type="text" name="departure" id="gc_departure_date" readonly="readonly" placeholder="Check out"/></div>'.
        '<div class="gc_guests"><input type="number" name="guests" id="gc_guests" placeholder="Guests"> '.
        '</div><div class="gc_book_now"><button style="background-color:'.$atts['buttonColor'].'; color:'.$atts['textColor'].'!important;">'.$atts['buttonText'].'</button></div></div>';

    return $output;

}
add_shortcode( 'display_calendar', 'display_calendar_func' );