<?php

//postinstall function
function guaven_sqlcharts_load_defaults()
{
    if (get_option("guaven_sqlcharts_already_installed") === false) {
        update_option("guaven_sqlcharts_already_installed", "1");
        guaven_sqlcharts_install_first_data();
    }
}




function guaven_sqlcharts_my_admin_notice()
{
    global $post;
    if (!empty($post) and $post->post_type == 'gvn_schart'):
        if (!current_user_can('manage_options')) {
            echo '<br><br>
  <div class="updated gf-alert gf-alert-danger">Only administrators can manage this page</div>';
            die();
        }
        echo '<div class="updated gf-alert gf-alert-info">';
        if (empty($_GET["post"]) and strpos($_SERVER["REQUEST_URI"], "post-new") === false):
            $gf_message = 'Use <b>Add new</b> button above to create new sql report. And click on any existing rule names below 
          to manage them. ';
        else:
            $gf_message = '
       1. Give any name to your report.<br>
       2. Choose chart type, type sql query, enter field names, labels and then press to Publish/Update<br>
       3. After update you will see needed shortcode below. You can use that shortcode anywhere in your website: in pages, posts, widgets etc. <br>
        ';
?>
    <?php
        endif;
        _e('<div style="float:left">' . $gf_message . '</div>', 'guaven_sqlcharts');
        echo '<div style="float: right;
    margin-top: 0px;
    padding-top: 0px;"><a class="button button-secondary" href="http://guaven.com/contact">Contact us for custom reports</a></div> </div>';
    endif;
}
add_action('admin_notices', 'guaven_sqlcharts_my_admin_notice');






