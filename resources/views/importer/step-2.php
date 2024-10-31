<strong>Your data is verified. Press on the "Import Data" button to begin import process.</strong>
<form action="<?php echo esc_url(admin_url('admin.php?import=mlsl_locations_csv&amp;step=2')); ?>" method="post">
	<?php wp_nonce_field('import-locations');?>
	<input type="hidden" name="import_id" value="<?php echo esc_attr($this->id); ?>" />
	<p class="submit"><input type="submit" class="button" value="<?php esc_attr_e('Import Data', 'wordpress-importer');?>" /></p>
</form>