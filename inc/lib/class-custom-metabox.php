<?php

class Custom_Metabox {
    private $fields = array();
    private static $script_loaded = false;// Flag to check if script has been loaded
    public function __construct($id, $title, $post_type, $fields = array()) {
        $this->id = $id;
        $this->title = $title;
        $this->post_type = $post_type;
        $this->fields = $fields;

        add_action('add_meta_boxes', array($this, 'register_metabox'));
        add_action('save_post', array($this, 'save_metabox_data'));
        add_action('admin_head', array($this, 'enque_metbox_css'));
    }

    public function register_metabox() {
        add_meta_box(
            $this->id,
            $this->title,
            array($this, 'metabox_callback'),
            $this->post_type,
            'normal',
            'high'
        );
    }

    public function metabox_callback($post) {
        wp_nonce_field($this->id . '_nonce', $this->id . '_nonce_field');

        echo '<table class="metabox-table">';

        foreach ($this->fields as $field) {
            $value = get_post_meta($post->ID, $field['id'], true);
            switch ($field['type']) {
                case 'text':
                    echo '<tr>';
                    echo '<td><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . ':</label></td>';
                    echo '<td><input type="text" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" value="' . esc_attr($value) . '" /><br></td>';
                    echo '</tr>';
                    break;
                case 'textarea':
                    echo '<tr>';
                    echo '<td><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . ':</label></td>';
                    echo '<td><textarea id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '">' . esc_textarea($value) . '</textarea><br></td>';
                    echo '</tr>';
                    break;
                case 'radio':
                    echo '<tr>';
                    echo '<td><label>' . esc_html($field['label']) . ':</label></td>';
                    foreach ($field['options'] as $option_value => $option_label) {
                        echo '<td><input type="radio" name="' . esc_attr($field['id']) . '" value="' . esc_attr($option_value) . '" ' . checked($value, $option_value, false) . ' /> ' . esc_html($option_label);
                    }
                    echo '<br></td>';
                    echo '</tr>';
                    break;
                case 'checkbox':
                    echo '<tr>';
                    echo '<td><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . ':</label></td>';
                    echo '<td><input type="checkbox" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" value="1" ' . checked($value, 1, false) . ' /><br></td>';
                    echo '</tr>';
                    break;
                case 'wysiwyg':
                    echo '<tr>';
                    echo '<td><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . ':</label></td>';
                    echo '<td>';
                    wp_editor($value, $field['id'], array('textarea_name' => $field['id']));
                    echo '</td></tr>';
                    break;
                case 'upload':
                    echo '<tr>';
                    echo '<td><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . ':</label></td>';
                    echo '<td><input type="text" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" value="' . esc_attr($value) . '" />';
                    echo '<input type="button" id="' . esc_attr($field['id']) . '_button" value="Upload" class="button" />';
                    echo '<div id="' . esc_attr($field['id']) . '_preview">';
                    if ($value) {
                        foreach (explode(',', $value) as $file) {
                            echo '<div class="uploaded-file"><img src="' . esc_url($file) . '" style="max-width:100px;" />
                            <span style="cursor:pointer" class="remove-file" data-file="' . esc_attr($file) . '">Remove</span></div>';
                        }
                    }
                    echo '</div></td>';
                    echo '</tr>';
                    break;
                case 'select':
                    echo '<tr>';
                    echo '<td><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . ':</label>';
                    echo '<td><select id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '">';
                    foreach ($field['options'] as $option_value => $option_label) {
                        echo '<option value="' . esc_attr($option_value) . '" ' . selected($value, $option_value, false) . '>' . esc_html($option_label) . '</option>';
                    }
                    echo '</select><br></td>';
                    echo '</tr>';
                    break;
                case 'dynamic_select':
                    echo '<tr>';
                    echo '<td><label for="' . esc_attr($field['id']) . '">' . esc_html($field['label']) . ':</label>';
                    echo '<td><select id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '">';
                    echo '<option value="">Select a post</option>';
                    $posts = get_posts(array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'numberposts' => -1,
                    ));
                    foreach ($posts as $post) {
                        echo '<option value="' . esc_attr($post->ID) . '" ' . selected($value, $post->ID, false) . '>' . esc_html($post->post_title) . '</option>';
                    }
                    echo '</select><br></td>';
                    echo '</tr>';
                    break;
            }
        }

        echo '</table>';
    }

    public function save_metabox_data($post_id) {
        if (!isset($_POST[$this->id . '_nonce_field']) || !wp_verify_nonce($_POST[$this->id . '_nonce_field'], $this->id . '_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        foreach ($this->fields as $field) {
            $value = isset($_POST[$field['id']]) ? $_POST[$field['id']] : '';
            switch ($field['type']) {
                case 'text':
                case 'textarea':
                case 'radio':
                case 'select':
                case 'dynamic_select':
                    update_post_meta($post_id, $field['id'], sanitize_text_field($value));
                    break;
                case 'checkbox':
                    update_post_meta($post_id, $field['id'], $value ? 1 : 0);
                    break;
                case 'wysiwyg':
                    update_post_meta($post_id, $field['id'], wp_kses_post($value));
                    break;
                case 'upload':
                    update_post_meta($post_id, $field['id'], esc_url_raw($value));
                    break;
            }
        }
    }
    public function enque_metbox_css(){
        ?>
<style>
.metabox-table, table {
    width: 100%;
}

.metabox-table td {
    padding: 5px;
}
.metabox-table td label{
    font-weight: 500;
}

.metabox-table input[type=text], .metabox-table textarea, .metabox-table select, .metabox-table .wysiwyg, .metabox-table .upload {
    width: 100%;
    margin: 10px 0;
    padding: 4px 6px;
}
.remove-file{cursor:pointer;}
</style>
<?php
    }
}