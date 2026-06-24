<?php
/*
Plugin Name: Peek-a-Boo
Description: Removes the logo from the WordPress login page and hides admin notices.
Version: 1.2
Author: OhSoFresh
*/

// Hides admin notices and removes the WordPress logo from the admin bar
add_action('admin_head', function() {
    echo '<style>
	#toplevel_page_edit-post_type-acf-field-group {
	display:none !imporatnt;
	}
        .notice, .update-nag, .notice-warning, .notice-info, .notice-success, .notice-error {
            display: none !important;
        }.wp-block {
    margin-bottom: 20px; /* Dodaj odstęp między blokami */
}

.block-editor-block-list__block {
    ul {
      list-style-type: disc;
      padding-left: 24px;
    }
}
.block-editor-block-list__layout > .block-editor-block-list__block {
    margin-bottom: 20px; /* Dodaj odstęp między blokami w edytorze */
}



        .ant-alert.ant-alert-info.ant-alert-no-icon {
            display: none !important;
        }
        html :where(.wp-block) {
            max-width: 1230px !important;
			display:block;
        }

		.acf-repeater .acf-row:nth-child(odd) .acf-row-handle {
			background:#dfdfdf !important;
		}
		
		.editor-styles-wrapper .block-editor-block-list__layout.is-root-container > :where(:not(.alignleft):not(.alignright):not(.alignfull)) {
            max-width: 1230px !important;
			display:block;
		}
        #wp-admin-bar-wp-logo, #wpfooter, #wp-admin-bar-updates, #wp-admin-bar-comments, #wp-admin-bar-new-content, #wp-admin-bar-w3tc {
            display:none !important;
        }
        #collapse-menu {
            display:none !important;
        }
        #menu-dashboard, #menu-comments {
            display:none !important;
        }
		/*---.username.column-username {
		display:none;}
		.table-view-list.users .check-column {
		display:none;
		}---*/
        [data-name="block-title"] {
			padding-top:0 !important;
			padding-bottom:0 !important;
			min-height: 20px;
		}

		[data-name="block-title"] .acf-label {
			display:none !important;
		}
		[data-name="block-title"] input {
			font-size: 10px !important;
			line-height: 1;
			border: none;
			font-weight: normal;
			padding: 0 !important;
			opacity: 0.5;
			text-transform: uppercase;
			min-height: 20px;
			transition: all 0.1s ease-out;
			border:none !important;
		}
		[data-name="block-title"] input:focus {
			border:none !important;
			box-shadow: none;
			opacity:0.9;
			font-weight: 600;
			transition: all 0.1s ease-out;
		}
        /* .components-editor-notices__dismissible, .components-editor-notices__pinned {
          display:none;
        } */
    </style>';
});

add_action('wp_head', function() {
    echo '<style>
	
        #wp-admin-bar-wp-logo, #wpfooter, #wp-admin-bar-updates, #wp-admin-bar-comments, #wp-admin-bar-new-content, #wp-admin-bar-w3tc, #screen-meta-links, #wp-admin-bar-show_template_file_name_on_top, #wp-admin-bar-customize, #wp-admin-bar-site-name, #wp-admin-bar-duplicate-post {
            display:none !important;
        }
		  .acf-field-wysiwyg iframe #tinymce.wp-editor body {
            background: #fff !important;
        }
        .acf-field-wysiwyg iframe body, #tinymce.wp-editor {
            background: #fff !important;
            color: #000 !important;
            font-family: sans-serif !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
            padding: 20px !important;
        }
		.acf-field-wysiwyg iframe html, 
.acf-field-wysiwyg iframe body {
    all: initial !important;
}
    </style>';
});

// Removes the logo from the WordPress login page
add_action('login_head', function() {
    echo '<style>
        .login h1 a {
            display:none !important;
        }
		#tinymce.wp-editor {
            background: red !important;
		}
    </style>';
});

// Add menu item to the admin menu
add_action('admin_menu', 'peekaboo_add_admin_menu');
function peekaboo_add_admin_menu() {
    add_options_page('Peek-a-Boo Settings', 'Peek-a-Boo Menu', 'manage_options', 'peekaboo', 'peekaboo_options_page');
    add_menu_page('Menus', 'Menu', 'manage_options', 'nav-menus.php', '', 'dashicons-menu', 61);
 /*    add_menu_page('ACFs', 'ACFs', 'manage_options', 'edit.php?post_type=acf-field-group', '', 'dashicons-layout', 62); */
} 

// Display the options page
function peekaboo_options_page() {
    ?>
    <div class="wrap">
        <h1>Peek-a-Boo Menu Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('peekaboo_options_group');
            do_settings_sections('peekaboo');
            submit_button();
            ?>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectAllButton = document.getElementById('select-all');
            var checkboxes = document.querySelectorAll('input[name^="peekaboo_options"]');

            selectAllButton.addEventListener('click', function() {
                var allChecked = Array.from(checkboxes).every(function(checkbox) {
                    return checkbox.checked;
                });
                
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = !allChecked;
                });
            });
        });
    </script>
    <?php
}

