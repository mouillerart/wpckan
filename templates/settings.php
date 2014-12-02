<div class="wrap">
    <h2>WPCKAN -  A plugin for integrating CKAN and WP</h2>
    <form method="post" action="options.php">
        <?php @settings_fields('wpckan-group'); ?>
        <?php @do_settings_fields('wpckan-group'); ?>

        <?php
          $ckan_url = get_option('setting_ckan_url');
          if (!$ckan_url)
            $ckan_url = "http://";
        ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="setting_ckan_url"><?php _e('CKAN Url','wpckan_settings_ckan_url_title') ?></label></th>
                <td>
                  <input type="text" name="setting_ckan_url" id="setting_ckan_url" value="<?php echo $ckan_url ?>"/>
                  <p class="description"><?php _e('Specify protocoll like http:// or https://','wpckan_settings_ckan_url_summary') ?>.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="setting_ckan_api"><?php _e('CKAN Api','wpckan_setting_ckan_api_title') ?></label></th>
                <td>
                  <input type="text" name="setting_ckan_api" id="setting_ckan_api" value="<?php echo get_option('setting_ckan_api'); ?>" />
                  <p class="description"><?php _e('Available under the profile page of a CKAN user with Admin rights.','wpckan_settings_ckan_api_summary') ?>.</p>
                </td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for="setting_ckan_organization"><?php _e('CKAN Organization','wpckan_setting_ckan_organization_title') ?></label></th>
              <td>
                <?php echo wpckan_do_get_organizations_list(); ?>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for"setting_archive_freq"><?php _e('Archive contents when:','wpckan_settings_archive_freq') ?></label></th>
              <td>
                <select name="setting_archive_freq" id="setting_archive_freq">
                  <option value="0" <?php if(get_option('setting_archive_freq') == 0) echo 'selected="selected"' ?>><?php _e('Post is saved','wpckan_settings_archive_freq_0' )?></option>
                  <option value="1" <?php if(get_option('setting_archive_freq') == 1) echo 'selected="selected"' ?>><?php _e('Daily','wpckan_settings_archive_freq_1') ?></option>
                  <option value="2" <?php if(get_option('setting_archive_freq') == 2) echo 'selected="selected"' ?>><?php _e('Weekly','wpckan_settings_archive_freq_2') ?></option>
                </select>
              </td>
            </tr>
        </table>
        <?php @submit_button(); ?>
    </form>
</div>
