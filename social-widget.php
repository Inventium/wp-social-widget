<?php
/*
 * Plugin name: Demo Plugin Widget
 * Plugin URI: http://website-in-a-weekend.net/plugins/demo-plugins/
 * Description: Demonstrating how to add a WordPress widget with a plugin.
 * Version: 0.1
 * Author: Dave Doolin
 * Author URI: http://website-in-a-weekend.net/
 */

$css_url = WP_PLUGIN_URL.'/demo-plugin-widget/css/demowidget.css';
$css_file = WP_PLUGIN_DIR.'/demo-plugin-widget/css/demowidget.css';

if (file_exists($css_file)) {
	wp_register_style('dw_stylesheet', $css_url);
	wp_enqueue_style('dw_stylesheet');
}

if (!class_exists("demo_plugin_widget")) {

	class demo_plugin_widget extends WP_Widget {
			
		function demo_plugin_widget() {
			$widget_ops = array('classname' => 'widget_rss_links', 'description' => 'Your Global Status' );
			$this->WP_Widget('rss_links', 'Status Star', $widget_ops);
		}

		/* This function doesn't work.
		 * Keep it around for later, it will
		 * be handy for refactoring.
		 */
		function status_buttons() {
			?>
<ul class="statustar">
	<li>R</li>
	<li>|</li>
	<li>Y</li>
	<li>|</li>
	<li>G</li>
</ul>
			<?php
		}

		/* This is the code that gets displayed on the UI side,
		 * what readers see.
		 */
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);

			echo $before_widget;
			$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
			$entry_title = empty($instance['entry_title']) ? '&nbsp;' : apply_filters('widget_entry_title', $instance['entry_title']);
			$comments_title = empty($instance['comments_title']) ? '&nbsp;' : apply_filters('widget_comments_title', $instance['comments_title']);

			if (!empty($title)) { 
				echo $before_title . $title . $after_title; 
			}
			?>

<form name="statusform" method="POST"><?php
if ( $_REQUEST['save'] ) {

	$status = $_POST['status'];
	print $status;
}
?>
<ul class="statustar">
	<li><input type="radio" name="status" value="R"> R</li>
	<li>|</li>
	<li><input type="radio" name="status" value="Y"> Y</li>
	<li>|</li>
	<li><input type="radio" name="status" value="G" checked> G</li>
</ul>
<input type="submit" name="save" value="Save Options &raquo;" /></form>

<?php
            echo $after_widget;
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['entry_title'] = strip_tags($new_instance['entry_title']);
			return $instance;
		}

		/* Back end, the interface shown in Appearance -> Widgets
		 * administration interface.
		 */
		function form($instance) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'entry_title' => '', 'comments_title' => '' ) );
			$title = strip_tags($instance['title']);
			$entry_title = strip_tags($instance['entry_title']);
			?>

<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input
	class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
	name="<?php echo $this->get_field_name('title'); ?>" type="text"
	value="<?php echo attribute_escape($title); ?>" /></label></p>


<p><label for="<?php echo $this->get_field_id('entry_title'); ?>">Title
for entry feed: <input class="widefat"
	id="<?php echo $this->get_field_id('entry_title'); ?>"
	name="<?php echo $this->get_field_name('entry_title'); ?>" type="text"
	value="<?php echo attribute_escape($entry_title); ?>" /></label></p>

			<?php
		}			
	}

	function demo_widget_init() {
		register_widget('demo_plugin_widget');
	}
	add_action('widgets_init', 'demo_widget_init');

}

$wpdpd = new demo_plugin_widget();

?>
