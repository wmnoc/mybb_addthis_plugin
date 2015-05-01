<?php
/**
*@ AddThis Social Sharing and Analytics Widget for MyBB
*@ Author: www.wmnoc.com
*@ Date: 2015-03-20
*@ Version: 0.1
*@ Contact: www.wmnoc.com
*@ AddThis is a trademark of AddThis.com. I am not affiliated to AddThis.com
*/
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.");
}

$plugins->add_hook("pre_output_page", "addthis_show");

function addthis_info()
{
    return array(
		"name"			=> "AddThis",
		"description"	=> "AddThis Sharing and Analytics Widget",
		"website"		=> "http://www.wmnoc.com/",
		"author"		=> "LinuxBB.com",
		"authorsite"	=> "http://www.wmnoc.com/",
		"version"		=> "1.0",
		"guid"          => "",
		"compatibility" => "*",
		"codename"		=> "addthis"
    );
}

function addthis_activate()
{
	global $db;
	$addthis_group = array(
        'gid'    		=> 'NULL',
        'name'  		=> 'addthis',
        'title'      	=> 'AddThis',
        'description'   => 'AddThis Sharing and Analytics, enables users to share pages and webmaster to analyse it!',
        'disporder'    	=> "1",
        'isdefault'  	=> "0",
    );
	$db->insert_query('settinggroups', $addthis_group);
	$gid = $db->insert_id();
	$addthis_setting = array();
	$addthis_setting[] = array(
        'name'       	 => 'addthis_enable',
        'title'          => 'Do you want to enable addthis?',
        'description'    => 'If you set this option to yes, this plugin be active on your board.',
        'optionscode'    => 'yesno',
        'value'        	 => '0',
        'disporder'      => 1,
        'gid'            => $gid
    );
	$addthis_setting[] = array(	
		'name' 			=> 'addthis_pid',
        'title' 		=> 'Profile ID',
        'description' 	=> 'AddThis Profile ID:',
        'optionscode' 	=> 'text',
        'value' 		=> '',
        'disporder' 	=> 2,
        'gid' 			=> $gid
    );

    foreach($addthis_setting as $array => $content)
    {
        $db->insert_query("settings", $content);
    }	
	rebuild_settings();
}

function addthis_deactivate()
{
	global $db;
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN ('addthis_enable')");
    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='addthis'");
	rebuild_settings();
}

function addthis_uninstall()
{
	global $db;
	$db->delete_query('settings', "name = 'addthis_enable'");
	$db->delete_query('settinggroups', "name = 'addthis'");
	rebuild_settings();
}

function addthis_show($page)
{
	
	global $mybb;
	if ($mybb->settings['addthis_enable'] == 1){
		$profile_id	= $mybb->settings['addthis_pid'];
		$page = str_replace("</body>", "<script type='text/javascript'>/* MyBB AddThis Plugin by LinuxBB.com*/var _at= document.createElement('script');_at.type='text/javascript';_at.async=true;_at.src='http://s7.addthis.com/js/300/addthis_widget.js#pubid={$profile_id}&domready=1';var at_s=document.getElementsByTagName('script')[0];at_s.parentNode.insertBefore(_at, at_s);</script>
		</body>", $page);
		return $page;
	}
}
?>
