<?php if (isset($post_id)): ?>
<div class="mlsl-opening-hours">
	<table>
		<tbody>
			<?php foreach ($days as $day): ?>

				<?php if ((($show_closed_days == 'on') && !isset($opening_hours_map[$day])) || isset($opening_hours_map[$day])): ?>


				<tr>
					<td class="mlsl-day-label"><?php echo esc_html($day); ?></td>

					<?php if (($show_closed_days == 'on') && !isset($opening_hours_map[$day])): ?>
						<td class='mlsl-timing-closed'><?php echo esc_html($closed_label); ?></td>
					<?php endif?>

					<?php if (isset($opening_hours_map[$day])): ?>
						<td class="mlsl-day-timings-cell">
							<div class="mlsl-timings">
								<?php foreach ($opening_hours_map[$day] as $opening_hour): ?>

									<?php
                                        // phpcs:ignore
                                        echo sprintf('<div class="mlsl-timing">%s - %s</div>', $opening_hour['opens'], $opening_hour['closes']);
                                    ?>

								<?php endforeach?>
							</div>
						</td>
					<?php endif?>

				</tr>

				<?php endif?>

			<?php endforeach?>
		</tbody>
	</table>

</div>
<?php else: ?>

	<?php if ($show_no_location_error == 'on'): ?>
		<div class="no-location-found"><?php echo esc_html($no_location_found_error); ?></div>
	<?php endif?>

<?php endif?>
