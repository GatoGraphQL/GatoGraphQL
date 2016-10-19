<div class="s3-details">
	<?php if ( ! $s3object ) : ?>
		<div class="misc-pub-section">
			<em class="not-copied"><?php _e( 'This item has not been copied to S3 yet.', 'amazon-s3-and-cloudfront' ); ?></em>
		</div>
	<?php else : ?>
		<div class="misc-pub-section">
			<div class="s3-key"><?php echo $this->get_media_action_strings( 'bucket' ); ?>:</div>
			<input type="text" class="widefat" readonly="readonly" value="<?php echo $s3object['bucket']; ?>">
		</div>
		<div class="misc-pub-section">
			<div class="s3-key"><?php echo $this->get_media_action_strings( 'key' ); ?>:</div>
			<input type="text" class="widefat" readonly="readonly" value="<?php echo $s3object['key']; ?>">
		</div>
		<?php if ( isset( $s3object['region'] ) && $s3object['region'] ) : ?>
			<div class="misc-pub-section">
				<div class="s3-key"><?php echo $this->get_media_action_strings( 'region' ); ?>:</div>
				<div class="s3-value"><?php echo $s3object['region']; ?></div>
			</div>
		<?php endif; ?>
		<div class="misc-pub-section">
			<div class="s3-key"><?php echo $this->get_media_action_strings( 'acl' ); ?>:</div>
			<div class="s3-value">
				<?php echo $this->get_acl_value_string( $s3object['acl'] ); ?>
			</div>
		</div>
		<?php if ( $user_can_perform_actions && ! $local_file_exists ) : ?>
			<div class="misc-pub-section">
				<div class="not-copied"><?php _e( 'File does not exist on server', 'amazon-s3-and-cloudfront' ); ?></div>
				<a href="<?php echo $this->get_media_action_url( 'download', $post->ID, $sendback ); ?>">
					<?php echo $this->get_media_action_strings( 'download' ); ?>
				</a>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<div class="clear"></div>
</div>
<?php if ( $user_can_perform_actions && ( $s3object || $local_file_exists ) ) : ?>
	<div class="s3-actions">
		<?php if ( $s3object ) : ?>
			<div class="remove-action">
				<a href="<?php echo $this->get_media_action_url( 'remove', $post->ID, $sendback ); ?>" class="<?php echo ( ! $local_file_exists ) ? 'local-warning' : ''; ?>">
					<?php echo $this->get_media_action_strings( 'remove' ); ?>
				</a>
			</div>
		<?php endif; ?>
		<?php if ( $local_file_exists ) : ?>
			<div class="copy-action">
				<a href="<?php echo $this->get_media_action_url( 'copy', $post->ID, $sendback ); ?>" class="button button-secondary">
					<?php echo $this->get_media_action_strings( 'copy' ); ?>
				</a>
			</div>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
<?php endif; ?>
