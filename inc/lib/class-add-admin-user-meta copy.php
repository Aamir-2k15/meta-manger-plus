<?php
/* * * *
 * Class: UserMeta_Admin_Fields
 * Version: 1
 * Date: 19 July, 2024
 * Description: Creates form fields in the wp admin usermeta, bugs in the version beta version media upload needs to be fixed
 * Fields:  
1. `text`
2. `email`
3. `number`
4. `textarea`
5. `wysiwyg`
6. `radio`
7. `checkbox`
8. `checkbox_multiselect`
9. `select`
10. `colorpicker`
11. `wp media library upload`
12.  `date`
 * * * * */

 

 class UserMeta_Admin_Fields {
    private $fields;

    public function __construct($fields) {
        $this->fields = $fields;
        add_action('show_user_profile', array($this, 'render_fields'));
        add_action('edit_user_profile', array($this, 'render_fields'));
        add_action('personal_options_update', array($this, 'save_fields'));
        add_action('edit_user_profile_update', array($this, 'save_fields'));
        // add_action('admin_footer', array($this, 'enqueue_scripts'));
    }

    public function render_fields($user) {
        echo '<div class="wrap">';
        echo '<h2>User Meta Fields</h2>';
        echo '<table class="form-table">';

        foreach ($this->fields as $field) {
            $value = get_user_meta($user->ID, $field['id'], true);
            $this->render_field($field, $value);
        }

        echo '</table>';
        echo '</div>';
    }

    private function render_field($field, $value) {
        echo '<tr>';
        echo '<th><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . '</label></th>';
        echo '<td>';

        switch ($field['type']) {
            case 'text':
            case 'email':
            case 'number':
            case 'date':
                // echo '<input type="' . esc_attr($field['type']) . '" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($field['placeholder']) . '" ' . $this->render_custom_attributes($field['custom_attributes']) . ' class="regular-text" />';
                echo '<input type="' . esc_attr($field['type']) . '" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($field['placeholder']) . '"  class="regular-text" />';
                break;

            case 'textarea':
                echo '<textarea id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '" placeholder="' . esc_attr($field['placeholder']) . '" class="large-text">' . esc_textarea($value) . '</textarea>';
                break;

            case 'radio':
                foreach ($field['options'] as $option_value => $option_label) {
                    echo '<label><input type="radio" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '" value="' . esc_attr($option_value) . '" ' . checked($value, $option_value, false) . ' /> ' . esc_html($option_label) . '</label><br>';
                }
                break;

            case 'checkbox':
                echo '<label><input type="checkbox" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '" ' . checked($value, 'on', false) . ' /> ' . esc_html($field['description']) . '</label>';
                break;

            case 'checkbox_multiselect':
                foreach ($field['options'] as $option_value => $option_label) {
                    echo '<label><input type="checkbox" id="' . esc_attr($field['id']) . '_' . esc_attr($option_value) . '" name="' . esc_attr($field['name']) . '[]" value="' . esc_attr($option_value) . '" ' . checked(in_array($option_value, (array)$value), true, false) . ' /> ' . esc_html($option_label) . '</label><br>';
                }
                break;

            case 'select':
                echo '<select id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '">';
                foreach ($field['options'] as $option_value => $option_label) {
                    echo '<option value="' . esc_attr($option_value) . '" ' . selected($value, $option_value, false) . '>' . esc_html($option_label) . '</option>';
                }
                echo '</select>';
                break;

            case 'colorpicker':
                echo '<input type="text" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['name']) . '" value="' . esc_attr($value) . '" class="color-picker" data-default-color="' . esc_attr($field['placeholder']) . '" />';
                break;

            case 'upload':
                echo '<input type="text" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" value="' . esc_attr($value) . '" />';
                echo '<input type="button" id="' . esc_attr($field['id']) . '_button" value="Upload" class="button-primary upload_button" />';
                echo '<div id="' . esc_attr($field['id']) . '_preview">';
                if ($value) {
                    foreach (explode(',', $value) as $file) {
                        echo '<div class="uploaded-file"><img src="' . esc_url($file) . '" style="max-width:100px;" />
                        <span style="cursor:pointer" class="remove-file" data-file="' . esc_attr($file) . '">Remove</span></div>';
                    }
                }
                echo '</div>';
                break;
        }

        echo '</td>';
        echo '</tr>';
    }

    private function render_custom_attributes($attributes) {
        $attr_string = '';
        if (!empty($attributes) && is_array($attributes)) {
            foreach ($attributes as $attr => $value) {
                $attr_string .= esc_attr($attr) . '="' . esc_attr($value) . '" ';
            }
        }
        return $attr_string;
    }

    public function save_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }

        foreach ($this->fields as $field) {
            if (isset($_POST[$field['id']])) {
                update_user_meta($user_id, $field['id'], $_POST[$field['id']]);
            } else {
                delete_user_meta($user_id, $field['id']);
            }
        }
    }

  
    
}
