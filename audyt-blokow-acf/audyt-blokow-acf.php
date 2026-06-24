<?php
/**
 * Plugin Name:       Audyt Bloków ACF
 * Description:       Wyświetla listę stron i użytych na nich bloków ACF.
 * Version:           1.0.0
 * Author:            GitHub Copilot
 * Author URI:        https://github.com/copilot
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       acf-block-audit
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

class ACF_Block_Audit_Plugin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menu_page']);
    }

    /**
     * Dodaje stronę wtyczki do menu administratora.
     */
    public function add_admin_menu_page()
    {
        add_menu_page(
            __('Audyt Bloków ACF', 'acf-block-audit'),
            __('Audyt Bloków ACF', 'acf-block-audit'),
            'manage_options',
            'acf-block-audit',
            [$this, 'render_admin_page'],
            'dashicons-analytics',
            20
        );
    }

    /**
     * Renderuje zawartość strony wtyczki.
     */
    public function render_admin_page()
    {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Audyt Bloków ACF na Stronach', 'acf-block-audit') . '</h1>';
        echo '<p>' . esc_html__('Poniższa tabela przedstawia listę wszystkich opublikowanych stron oraz bloki ACF, które zostały na nich użyte.', 'acf-block-audit') . '</p>';

        $this->display_pages_and_blocks_table();

        echo '</div>';
    }

    /**
     * Wyświetla tabelę ze stronami i użytymi blokami.
     */
    private function display_pages_and_blocks_table()
    {
        $all_pages = get_pages([
            'post_status' => 'publish',
            'sort_column' => 'post_title',
            'sort_order' => 'asc',
        ]);

        if (empty($all_pages)) {
            echo '<p>' . esc_html__('Nie znaleziono żadnych opublikowanych stron.', 'acf-block-audit') . '</p>';
            return;
        }

        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th scope="col" style="width: 40%;">' . esc_html__('Tytuł Strony', 'acf-block-audit') . '</th>';
        echo '<th scope="col">' . esc_html__('Użyte Bloki ACF', 'acf-block-audit') . '</th>';
        echo '</tr></thead>';
        echo '<tbody>';

        foreach ($all_pages as $page) {
            $blocks = parse_blocks($page->post_content);
            $acf_blocks = array_filter($blocks, function ($block) {
                return $block['blockName'] && strpos($block['blockName'], 'acf/') === 0;
            });

            echo '<tr>';
            echo '<td>';
            echo '<strong><a href="' . esc_url(get_edit_post_link($page->ID)) . '">' . esc_html($page->post_title) . '</a></strong>';
            echo '<br/><small>' . esc_html(get_permalink($page->ID)) . '</small>';
            echo '</td>';
            echo '<td>';

            if (!empty($acf_blocks)) {
                echo '<ul>';
                foreach ($acf_blocks as $block) {
                    // Usuwamy prefix 'acf/' dla czytelności
                    $block_name_clean = str_replace('acf/', '', $block['blockName']);
                    echo '<li>' . esc_html($block_name_clean) . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<em>' . esc_html__('Brak bloków ACF', 'acf-block-audit') . '</em>';
            }

            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }
}

// Inicjalizacja wtyczki
new ACF_Block_Audit_Plugin();