function guaven_sqlcharts_enqueue_main_style()
{
    wp_enqueue_style('guaven_sqlcharts_main_style', plugins_url('guaven_sqlcharts.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'guaven_sqlcharts_enqueue_main_style');

function guaven_sqlcharts_isJson($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

add_action('init', 'guaven_sqlcharts_register_post');
function guaven_sqlcharts_register_post()
{
    //register_taxonomy('guaven_update_push_tag', 'termin');
    register_post_type('gvn_schart', array(
        'labels' => array(
            'name' => __('My SQL Charts'),
            'singular_name' => __('My SQL chart')
        ),
        
        'public' => true,
        //'taxonomies' => array('guaven_update_push_tag'), 
        'supports' => array(
            'title',
            'postmeta'
        ),
        'register_meta_box_cb' => 'guaven_sqlcharts_metabox_area'
    ));
    
    guaven_sqlcharts_load_defaults();
}

add_action('admin_footer', 'guaven_sqlcharts_admin_front');


function guaven_sqlcharts_admin_front()
{
    global $post;
    if (!empty($post) and $post->post_type == 'gvn_schart') {
?>
<style type="text/css">#normal-sortables{display: none}</style>
  <?php
    }
}

// metabox for editor
function guaven_sqlcharts_metabox_area()
{
    add_meta_box('gvn_schart_metabox', 'Configure your graph chart', 'gvn_schart_metabox', 'gvn_schart', 'advanced', 'default');
}

function gvn_schart_metabox()
{
    global $post;
    wp_nonce_field('meta_box_nonce_action', 'meta_box_nonce_field');
    
    
?>
<h4>Choose your graph</h4>
<p>
<select name="guaven_sqlcharts_graphtype">
<option value="pie" <?php
    $guaven_sqlcharts_graphtype = get_post_meta($post->ID, 'guaven_sqlcharts_graphtype', true);
    echo ($guaven_sqlcharts_graphtype == 'pie' ? 'selected' : '');
?>>Pie Chart</option>

    <option value="3dpie" <?php
    echo ($guaven_sqlcharts_graphtype == '3dpie' ? 'selected' : '');
?>>3D Pie Chart</option>

<option value="column" <?php
    echo ($guaven_sqlcharts_graphtype == 'column' ? 'selected' : '');
?>>Column Chart</option>

<option value="bar" <?php
    echo ($guaven_sqlcharts_graphtype == 'bar' ? 'selected' : '');
?>>Bar Chart</option>

    <option value="area" <?php
    echo ($guaven_sqlcharts_graphtype == 'area' ? 'selected' : '');
?>>Area Chart</option>
    

</select>
</p>
<hr>




<h4>Your SQL code  </h4>
<span>(Use double " " quotes instead of single ' ' ones)</span>
<p>
<textarea name="guaven_sqlcharts_code" id="guaven_sqlcharts_code" style="width:100%" rows="6"><?php
    echo (get_post_meta($post->ID, 'guaven_sqlcharts_code', true) != '' ? get_post_meta($post->ID, 'guaven_sqlcharts_code', true) : '');
?>
</textarea> 
</p>
<p style="text-align: right;">Need help with custom SQL reports? <a href="http://guaven.com/contact">Contact us.</a></p>
<hr>




<h4>Arguments</h4>
<p>
Label for X axis: <br><input type="text" name="guaven_sqlcharts_xarg_l" id="guaven_sqlcharts_xarg_l" value="<?php
    echo get_post_meta($post->ID, 'guaven_sqlcharts_xarg_l', true);
?>"></p>
    <p>
SQL field name of X axis (Write corresponding SQL field name here. f.e. diplay_name): 
<br>
<input type="text" name="guaven_sqlcharts_xarg_s" id="guaven_sqlcharts_xarg_s" value="<?php
    echo get_post_meta($post->ID, 'guaven_sqlcharts_xarg_s', true);
?>">
</p>

<p>
Label for Y axis: <br><input type="text" name="guaven_sqlcharts_yarg_l" id="guaven_sqlcharts_yarg_l" value="<?php
    echo get_post_meta($post->ID, 'guaven_sqlcharts_yarg_l', true);
?>"></p><p>
SQL field name of Y axis (Write corresponding SQL field name here. f.e. post_count): 
<br>
<input type="text" name="guaven_sqlcharts_yarg_s" id="guaven_sqlcharts_yarg_s" value="<?php
    echo get_post_meta($post->ID, 'guaven_sqlcharts_yarg_s', true);
?>">
</p>

<hr>


<h4>Width and height (with px. don't type px itself, just numbers)</h4>
<p>

<input type="number" name="guaven_sqlcharts_chartwidth" id="guaven_sqlcharts_chartwidth" value="<?php
    echo get_post_meta($post->ID, 'guaven_sqlcharts_chartwidth', true);
?>">

<input type="number" name="guaven_sqlcharts_chartheight" id="guaven_sqlcharts_chartheight" value="<?php
    echo get_post_meta($post->ID, 'guaven_sqlcharts_chartheight', true);
?>">
</p>

<hr>




<?php
    if (get_post_meta($post->ID, 'guaven_sqlcharts_graphtype', true) != '') {
?>
<div class="gf-alert-success gf-alert">
<h4>
Text shortcode: [gvn_schart id="<?php
        echo $post->ID;
?>"]
<br>
<br>
PHP shorcode: &lt;?php echo do_shortcode(' [gvn_schart id="<?php
        echo $post->ID;
?>"]'); ?&gt;
</h4>
<p>Note: Shortcodes supports width and height attributes. F.e. [gvn_schart id="1" width="500" height="400"]. If no attributes, default width, height (which you enter in this page above) will be used.
</div>
<h4>Demo</h4>
<?php
        echo do_shortcode(' [gvn_schart id="' . $post->ID . '"]');
        
    }
    
}



function gvn_schart_save_metabox_area($post_id, $post)
{
    
    if (!isset($_POST['meta_box_nonce_field']) or !wp_verify_nonce($_POST['meta_box_nonce_field'], 'meta_box_nonce_action')) {
        return $post->ID;
    }
    $fields = array(
        "guaven_sqlcharts_chartheight",
        "guaven_sqlcharts_chartwidth",
        "guaven_sqlcharts_graphtype",
        "guaven_sqlcharts_code",
        "guaven_sqlcharts_xarg_s",
        "guaven_sqlcharts_xarg_l",
        "guaven_sqlcharts_yarg_s",
        "guaven_sqlcharts_yarg_l"
    );
    foreach ($fields as $key => $value) {
        update_post_meta($post->ID, $value, esc_attr($_POST[$value]));
    }
}
add_action('save_post', 'gvn_schart_save_metabox_area', 1, 2);
// save the custom fields




function gvn_schart_libloads($type, $step)
{
    $stty = array(
        'bar' => array(
            'packages' => "'corechart', 'bar'",
            'charts' => "BarChart"
        ),
        'column' => array(
            'packages' => "'corechart', 'bar'",
            'charts' => "ColumnChart"
        ),
        'area' => array(
            'packages' => "'corechart'",
            'charts' => "AreaChart"
        ),
        'pie' => array(
            'packages' => "'corechart'",
            'charts' => "PieChart"
        ),
        '3dpie' => array(
            'packages' => "'corechart'",
            'charts' => "PieChart"
        )
    );
    
    return $stty[$type][$step];
}






function gvn_schart_shortcode($atts)
{
    
    
    global $wpdb;
    $sql = html_entity_decode(get_post_meta($atts['id'], 'guaven_sqlcharts_code', true));
    
    
    $blacklister   = array(
        "delete",
        "update",
        "insert",
        "drop",
        "truncate"
    ); //add all
    $blacklister_f = 0;
    foreach ($blacklister as $key => $value) {
        if (strpos($sql, $value) !== false)
            $blacklister_f = 1;
    }
    
    if ($blacklister_f == 1)
        return 'You given SQL code contains forbidden commands. Remember that you should only use SELECT queries';
    
    $fvs = $wpdb->get_results($sql);
    
    $wpdb->show_errors();
    ob_start();
    $wpdb->print_error();
    $printerror = ob_get_clean();
    
    if ($printerror != '' and strpos($printerror, "[]") === false)
        return $printerror;
    elseif (empty($fvs))
        return 'Your SQL returnes empty date, please recheck your SQL query above';
    else {
        ob_start();
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  
     <script type="text/javascript">;
       google.charts.load('current', {'packages':[<?php
        $tip_g = get_post_meta($atts['id'], 'guaven_sqlcharts_graphtype', true);
        
        echo gvn_schart_libloads($tip_g, 'packages');
?>]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
    <?php
        $html_temp = '';
        
        $post_g = get_post($atts['id']);
        $xarg_s = get_post_meta($atts['id'], 'guaven_sqlcharts_xarg_s', true);
        $xarg_l = get_post_meta($atts['id'], 'guaven_sqlcharts_xarg_l', true);
        $yarg_s = get_post_meta($atts['id'], 'guaven_sqlcharts_yarg_s', true);
        $yarg_l = get_post_meta($atts['id'], 'guaven_sqlcharts_yarg_l', true);
        
        $graph_width  = get_post_meta($atts['id'], 'guaven_sqlcharts_chartwidth', true);
        $graph_height = get_post_meta($atts['id'], 'guaven_sqlcharts_chartheight', true);
        
        if (!empty($atts["width"])) {
            $graph_width = intval($atts['width']);
        }
        if (!empty($atts["height"])) {
            $graph_height = intval($atts['height']);
        }
        
        foreach ($fvs as $fv) {
            $html_temp .= "['{$fv->$yarg_s}', {$fv->$xarg_s}, '#b87333'],";
            
        }
        
?>
      var data = google.visualization.arrayToDataTable([
         ['<?php
        echo $yarg_l;
?>', '<?php
        echo $xarg_l;
?>', { role: 'style' }],
        <?php
        echo $html_temp;
?>
      ]);

        var options = {
          <?php
        echo $tip_g == '3dpie' ? "is3D: true," : '';
?>
          chart: {
            title: '<?php
        echo $post_g->post_title;
?>',
          }
        };

var chart = new google.visualization.<?php
        echo gvn_schart_libloads(get_post_meta($atts['id'], 'guaven_sqlcharts_graphtype', true), 'charts');
?>(document.getElementById('columnchart_material'));
        chart.draw(data, options);
      }

    </script>

<div id="columnchart_material" style="width:<?php
        echo $graph_width > 0 ? intval($graph_width) : '500';
?>px;
height: <?php
        echo $graph_height > 0 ? intval($graph_height) : '400';
?>px"></div>
<?php
        return ob_get_clean();
    }
}
add_shortcode('gvn_schart', 'gvn_schart_shortcode');




function gvn_schart_wp_tags()
{
    $tags   = get_tags(array(
        "order" => "DESC",
        "orderby" => "count",
        "number" => 10
    ));
    $html   = '';
    $itag   = 0;
    $html60 = '';
    foreach ($tags as $tag) {
        $itag++;
        $html_temp .= "['{$tag->name}', {$tag->count}, '#b87333'],";
    }
?>
      var data = google.visualization.arrayToDataTable([
         ['Element', 'Density', { role: 'style' }],
        <?php
    echo $html_temp;
?>
      ]);

        var options = {
          chart: {
            title: 'Company Performance',
            subtitle: 'Sales, Expenses, and Profit: 2014-2017',
          }
        };
<?php
    return $html_temp;
}


function guaven_sqlcharts_install_first_data()
{
    
    require_once(dirname(__FILE__) . "/initial_data.php");
    gvn_chart_sample_nonxml_data();
}
