<div class="wpt-map-info-window">
		<div class="wpt-map-info-window-container">
			<div class="title-address-direction-container">
				<?php if (($show_title == 'on') || ($show_address == 'on')): ?>
				<div class="title-address-container">
					<?php if ($show_title == 'on'): ?>
						<h4 class="wpt-location-title"><?php echo esc_html($location_title); ?></h4>
					<?php endif?>

					<?php if ($show_address == 'on'): ?>
						<p class="wpt-location-address"><?php echo esc_html($address_string); ?></p>
					<?php endif?>
				</div>
			<?php endif?>

			<?php if ($show_direction_link == 'on'): ?>
				<div class="direction-container">
					<a href="<?php echo esc_url($directions_link) ?>" target="_blank" title='<?php echo esc_attr($direction_image_alt_title); ?>'>
						<img src="<?php echo esc_url($this->container['plugin_url'] . '/images/directions-icon.png'); ?>">
					</a>
				</div>
			<?php endif?>
		</div>

		<?php if ($show_description == 'on'): ?>
			<p class="wpt-location-description">
				<?php
                echo wp_kses_post($location_description);
                ?></p>
		<?php endif?>

	</div>
</div>