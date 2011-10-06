<div class="wrap">
<h2><?php print nwc_PUGIN_NAME ." ". nwc_CURRENT_VERSION; ?></h2>
<p>Here's a few options for you to change if you would have a suggestion or comment about the plugin, send an email to thejakeneumann (a) gmail (dot) com</p>
<p>To show all of your post titles and word counts on one page, just use the [nanowc] short tag</p>
<form method="post" action="options.php">
    <?php settings_fields( 'nanowc_settings_group' );?>
    <table class="form-table">
         
        <tr valign="top">
        <th scope="row">Would you like to display the word count at the bottom of your posts?</th>
        <td><?php $options = get_option( 'page_option' ); ?>
			Yes <input name="page_option" type="radio" value="0" <?php checked( '0', get_option( 'page_option' ) ); ?> />
			No <input name="page_option" type="radio" value="1" <?php checked( '1', get_option( 'page_option' ) ); ?> /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Category</th>
        <?php $args = array(
            'show_count' => 0,
            'hierarchical' => 0,
            'selected' => get_option('cat'),
        ) ?>
        <td><?php wp_dropdown_categories($args); ?></td>
        </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>