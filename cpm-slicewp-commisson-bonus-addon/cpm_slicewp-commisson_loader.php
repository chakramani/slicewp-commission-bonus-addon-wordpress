<?php

if (!defined('ABSPATH')) {
    exit;
}
function cpm_slicewp_commission_addon_enqueue_script()
{
    wp_enqueue_script('cpm_custom_for_slicewp_script_admin', plugin_dir_url(__FILE__) . '/cpm_custom.js');
    wp_enqueue_style('cpm_custom_for_slicewp_css_admin', plugin_dir_url(__FILE__) . '/cpm_custom.css');
}
add_action('slicewp_enqueue_admin_scripts', 'cpm_slicewp_commission_addon_enqueue_script');


add_action("admin_menu", "cpm_slicewp_options_submenu",99);
if (!function_exists('cpm_slicewp_options_submenu')) {
    function cpm_slicewp_options_submenu()
    {
        add_submenu_page(
            'slicewp-page',
            'SliceWP Commission Bonus',
            'SliceWP Addon Setting',
            'manage_options',
            'slice-commission-bonus-addon',
            'cpm_slicewp_bonus_settings_page'
        );
    }
}

function cpm_slicewp_bonus_settings_page()
{
    $msg = '';
    $msg_class = '';
    if (isset($_POST['submit'])) {

        if (!isset($_POST['amount']) && !isset($_POST['bonus'])) {
            $total_amount_save = update_option('_slicewp_total_amount', false);
            $commission_rate_save = update_option('_slicewp_commission_rate', false);
            return;
        }

        $amount[] = $_POST['amount'];
        $rate[] = $_POST['bonus'];
        // $current_date = date("Y-m-d");
        $cron_date[] = $_POST['cron_date'];
        $next_month_date = date("Y-m-d", strtotime("+1 month", strtotime($cron_date[0])));
        array_push($cron_date, $next_month_date);
        $commission_cron_date_save = update_option('_slicewp_commission_cron_dates__', $cron_date);
        // $cron_update_date = !empty(get_option('_slicewp_commission_cron_dates__')[1]) ? get_option('_slicewp_commission_cron_dates__')[1] : $cron_date[0];
        update_option('_slicewp_commission_prev_cron_dates', strtotime($cron_date[0]));
        // var_dump($prev_month_date);
        $total_amount_save = update_option('_slicewp_total_amount', $amount);
        $commission_rate_save = update_option('_slicewp_commission_rate', $rate);
        if ($total_amount_save || $commission_rate_save || $commission_cron_date_save) {
            $msg = "Data are successfully saved.";
            $msg_class = 'message success';
        } else {
            $msg = "No any change.";
            $msg_class = 'message error';
        }
    } ?>
    <div class="wrap">
        <div class="slicewp-addon-main-wrapper">
            <h3>Commission Bonus Rate:</h3>

            <div class="container" id="labels">
                <!-- <div class="label">Earn More Than</div>
            <div class="label">Bonus</div> -->
                <form method="POST">
                    <table class="form-table" role="presentation">
                        <thead>
                            <tr>
                                <th width="10%"><label>S.N</label></th>
                                <th width="40%"><label>Earn More Than</label></th>
                                <th width="40%"><label>Bonus</label></th>
                                <th width="10%"><label>Action</label></th>
                            </tr>
                        </thead>

                        <?php
                        // var_dump(get_option('_slicewp_total_amount'));
                        $total_amount = get_option('_slicewp_total_amount');
                        $total_commission = get_option('_slicewp_commission_rate');
                        ?>
                        <div id="inputs">
                            <tbody class="cpm-slicewp-commission-table" id="cpm-slicewp-commission-table">
                                <?php
                                if (!empty($total_amount)) {
                                    foreach ($total_amount[0] as $key => $amount) : $temp = $key; ?>
                                        <tr class="row cpm_row" id="cpm_row">
                                            <td width="10%">
                                                <div class="count"><?php echo ++$temp; ?></div>
                                            </td>
                                            <td class="input" width="40%">
                                                <input type="number" name="amount[]" id="" class="select-css" value="<?php echo !empty($amount) ? $amount : ''; ?>">
                                            </td>
                                            <td class="input" width="40%">
                                                <input type="number" class="bonus" value="<?php echo !empty($total_commission[0]) ? $total_commission[0][$key] : ''; ?>" name="bonus[]"> <span>USD</span>
                                            </td>
                                            <td id="remove" width="10%">
                                                <button class="remove-btn remove"><span class="dashicons dashicons-trash"></span></button>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                } else {  ?>
                                    <tr class="row cpm_row" id="cpm_row">
                                        <td width="10%">
                                            <div class="count">1</div>
                                        </td>
                                        <td class="input" width="40%">
                                            <input type="number" name="amount[]" id="" class="select-css" value="<?php echo !empty($amount) ? $amount : ''; ?>">
                                        </td>
                                        <td class="input" width="40%">
                                            <input type="number" class="bonus" value="" name="bonus[]"> <span>USD</span>
                                        </td>
                                        <td id="remove" width="10%">
                                            <button class="remove-btn remove"><span class="dashicons dashicons-trash"></span></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </div>
                    </table>
                    <div class="date-picker-wrapper">
                        <h3>Pick Date For Bonus Distribution:</h3>
                        <div class="date-picker-wrapper-inner">
                            <input type="date" name="cron_date" min="<?php echo date("Y-m-d"); ?>" id="cron_date" class="cron_date" value="<?php echo !empty(get_option('_slicewp_commission_cron_dates__')) ? get_option('_slicewp_commission_cron_dates__')[0] : ''; ?>">
                        </div>
                        <p>NOTE: Your next cron date is : <?php if(isset(get_option('_slicewp_commission_cron_dates__')[1]))echo get_option('_slicewp_commission_cron_dates__')[1]; ?></p>
                    </div>
                    <div class="bottom-wrapper">
                        <div class="submit">
                            <input type="submit" class="button button-primary" name="submit" value="Save">
                        </div>
                        <div class="middlebox <?php echo $msg_class; ?>">
                            <p><?php echo $msg; ?></p>
                        </div>
                        <div class="addrow">
                            <button id="addRow" class="button">Add New Row</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
}


function bonus_time_intervals($schedules)
{
    $schedules['weekly'] = array(
		'interval' => 604800,
		'display' => __('Once Weekly')
	);
    $schedules['monthly'] = array(
		'interval' => 2635200,
		'display' => __('Once a month')
	);
    return $schedules;
}
add_filter('cron_schedules', 'bonus_time_intervals');

add_action('slicewp_commission_bonus_update', 'update_commission_bonus');


// add_action('wp_footer', 'update_commission_bonus');
function update_commission_bonus()
{
    global $wpdb;
    $total_amount = get_option('_slicewp_total_amount')[0];
    $total_commission = get_option('_slicewp_commission_rate')[0];
    $slicewp_commission_table_name = $wpdb->prefix . "slicewp_commissions";
    $total = $wpdb->get_results("SELECT affiliate_id, sum(amount) from " . $slicewp_commission_table_name . " WHERE (status='paid' OR status='unpaid') AND type != 'bonus' GROUP BY affiliate_id having sum(amount) > 0", ARRAY_A);
    if (!empty($total)) {
        foreach ($total as $val => $row) {
            // echo($row['affiliate_id']);
            $count = 0;
            foreach ($total_amount as $key => $total_amounts) {

                if ($row['sum(amount)'] >= $total_amounts) {
                    if ($count > 0) {
                        // echo $row['affiliate_id']. '=>' .$total_commission[$key].'count => '.$count.' update '.$total_commission[$key-1] .'with'.$total_commission[$key] .'</br>';
                        $wpdb->get_results("UPDATE " . $slicewp_commission_table_name . " SET `amount` = " . $total_commission[$key] . " WHERE `id` = " . $wpdb->insert_id);
                    } else {
                        // echo $row['affiliate_id']. '=>' .$total_commission[$key].' count => '.$count.'</br>';
                        $wpdb->get_results("INSERT INTO " . $slicewp_commission_table_name . " (`affiliate_id`, `type`,`date_created`, `date_modified`, `status`, `origin`, `amount`, `currency`) VALUES (" . $row['affiliate_id'] . ", 'bonus','" . date("Y-m-d h:i:s") . "','" . date("Y-m-d h:i:s") . "'," . " 'unpaid', 'custom', " . $total_commission[$key] . ", 'USD')");
                    }
                    $count++;
                }
            }
        }
    }
}

if (!wp_next_scheduled('slicewp_commission_bonus_update')) {
    
    wp_schedule_event(get_option('_slicewp_commission_prev_cron_dates'), 'monthly', 'slicewp_commission_bonus_update');
}