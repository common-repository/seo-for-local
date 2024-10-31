<div class="<?php echo esc_attr($module_class); ?>">
	<div class="wpt-mlsl-location-map"
	data-lat="<?php echo esc_attr($lat); ?>"
	data-lng="<?php echo esc_attr($lng); ?>"
	data-zoom="<?php echo esc_attr($zoom); ?>"
	data-gesture-handling="<?php echo esc_attr($gesture_handling); ?>"
	data-map-type="<?php echo esc_attr($map_type); ?>"
	data-show-fullscreen-control="<?php echo esc_attr($show_fullscreen_control); ?>"
	data-fullscreen-control-position="<?php echo esc_attr($fullscreen_control_position); ?>"
	data-show-rotate-control="<?php echo esc_attr($show_rotate_control); ?>"
	data-rotate-control-position="<?php echo esc_attr($rotate_control_position); ?>"
	data-show-street-view-control="<?php echo esc_attr($show_street_view_control); ?>"
	data-street-view-control-position="<?php echo esc_attr($street_view_control_position); ?>"
	data-show-scale-control="<?php echo esc_attr($show_scale_control); ?>"
	data-show-map-type-control="<?php echo esc_attr($show_map_type_control); ?>"
	data-map-type-control-map-types='<?php echo wp_json_encode($map_control_map_types); ?>'
	data-zoom-control-position="<?php echo esc_attr($zoom_control_position); ?>"
	data-show-zoom-control="<?php echo esc_attr($show_zoom_control); ?>"
	data-map-control-position="<?php echo esc_attr($map_control_position); ?>"
	data-marker-icon="<?php echo esc_attr($marker_icon); ?>"
	data-marker-animation="<?php echo esc_attr($marker_animation); ?>"
	data-show-info-window="<?php echo esc_attr($show_info_window); ?>"
	data-map-height="<?php echo esc_attr($map_height); ?>"
	data-map-style='<?php echo esc_attr($map_style); ?>'
	data-location-title='<?php echo esc_attr($location_title); ?>'
	>
</div>

<div style='display:none'>
	<?php echo wp_kses_post($info_window_html); ?>
</div>
</div>
