<?php ob_start();

include_once(GUESTY_DIR.'functions.php');

$token_expiry = get_option('_guesty_calendar_bearer_token_expiry_date',true);
$today = date("Y-m-d H:i:s");

$client_id_option = get_option('_guesty_calendar_client_id');
$client_secret_option = get_option('_guesty_calendar_client_secret');
$booking_url_option = get_option('_guesty_calendar_booking_url');
$bearer_token = get_option('_guesty_calendar_bearer_token');

$err = "";

if(isset($_POST['save_settings'])){
    
    $client_id = isset($_POST['client_id']) ? $_POST['client_id'] : $client_id_option;
    $client_secret = isset($_POST['client_secret']) ? $_POST['client_secret'] : $client_secret_option;
    $booking_url = isset($_POST['booking_url']) ? $_POST['booking_url'] : $booking_url_option;

    if(($today > $token_expiry) || $bearer_token == '') {
         
        _guesty_calendar_generate_token($client_id, $client_secret, $booking_url);

        // // schedule auto renewal CRON job
        if (! wp_next_scheduled( 'renew_token')) {
            wp_schedule_event( time(), 'daily', 'renew_token' );
        }
        
    } 

    update_option('_guesty_calendar_booking_url', $booking_url);

    wp_redirect(admin_url("admin.php?page=calendar_widgets&status=settings_saved"));
    exit();

}

?>


<style>
    .guesty-form-input {
        margin-bottom:20px;
    }
    .guesty-form-input label,
    .guesty-form-input input {
        display:block;
        max-width:500px;
        width:100%;
    }
    .guesty-form-input input[type=submit]{
        max-width:200px;
    }
    .guesty-refresh_listings {
        padding: 3px 25px !important;
        font-size: 19px!important;
    }
    .guesty-settings-content-1 {
        padding-bottom:25px;
        border-bottom:1px solid #d0d0d0;
    }
    .guesty-settings-content-2,
    .guesty-settings-content-3{
        padding-top:25px;
        padding-bottom:25px;
        border-bottom:1px solid #d0d0d0;
    }
</style>
<div class="wrap">
    <h2>Guesty Settings</h2>
    <?php if(isset($_GET['status']) && $_GET['status'] == "settings_saved"): ?>
        <div class="notice notice-success is-dismissible">
            <p>You have successfully connected to Guesty API</p>
        </div>
    <?php elseif(isset($_GET['status']) && $_GET['status'] == "interval_saved"): ?>
        <div class="notice notice-success is-dismissible">
            <p>You have successfully saved the time interval!</p>
        </div>
    <?php endif;?>

    <?php echo !empty($err) ? $err : ""; ?>

    <div class="guesty-settings-content guesty-settings-content-1">
        <h3>API Settings</h3>
        <form method="post">
            <div class="guesty-form-input">
                <label>API Client ID</label>
                <input type="text" required name="client_id"  value="<?php echo isset($_POST['client_id'])?$_POST['client_id']:$client_id_option;;?>"/>
            </div>
            <div class="guesty-form-input">
                <label>API Client Secret</label>
                <input type="password" required name="client_secret" value="<?php echo isset($_POST['client_secret'])?$_POST['client_secret']:$client_secret_option;;?>"/>
            </div>
            <div class="guesty-form-input">
                <label>Guesty Booking URL</label>
                <input type="text" required name="booking_url" value="<?php echo isset($_POST['booking_url'])?$_POST['booking_url']:$booking_url_option;?>"/>
                <span style="line-height: 2em;">Ex: https://sitename.guestybookings.com</span>
            </div>
            <div class="guesty-form-input">
                <input type="text" disabled name="bearer_token" value=<?php echo $bearer_token; ?>>
            </div>
            <div class="guesty-form-input">
                <input type="submit" name="save_settings" class="button-primary" value="Save Settings"/>    
            </div>
        </form>
    </div>
</div>

<div class="shortcodes-wrap">
    <h3>Available Shortcodes:</h3>
    <p>[display_calendar]</p>

    <p><strong>Example usage:</strong></p>
    <p>[display_calendar listingID="xxxxxxxxxxxxxx" buttonText="Book Now"]</p>

    <h4>Parameters:</h4>
    <ul>
        <li>listingID (required)</li>
        <li>buttonText (optional - default: "Book Now")</li>
        <li>buttonColor (optional - default: "#E19159")</li>
        <li>textColor (optional - default: white)</li>
    </ul>
</div>