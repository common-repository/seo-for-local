<?php if (isset($address_array) && !empty($address_array)): ?>
	<address class="mlsl-address">
		<?php
            echo wp_kses_post(implode($separator, $address_array));
        ?>
		</address>
<?php else: ?>

	<?php if ($show_no_location_error == 'on'): ?>
	<div class="no-location-found"><?php echo esc_html($no_location_found_error); ?></div>
	<?php endif?>

<?php endif?>
