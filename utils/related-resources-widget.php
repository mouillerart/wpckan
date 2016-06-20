<?php

class Wpckan_Related_Resources_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
        'wpckan_related_resources_widget',
        __('WPCKAN Related Datasets', 'wpckan'),
        array('description' => __('Display post related resources.', 'wpckan'))
        );
    }

 /**
  * Outputs the content of the widget.
  *
  * @param array $args
  * @param array $instance
  */
 public function widget($args, $instance)
 {
     global $post;

     $shortcode = '[wpckan_related_datasets';
     if (!empty($instance['group']) && $instance['group'] != '-1') {
         $shortcode .= ' group="'.$instance['group'].'"';
         $group = $instance['group'];
     }
     if (!empty($instance['organization']) && $instance['organization'] != '-1') {
         $shortcode .= ' organization="'.$instance['organization'].'"';
         $organization = ucwords(str_replace('-organization', '', $instance['organization']));
     }
     if (!empty($instance['filter_fields']) && json_decode($instance['filter_fields'])) {
         $shortcode .= ' filter_fields=\''.$instance['filter_fields'].'\'';
     }
     if (!empty($instance['type'])) {
         $shortcode .= ' type="'.$instance['type'].'"';
     }
     if (!empty($instance['limit']) && $instance['limit'] > 0) {
         $shortcode .= ' limit="'.$instance['limit'].'"';
     }
     $shortcode .= ' include_fields_dataset="title" include_fields_resources="format" blank_on_empty="true"]';
     $output = do_shortcode($shortcode);

     if (!empty($output) && $output != '') {
         echo $args['before_widget'];
         if (!empty($instance['title'])) {
             echo $args['before_title'].apply_filters('widget_title', __($instance['title'], 'wpckan')).$args['after_title'];
         }

         echo $output;
         echo $args['after_widget'];
     }
 }

 /**
  * Outputs the options form on admin.
  *
  * @param array $instance The widget options
  */
 public function form($instance)
 {
     $title = !empty($instance['title']) ? __($instance['title'], 'wpckan') : __('Related Resources', 'wpckan');
     $limit = !empty($instance['limit']) ? $instance['limit'] : 0;
     $filter_fields = isset($instance['filter_fields']) && json_decode($instance['filter_fields']) ? $instance['filter_fields'] : null;
     $type = isset($instance['$type']) ? $instance['$type'] : 'dataset';
     $organization = isset($instance['organization']) ? $instance['organization'] : -1;
     $organization_list = [];
     if (function_exists('wpckan_api_get_organizations_list')) {
         try {
             $organization_list = wpckan_api_get_organizations_list();
         } catch (Exception $e) {
         }
     }
     $group = isset($instance['group']) ? $instance['group'] : -1;
     $group_list = [];
     if (function_exists('wpckan_api_get_groups_list')) {
         try {
             $group_list = wpckan_api_get_groups_list();
         } catch (Exception $e) {
         }
     }

     ?>
  <p>
   <label for="<?php echo $this->get_field_id('title');
     ?>"><?php _e('Title:');
     ?></label>
   <input class="widefat" id="<?php echo $this->get_field_id('title');
     ?>" name="<?php echo $this->get_field_name('title');
     ?>" type="text" value="<?php echo esc_attr($title);
     ?>">
   <label for="<?php echo $this->get_field_id('organization');
     ?>"><?php _e('CKAN Organization:');
     ?></label>
   <select class="widefat" id="<?php echo $this->get_field_id('organization');
     ?>" name="<?php echo $this->get_field_name('organization');
     ?>">
      <option <?php if ($organization == -1) {
    echo 'selected="selected"';
}
     ?> value="-1"><?php _e('All', 'wpckan')?></option>
      <?php foreach ($organization_list as $dataset_organization) {
    ?>
       <option <?php if ($dataset_organization['name'] == $organization) {
    echo 'selected="selected"';
}
    ?> value="<?php echo $dataset_organization['name'];
    ?>"><?php echo $dataset_organization['display_name'];
    ?></option>
      <?php

}
     ?>
    </select>
   <label for="<?php echo $this->get_field_id('group');
     ?>"><?php _e('CKAN Group:');
     ?></label>
   <select class="widefat" id="<?php echo $this->get_field_id('group');
     ?>" name="<?php echo $this->get_field_name('group');
     ?>">
      <option <?php if ($group == -1) {
    echo 'selected="selected"';
}
     ?> value="-1"><?php _e('All', 'wpckan')?></option>
      <?php foreach ($group_list as $dataset_group) {
    ?>
       <option <?php if ($dataset_group['name'] == $group) {
    echo 'selected="selected"';
}
    ?> value="<?php echo $dataset_group['name'];
    ?>"><?php echo $dataset_group['display_name'];
    ?></option>
      <?php

}
     ?>
    </select>
    <label for="<?php echo $this->get_field_id('filter_fields');
      ?>"><?php _e('Additional filtering:');
      ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('filter_fields');
      ?>" name="<?php echo $this->get_field_name('filter_fields');
      ?>" type="text" value="<?php echo esc_attr($filter_fields);
      ?>" placeholder="<?php _e('Specify valid JSON, otherwise not saved');
      ?>">
    <label for="<?php echo $this->get_field_id('type');
      ?>"><?php _e('Dataset type:');
      ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('type');
      ?>" name="<?php echo $this->get_field_name('type');
      ?>" type="text" value="<?php echo esc_attr($type);
      ?>" placeholder="<?php _e('dataset, library_record, etc..');
      ?>">
    <label for="<?php echo $this->get_field_id('limit');
     ?>"><?php _e('Limit:');
     ?></label>
    <input class="widefat" type="number" id="<?php echo $this->get_field_id('limit');
     ?>" name="<?php echo $this->get_field_name('limit');
     ?>" value="<?php echo esc_attr($limit);
     ?>">
  </p>
  <?php

 }

 /**
  * Processing widget options on save.
  *
  * @param array $new_instance The new options
  * @param array $old_instance The previous options
  */
 public function update($new_instance, $old_instance)
 {
     $instance = array();
     $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
     $instance['organization'] = (!empty($new_instance['organization'])) ? strip_tags($new_instance['organization']) : '';
     $instance['group'] = (!empty($new_instance['group'])) ? strip_tags($new_instance['group']) : '';
     $instance['limit'] = (!empty($new_instance['limit'])) ? strip_tags($new_instance['limit']) : '';
     $instance['filter_fields'] = (!empty($new_instance['filter_fields'])) ? strip_tags($new_instance['filter_fields']) : '';
     $instance['type'] = (!empty($new_instance['type'])) ? strip_tags($new_instance['type']) : '';

     return $instance;
 }
}

?>