// Register and define the settings
add_action('admin_init', 'peekaboo_register_settings');
function peekaboo_register_settings() {
    register_setting('peekaboo_options_group', 'peekaboo_options', 'peekaboo_options_validate');
    register_setting('peekaboo_options_group', 'peekaboo_users');
    register_setting('peekaboo_options_group', 'peekaboo_custom_selectors'); // Register new setting
    add_settings_section('peekaboo_main', 'Wybierz elementy do ukrycia', 'peekaboo_section_text', 'peekaboo');
    add_settings_field('peekaboo_items', 'Elementy menu', 'peekaboo_setting_string', 'peekaboo', 'peekaboo_main');
    add_settings_section('peekaboo_users_section', '', 'peekaboo_users_section_text', 'peekaboo');
    add_settings_field('peekaboo_users_field', 'Użytkownicy', 'peekaboo_users_setting_string', 'peekaboo', 'peekaboo_users_section');
    add_settings_field('peekaboo_custom_selectors', 'Custom Selectors', 'peekaboo_custom_selectors_field', 'peekaboo', 'peekaboo_main'); // Add new field
}

function peekaboo_section_text() {
    echo '<p>Wybierz elementy, które chcesz ukryć w menu.</p>';
}

function peekaboo_users_section_text() {
    echo '<button type="button" id="select-all">Zaznacz wszystkie</button>';
    echo '<h2>Wybierz użytkowników</h2><p>Wybierz użytkowników, którzy będą widzieli wszystkie elementy menu.</p>';
}

function peekaboo_setting_string() {
    $options = get_option('peekaboo_options');
    $menu_items = peekaboo_get_menu_items();

    foreach ($menu_items as $item) {
        $checked = isset($options[$item['id']]) ? 'checked="checked"' : '';
        echo '<label><input type="checkbox" name="peekaboo_options[' . $item['id'] . ']" value="1" ' . $checked . '> ' . $item['title'] . '</label><br>';
    }
}

function peekaboo_users_setting_string() {
    $selected_users = get_option('peekaboo_users', []);
    if (!is_array($selected_users)) {
        $selected_users = [];
    }
    $users = get_users(['role' => 'administrator']);

    foreach ($users as $user) {
        $checked = in_array($user->ID, $selected_users) ? 'checked="checked"' : '';
        echo '<label><input type="checkbox" name="peekaboo_users[]" value="' . esc_attr($user->ID) . '" ' . $checked . '> ' . esc_html($user->display_name) . '</label><br>';
    }
}

// New function for custom selectors field
function peekaboo_custom_selectors_field() {
    $custom_selectors = get_option('peekaboo_custom_selectors', '');
    echo '<textarea name="peekaboo_custom_selectors" rows="5" cols="50" placeholder="Enter CSS selectors to hide, separated by commas.">' . esc_textarea($custom_selectors) . '</textarea>';
    echo '<p>Enter CSS selectors (e.g., #element-id, .element-class) separated by commas to hide them in the admin panel.</p>';
}

// Get all admin menu items
function peekaboo_get_menu_items() {
    global $menu;
    $items = [];

    foreach ($menu as $menu_item) {
        if (empty($menu_item[5])) continue;
        $items[] = [
            'id' => $menu_item[5],
            'title' => strip_tags($menu_item[0]),
        ];
    }

    return $items;
}

// Hide selected menu items
add_action('admin_head', 'peekaboo_hide_menu_items');
function peekaboo_hide_menu_items() {
    $current_user = wp_get_current_user();
    $selected_users = get_option('peekaboo_users', []);
    if (!is_array($selected_users)) {
        $selected_users = [];
    }
    if (in_array($current_user->ID, $selected_users)) {
        return;
    }

    $options = get_option('peekaboo_options');
    $custom_selectors = get_option('peekaboo_custom_selectors', '');

    if (!empty($options) || !empty($custom_selectors)) {
        echo '<style>';
        if (!empty($options)) {
            foreach ($options as $id => $value) {
                echo '#' . esc_attr($id) . ' { display: none !important; }';
            }
        }
        if (!empty($custom_selectors)) {
            $selectors = explode(',', $custom_selectors);
            foreach ($selectors as $selector) {
                echo trim($selector) . ' { display: none !important; }';
            }
        }
        echo '</style>';
    }
}

// Change WooCommerce menu item name and icon
add_action('admin_menu', 'peekaboo_change_woocommerce_menu', 999);
function peekaboo_change_woocommerce_menu() {
    global $menu;
    foreach ($menu as $key => $value) {
        if ($menu[$key][2] == 'woocommerce') {
            $menu[$key][0] = 'Sklep';
            $menu[$key][6] = 'dashicons-cart';
        }
    }
}

// Add "Settings" link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'peekaboo_add_action_links');
function peekaboo_add_action_links($links) {
    $settings_link = '<a href="options-general.php?page=peekaboo">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Add a dot to the footer
add_action('admin_footer', 'add_dot_to_footer');
function add_dot_to_footer() {
    echo '<div id="acf-dot-footer" style="text-align: center; padding: 10px 0; position:absolute; bottom:4px; right:24px; opacity:0.1;">
        <a href="' . admin_url('edit.php?post_type=acf-field-group') . '">•</a>
    </div>';
}
?>
