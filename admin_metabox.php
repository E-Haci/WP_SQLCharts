<?php
global $post;
wp_nonce_field('meta_box_nonce_action', 'meta_box_nonce_field');


?>
<h4>Choose your graph</h4>

<p>
<select name="guaven_sqlcharts_graphtype">

<option value="pie_l" <?php
$guaven_sqlcharts_graphtype = get_post_meta($post->ID, 'guaven_sqlcharts_graphtype', true);
echo ($guaven_sqlcharts_graphtype == 'pie_l' ? 'selected' : '');
?>>Pie Chart</option>

    <option value="line_l" <?php
echo ($guaven_sqlcharts_graphtype == 'line_l' ? 'selected' : '');
?>>Line Chart</option>

<option value="donut_l" <?php
echo ($guaven_sqlcharts_graphtype == 'donut_l' ? 'selected' : '');
?>>Donut Chart</option>

<option value="bar_l" <?php
echo ($guaven_sqlcharts_graphtype == 'bar_l' ? 'selected' : '');
?>>Vertical Bar Chart</option>

<option value="horizontalbar_l" <?php
echo ($guaven_sqlcharts_graphtype == 'horizontalbar_l' ? 'selected' : '');
?>>Horizontal Bar Chart</option>

    <option value="area_l" <?php
echo ($guaven_sqlcharts_graphtype == 'area_l' ? 'selected' : '');
?>>Area Chart</option>

<option value="pie" <?php
$guaven_sqlcharts_graphtype = get_post_meta($post->ID, 'guaven_sqlcharts_graphtype', true);
echo ($guaven_sqlcharts_graphtype == 'pie' ? 'selected' : '');
?>>Google Chart - Pie</option>

    <option value="3dpie" <?php
echo ($guaven_sqlcharts_graphtype == '3dpie' ? 'selected' : '');
?>>Google Chart - 3D Pie</option>

<option value="column" <?php
echo ($guaven_sqlcharts_graphtype == 'column' ? 'selected' : '');
?>>Google Chart - Column</option>

<option value="bar" <?php
echo ($guaven_sqlcharts_graphtype == 'bar' ? 'selected' : '');
?>>Google Chart - Bar</option>

    <option value="area" <?php
echo ($guaven_sqlcharts_graphtype == 'area' ? 'selected' : '');
?>>Google Chart - Area</option>
    

</select><br>
<small>
- Google Charts options are only for single queries. Use only non-Google Chart options above to build multiple queries.
</small>
</p>
<hr>




<h4>Your SQL code  </h4>
<span>(Use double " " quotes instead of single ' ' ones)</span>
<p>
<textarea name="guaven_sqlcharts_code" id="guaven_sqlcharts_code" style="width:100%" rows="6"><?php
echo (get_post_meta($post->ID, 'guaven_sqlcharts_code', true) != '' ? get_post_meta($post->ID, 'guaven_sqlcharts_code', true) : '');
?>
</textarea> 
<br><small>Separate sql queries with ";" if you would like to use multiple queries to build comparison graphs.<a href="https://www.dropbox.com/s/l9xggkeliqdswo9/mysqlchart_example.png?dl=0"  target="_blank">See example</a></small>
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
?>">
<br><small>Use ";" separated label names if you want to use multiple fields. <a href="https://www.dropbox.com/s/l9xggkeliqdswo9/mysqlchart_example.png?dl=0" target="_blank">See example</a></small>
</p>
<p>
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
    if (strpos(get_post_meta($post->ID, 'guaven_sqlcharts_graphtype', true), "_l") !== false)
        $postfix = '_2';
    else
        $postfix = '';
?>
<div class="gf-alert-success gf-alert">
<h4>
Text shortcode: [gvn_schart<?php
    echo $postfix;
?> id="<?php
    echo $post->ID;
?>"]
<br>
<br>
PHP shorcode: &lt;?php echo do_shortcode(' [gvn_schart<?php
    echo $postfix;
?> id="<?php
    echo $post->ID;
?>"]'); ?&gt;
</h4>
<p>Note: Shortcodes supports width and height attributes. F.e. [gvn_schart<?php
    echo $postfix;
?> id="1" width="500" height="400"]. If no attributes, default width, height (which you enter in this page above) will be used.
</div>
<h4>Demo</h4>
<?php
    echo do_shortcode(' [gvn_schart' . $postfix . ' id="' . $post->ID . '"]');
    
}