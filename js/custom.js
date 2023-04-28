jQuery(document).ready(function(){

    jQuery('.gc_booking_widget').each(function(){

        var dates = jQuery(this).data('availability');
        var availableDates = dates.split(',');


        jQuery(this).find('#gc_arrival_date').datepicker({
            defaultDate: new Date(),
            minDate: new Date(),
            yearRange: new Date().getFullYear() + ':' + new Date().getFullYear(),
            beforeShowDay: function(date) {
                ymd = date.getFullYear() + "-" + (("0" + (date.getMonth() + 1)).slice(-2)) + "-" + (("0" + date.getDate()).slice(-2));
                if (jQuery.inArray(ymd, availableDates) !== -1) {
                    return [true, "","Available"];
                } else {
                    return [false,"","unAvailable"];
                }
            },
            onSelect: function(dateStr) {   
            	jQuery('#gc_arrival_date').datepicker( "hide" );
                jQuery('#gc_departure_date').val(dateStr);
                jQuery('#gc_departure_date').focus();
                
                jQuery('#gc_departure_date').datepicker( "show" );
                jQuery('#gc_departure_date').datepicker( "option", "minDate", finalDate );
            }
        });

        jQuery(this).find('#gc_departure_date').datepicker({
            defaultDate: new Date(),
            minDate: new Date(),
            yearRange: new Date().getFullYear() + ':' + new Date().getFullYear(),
            beforeShowDay: function(date) {
                ymd = date.getFullYear() + "-" + (("0" + (date.getMonth() + 1)).slice(-2)) + "-" + (("0" + date.getDate()).slice(-2));
                if (jQuery.inArray(ymd, availableDates) !== -1) {
                    return [true, "","Available"];
                } else {
                    return [false,"","unAvailable"];
                }
            },
            onSelect: function(dateStr) {   
            	jQuery('#gc_departure_date').datepicker( "hide" );
                jQuery('#gc_departure_date').val(dateStr);
                // jQuery('#gc_departure_date').focus();
                
                // jQuery('#gc_departure_date').datepicker( "show" );
                jQuery('#gc_departure_date').datepicker( "option", "minDate", finalDate );
            }
        });

        

        jQuery(this).find('.gc_book_now button').click(function(){

            var error = '';
            
            var listingID = jQuery(this).closest('.gc_booking_widget').data('listingid');
            var bookingUrl = jQuery(this).closest('.gc_booking_widget').data('bookingurl');

            var checkIn = jQuery(this).closest('.gc_booking_widget').find('#gc_arrival_date').val();
            var checkOut = jQuery(this).closest('.gc_booking_widget').find('#gc_departure_date').val();
            var guests = jQuery(this).closest('.gc_booking_widget').find('#gc_guests').val();

            if(checkIn == '') {
                error += '<span class="error-notice">Check in date is required!</span>';
            }

            if(checkOut == '') {
                error += '<span class="error-notice">Check out date is required!</span>';
            }
            
            if(error == '') {
                window.location.href = bookingUrl+'/properties/'+listingID+"?checkIn="+checkIn+"&checkOut="+checkOut+"&minOccupancy="+guests;
            } else {
                jQuery(this).closest('.gc_booking_widget').find('.gc_form_errors').html(error);
            }
        });
    });
    
}); 