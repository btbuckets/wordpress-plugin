<?php

/*
Plugin Name: BTBuckets
Plugin URI: http://btbuckets.com/wordpress
Description: Automatically install BTBuckets tag and instantly brings you to behavioral targeting world.
Author: Arnaldo Pereira
Version: 1.0
Requires at least: 2.7
Author URI: http://btbuckets.com/
License: GPL2
*/

/*  Copyright 2010  Arnaldo M Pereira   (email : ap@predicta.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
 * Admin User Interface
 */
if (!class_exists('BT_Admin')) {

    require_once('yst_plugin_tools.php');

    class BT_Admin extends Yoast_Plugin_Admin {

        var $hook       = 'btbuckets';
        var $filename   = 'btbuckets/btbuckets.php';
        var $longname   = 'BTBuckets configuration';
        var $shortname  = 'BTBuckets';
        var $optionname = 'BTBucketsTag';
        var $homepage   = 'http://btbuckets.com/wordpress';
        var $btb_tag_service = 'https://btbuckets.com/services/generate_tag/uk/';

        function BT_Admin() {
            add_action('admin_menu', array(&$this, 'register_settings_page'));
            add_filter('plugin_action_links', array(&$this, 'add_action_link'), 10, 2);
            add_action('admin_head', array(&$this,'config_page_head'));
        }

        function config_page_head() {
            if ($_GET['page'] == $this->hook) {
                wp_enqueue_script('jquery');
            ?>
                 <script type="text/javascript" charset="utf-8">
                     jQuery(document).ready(function(){
                        jQuery('#explain').click(function(){ jQuery('#explanation').toggle(); });
                    });
                 </script>
            <?php
            }
        }

        function config_page() {
            $options = get_option('BTBucketsTag');

            if ((isset($_POST['reset']) && $_POST['reset'] == "true") || !is_array($options)) {
                $this->set_defaults();
                $options['btb_key'] = '';
            }

            if (isset($_POST['submit'])) {
                if (!current_user_can('manage_options'))
                    die("You cannot edit the BTBuckets options.");

                if (!strlen($_POST['btb_key'])) {
                    $this->error("Please, enter a valid API key.");

                } else {
                    $options['btb_key'] = $_POST['btb_key'];

                    $buffer = @file_get_contents($this->btb_tag_service.$options['btb_key']);
                    $json_obj = @json_decode(substr($buffer, 3));

                    if (strlen($buffer) && $json_obj->tag_code) {
                        $options['btb_tag'] = ($json_obj->tag_code);
                        update_option('BTBucketsTag', $options);
                        $msg = "BTBuckets settings updated, behavioral targeting is <strong>ON</strong>.";
                    } else
                        $msg = "Error updating tag.";
                    $this->warning($msg);
                }
            } else if (!strlen($options['btb_key']))
                add_action('admin_footer', array(&$this, 'disabled_warning'));

            ?>
            <div class="wrap">
                <a href="http://www.btbuckets.com"><div id="btbuckets-icon" style="background: url(http://btbuckets.com/img/mini_logo.gif) no-repeat;width:120px;" class="icon32"><br /></div></a>
                <h2 style="font-family:helvetica; font-size:14px; line-height:34px; font-weight:bold; font-style:normal;">Configuration</h2>
                <div class="postbox-container" style="width:70%;">
                    <div class="metabox-holder">
                        <div class="meta-box-sortables">
                            <form action="" method="post" id="btbuckets-conf">
                                <?php
                                    $rows = array();
                                    $rows[] = array(
                                        'id' => 'btb_key',
                                        'label' => 'BTBuckets key',
                                        'desc' => '<a href="#" id="explain">What\'s this?</a>',
                                        'content' => '<input id="btb_key" name="btb_key" type="text" size="41" maxlength="37" value="'.$options['btb_key'].'"/><br/><div id="explanation" style="background: #fff; border: 1px solid #ccc; padding: 5px; display:none;">
                                            <strong>API key</strong><br/><br/>
                                            It\'s a key that identifies your unique account/website pair within BTBuckets system. If you don\'t have an account on btbuckets.com, click <a href="http://btbuckets.com" target="new">here</a> and create one.<br/><br/>

                                            <strong>To discover your key</strong><br/><br/>
                                            1. Log into <a href="http://www.btbuckets.com" target="blank">btbuckets.com</a><br/>
                                            2. Click on "tags"<br/>
                                            3. Copy the API KEY, shown on the bottom of the page<br/>
                                            4. Paste it on the text input above
                                            <br/>
                                        </div>'
                                    );
                                    $this->postbox('settings', 'Settings', $this->form_table($rows));
                                ?>
                        <div class="submit"><input type="submit" class="button-primary" name="submit" value="Update Settings &raquo;" /></div>
                    </form>
                    <form action="" method="post">
                        <input type="hidden" name="reset" value="true"/>
                        <div class="submit"><input type="submit" value="Reset Default Settings &raquo;" /></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="postbox-container" style="width:20%;">
            <div class="metabox-holder">
                <div class="meta-box-sortables">
                    <?php
                        $this->plugin_like();
                        $this->plugin_support();
                        $this->news();
                    ?>
                </div>
                <br/><br/><br/>
            </div>
        </div>
    </div>
            <?php
        }

        function set_defaults() {
            $options = get_option('BTBucketsTag');
            $options['btb_key'] = '';
            $options['btb_tag'] = '';
            update_option('BTBucketsTag', $options);
        }

        function disabled_warning() {
            echo $this->error('Behavioral targeting is <strong>OFF</strong>. You must enter your API key to activate it.');
        }

        function error($str) {
            echo "<div id='message' class='error'><p>$str</p></div>";
        }

        function warning($str) {
            echo "<div id='updatemessage' class='updated'><p>$str</p></div>";
        }
    }

    $btb_admin = new BT_Admin();
}


/**
 * Insert BTBuckets tag, if applicable
 */
if (!class_exists('BT_Filter')) {
    class BT_Filter {
        function btb_insert_tag() {
            $options = get_option('BTBucketsTag');
            printf("<!-- BTBuckets tag-->%s<!-- BTBuckets tag end -->\n", $options['btb_tag']);
        }
    }
}

if (!is_array(get_option('BTBucketsTag')))
    $btb_admin->set_defaults();

add_action('wp_head', array('BT_Filter', 'btb_insert_tag'), 20);

?>
