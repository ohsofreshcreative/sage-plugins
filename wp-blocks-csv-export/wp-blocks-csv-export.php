<?php
/**
 * Plugin Name: WP Blocks CSV Export (Final Corrected)
 * Description: Export block content, skipping a specific and accurate list of ignored fields.
 * Version: 0.2.5
 * Author: ohsofreshcreative
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('WP_Blocks_CSV_Export_Corrected')) {
    class WP_Blocks_CSV_Export_Corrected {
        const NONCE_ACTION = 'wp_blocks_csv_export_ajax';

        /**
         * --- OSTATECZNA CZARNA LISTA PÓL DO IGNOROWANIA ---
         */
        const IGNORED_FIELDS = [
            // Pełne nazwy pól do ignorowania
            'team_categories',
            'selection_mode',
            'selected_offers',
            'lightbg', 'graybg', 'whitebg', 'brandbg',
            'block-title',
            'section_id',
            'section_class',
			'r_tiles_0_image',
            'nomt', 'nolist', 'flip', 'wide', 'gap', 'background',

            // Końcówki pól do ignorowania (np. z obrazków lub linków)
            '_image', // np. 'g_hero_image', 'cta_bg_image'
            '.target',// np. 'button.target'
            '.url',   // np. 'button.url'
        ];

        public function __construct() {
            add_action('add_meta_boxes', [$this, 'add_metabox']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
            add_action('wp_ajax_export_blocks_csv', [$this, 'handle_ajax_export']);
        }

        public function add_metabox($post) {
            add_meta_box('wp_blocks_csv_export_metabox', 'Eksport treści CSV', [$this, 'render_metabox'], null, 'side', 'default');
        }

        public function render_metabox($post) {
            ?>
            <p>Eksportuje tylko czystą treść tekstową z bloków, pomijając pola z czarnej listy.</p>
            <button type="button" id="wp-blocks-csv-export-btn" class="button button-primary" data-post-id="<?php echo esc_attr($post->ID); ?>">
              Eksportuj CSV (dla Excela)
            </button>
            <p id="wp-blocks-csv-export-status" style="margin-top: 8px; font-style: italic; color: #555;"></p>
            <?php
        }

        public function enqueue_scripts($hook) {
            if ('post.php' !== $hook && 'post-new.php' !== $hook) return;
            add_action('admin_footer', function() {
                $nonce = wp_create_nonce(self::NONCE_ACTION);
                ?>
                <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    const btn = document.getElementById('wp-blocks-csv-export-btn'), status = document.getElementById('wp-blocks-csv-export-status');
                    if(!btn) return;
                    btn.addEventListener('click', function() {
                        status.style.color='#555'; status.textContent='Przygotowuję plik...'; btn.disabled=true;
                        const data = new FormData();
                        data.append('action','export_blocks_csv'); data.append('post_id',this.dataset.postId); data.append('nonce','<?php echo $nonce; ?>');
                        fetch(ajaxurl, {method:'POST',body:data})
                        .then(res => res.ok ? res.json() : res.text().then(text => Promise.reject(new Error('Błąd serwera: '+text.slice(0,300)))))
                        .then(result => {
                            if(result.success){
                                status.textContent='Gotowe! Pobieranie...';
                                const blob = new Blob([new Uint8Array([0xEF,0xBB,0xBF]), result.data.csv],{type:'text/csv;charset=utf-8;'});
                                const link = document.createElement("a");
                                link.href=URL.createObjectURL(blob); link.download=result.data.filename;
                                document.body.appendChild(link); link.click(); document.body.removeChild(link);
                                setTimeout(() => { status.textContent = 'Pobrano pomyślnie.'; }, 500);
                            } else { throw new Error(result.data.message || 'Nieznany błąd.'); }
                        })
                        .catch(err => { console.error('Błąd eksportu:',err); status.style.color='#d63638'; status.textContent='Błąd: '+err.message; })
                        .finally(() => btn.disabled=false);
                    });
                });
                </script>
                <?php
            });
        }

        public function handle_ajax_export() {
            if (!check_ajax_referer(self::NONCE_ACTION, 'nonce', false)) { wp_send_json_error(['message' => 'Błąd sesji.'], 403); }
            $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
            if (!current_user_can('edit_post', $post_id)) { wp_send_json_error(['message' => 'Brak uprawnień.'], 403); }
            $post = get_post($post_id);
            if (!$post) { wp_send_json_error(['message' => 'Nie znaleziono wpisu.'], 404); }
            
            $blocks = parse_blocks($post->post_content);
            $rows = [];
            $this->walk_blocks($blocks, $post, $rows);
            if (empty($rows)) { wp_send_json_error(['message' => 'Nie znaleziono treści do eksportu.'], 404); }
            
            wp_send_json_success([
                'filename' => sprintf('eksport-%s-%s.csv', sanitize_title($post->post_name), date('Y-m-d')),
                'csv' => $this->build_csv_string($rows)
            ]);
        }
        
        private function walk_blocks(array $blocks, WP_Post $post, array &$rows, &$index_counter = 0) {
            foreach ($blocks as $block) {
                if (empty($block['blockName'])) continue;
                $current_block_index = $index_counter++; $attrs = $block['attrs'] ?? [];
                if (isset($attrs['data']) && is_array($attrs['data'])) {
                    $flat = [];
                    $this->flatten_acf_data($attrs['data'], $flat);
                    foreach ($flat as $field_path => $value) {
                        $value_str = $this->stringify_and_clean_value($value);
                        if (empty($value_str) && $value_str !== '0') continue;
                        $rows[] = ['post_id'=>$post->ID, 'post_type'=>$post->post_type, 'post_title'=>$post->post_title, 'post_slug'=>$post->post_name, 'post_url'=>get_permalink($post), 'block_index'=>$current_block_index, 'block_name'=>$block['blockName'], 'block_slug'=>!empty($attrs['anchor'])?$attrs['anchor']:'', 'field_path'=>$field_path, 'value'=>$value_str];
                    }
                }
                if (!empty($block['innerBlocks'])) { $this->walk_blocks($block['innerBlocks'], $post, $rows, $index_counter); }
            }
        }
        
        private function flatten_acf_data(array $data, array &$out, $prefix = '') {
            foreach ($data as $key => $value) {
                if (is_string($key) && $key[0] === '_') continue;
                $path = $prefix ? $prefix . '.' . $key : (string)$key;
                if ($this->is_field_ignored($path)) continue;
                if (is_array($value) && !empty($value)) {
                    if (array_keys($value) === range(0, count($value)-1)) {
                        foreach ($value as $i => $item) { $this->flatten_acf_data(is_array($item)?$item:['item'=>$item], $out, $path.'['.$i.']'); }
                    } else { $this->flatten_acf_data($value, $out, $path); }
                } else { $out[$path] = $value; }
            }
        }

        private function is_field_ignored($field_path) {
            foreach (self::IGNORED_FIELDS as $ignored) {
                // Jeśli ignorowane słowo zaczyna się od '.', sprawdzaj tylko końcówkę
                if ($ignored[0] === '.') {
                    if (str_ends_with($field_path, $ignored)) return true;
                } 
                // W przeciwnym razie sprawdzaj, czy ścieżka zaczyna się od ignorowanego słowa
                // To poprawnie zablokuje 'selected_offers' i jego dzieci 'selected_offers[0]' itp.
                else {
                    if (str_starts_with($field_path, $ignored)) return true;
                }
            }
            return false;
        }

        private function build_csv_string(array $rows) {
            $out = fopen('php://memory', 'w');
            fputcsv($out, ['ID Wpisu', 'Typ Wpisu', 'Tytuł Wpisu', 'Slug Wpisu', 'URL Wpisu', 'Indeks Bloku', 'Nazwa Bloku', 'ID Bloku (Anchor)', 'Ścieżka Pola', 'Wartość'], ';');
            foreach ($rows as $row) {
                fputcsv($out, [
                    $row['post_id'], $row['post_type'], $row['post_title'], $row['post_slug'], $row['post_url'],
                    $row['block_index'], $row['block_name'], $row['block_slug'], $row['field_path'], $row['value']
                ], ';');
            }
            rewind($out); $csv = stream_get_contents($out); fclose($out);
            return $csv;
        }

        private function stringify_and_clean_value($value) {
            if (is_scalar($value)) return trim(wp_strip_all_tags((string)$value, true));
            return null;
        }
    }
    new WP_Blocks_CSV_Export_Corrected();
}

if (!function_exists('str_starts_with')) { function str_starts_with($haystack, $needle) { return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0; } }
if (!function_exists('str_ends_with')) { function str_ends_with($haystack, $needle) { return $needle !== '' && substr($haystack, -strlen($needle)) === (string)$needle; } }