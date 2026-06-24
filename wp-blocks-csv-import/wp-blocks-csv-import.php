<?php
/**
 * Plugin Name: WP Blocks CSV Import
 * Description: Import block content from CSV exported by WP Blocks CSV Export plugin.
 * Version: 0.5.0
 * Author: ohsofreshcreative
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('WP_Blocks_CSV_Import')) {

    class WP_Blocks_CSV_Import {

        const NONCE_ACTION = 'wp_blocks_csv_import_ajax';

        public function __construct() {
            add_action('add_meta_boxes',            [$this, 'add_metabox']);
            add_action('admin_enqueue_scripts',     [$this, 'enqueue_scripts']);
            add_action('wp_ajax_import_blocks_csv', [$this, 'handle_ajax_import']);
        }

        /* ------------------------------------------------------------------ */
        /*  Metabox                                                             */
        /* ------------------------------------------------------------------ */

        public function add_metabox() {
            add_meta_box(
                'wp_blocks_csv_import_metabox',
                'Import treści CSV',
                [$this, 'render_metabox'],
                null,
                'side',
                'default'
            );
        }

        public function render_metabox($post) {
            ?>
            <p>Importuj treść z pliku CSV wyeksportowanego wcześniej.</p>
            <input type="file" id="wp-csv-import-file" accept=".csv"
                   style="margin-bottom:10px;width:100%;" />
            <button type="button" id="wp-csv-import-btn"
                    class="button button-primary" style="width:100%;"
                    data-post-id="<?php echo esc_attr($post->ID); ?>">
                Importuj CSV
            </button>
            <p id="wp-csv-import-status"
               style="margin-top:8px;font-style:italic;color:#555;"></p>
            <div id="wp-csv-import-preview"
                 style="margin-top:10px;max-height:300px;overflow-y:auto;
                        display:none;border:1px solid #ddd;padding:10px;
                        background:#f9f9f9;">
                <h4 style="margin:0 0 8px;">Podgląd zmian:</h4>
                <ul id="wp-csv-import-preview-list"
                    style="margin:0;padding-left:20px;"></ul>
            </div>
            <?php
        }

        /* ------------------------------------------------------------------ */
        /*  JavaScript                                                          */
        /* ------------------------------------------------------------------ */

        public function enqueue_scripts($hook) {
            if ($hook !== 'post.php' && $hook !== 'post-new.php') return;

            add_action('admin_footer', function () {
                $nonce = wp_create_nonce(self::NONCE_ACTION);
                ?>
                <script>
                document.addEventListener('DOMContentLoaded', function () {

                    var btn         = document.getElementById('wp-csv-import-btn');
                    var fileInput   = document.getElementById('wp-csv-import-file');
                    var statusEl    = document.getElementById('wp-csv-import-status');
                    var preview     = document.getElementById('wp-csv-import-preview');
                    var previewList = document.getElementById('wp-csv-import-preview-list');

                    if (!btn || !fileInput) return;

                    /* ---------------------------------------------------------
                     * Parser CSV (RFC 4180)
                     * - separator ;
                     * - pola wieloliniowe w "..."
                     * - "" jako escaped cudzysłów wewnątrz pola
                     * - obsługa BOM, \r\n, \n
                     * ------------------------------------------------------- */
                    function parseCSV(text) {
                        var SEP  = ';';
                        var rows = [];
                        var i    = 0;
                        var n    = text.length;

                        // Pomiń BOM
                        if (n > 0 && text.charCodeAt(0) === 0xFEFF) i++;

                        // Pomiń wiersz nagłówka
                        while (i < n && text[i] !== '\n' && text[i] !== '\r') i++;
                        if (i < n && text[i] === '\r') i++;
                        if (i < n && text[i] === '\n') i++;

                        while (i < n) {
                            var row = [];

                            while (i < n) {
                                var field = '';

                                if (text[i] === '"') {
                                    i++; // pomiń otwierający "
                                    while (i < n) {
                                        if (text[i] === '"') {
                                            if (i + 1 < n && text[i + 1] === '"') {
                                                field += '"';
                                                i += 2;
                                            } else {
                                                i++; // zamykający "
                                                break;
                                            }
                                        } else {
                                            field += text[i++];
                                        }
                                    }
                                } else {
                                    while (i < n && text[i] !== SEP &&
                                           text[i] !== '\n' && text[i] !== '\r') {
                                        field += text[i++];
                                    }
                                }

                                row.push(field);

                                if (i < n && text[i] === SEP) {
                                    i++;
                                } else {
                                    break;
                                }
                            }

                            if (i < n && text[i] === '\r') i++;
                            if (i < n && text[i] === '\n') i++;

                            if (row.length >= 10 &&
                                row[0].trim() !== '' &&
                                row[8].trim() !== '') {
                                rows.push(row);
                            }
                        }

                        return rows;
                    }

                    function setStatus(type, msg) {
                        statusEl.style.color =
                            type === 'ok'    ? '#00a32a' :
                            type === 'error' ? '#d63638' : '#555';
                        statusEl.textContent = msg;
                    }

                    function escHtml(str) {
                        return String(str)
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;');
                    }

                    /* ---------------------------------------------------------
                     * Klik przycisku
                     * ------------------------------------------------------- */
                    btn.addEventListener('click', function () {
                        var file = fileInput.files[0];

                        if (!file) {
                            setStatus('error', 'Proszę wybrać plik CSV.');
                            return;
                        }
                        if (!file.name.toLowerCase().endsWith('.csv')) {
                            setStatus('error', 'Plik musi mieć rozszerzenie .csv');
                            return;
                        }

                        setStatus('info', 'Wczytuję plik...');
                        btn.disabled          = true;
                        preview.style.display = 'none';

                        var reader = new FileReader();

                        reader.onload = function (e) {
                            var rows;
                            try {
                                rows = parseCSV(e.target.result);
                            } catch (err) {
                                setStatus('error', 'Błąd parsowania CSV: ' + err.message);
                                btn.disabled = false;
                                return;
                            }

                            if (!rows || rows.length === 0) {
                                setStatus('error', 'Nie znaleziono danych w pliku CSV.');
                                btn.disabled = false;
                                return;
                            }

                            setStatus('info', 'Wysyłam ' + rows.length + ' wierszy...');

                            /*
                             * Base64 — chroni przed WordPress addslashes()
                             * które niszczyłoby \n i \t wewnątrz JSON.
                             * btoa(unescape(encodeURIComponent(str))) = bezpieczne UTF-8.
                             */
                            var b64 = btoa(unescape(encodeURIComponent(JSON.stringify(rows))));

                            var fd = new FormData();
                            fd.append('action',   'import_blocks_csv');
                            fd.append('post_id',  btn.dataset.postId);
                            fd.append('nonce',    '<?php echo esc_js($nonce); ?>');
                            fd.append('csv_rows', b64);

                            fetch(ajaxurl, { method: 'POST', body: fd })
                                .then(function (res) {
                                    if (res.ok) return res.json();
                                    return res.text().then(function (t) {
                                        throw new Error('Błąd serwera: ' + t.slice(0, 400));
                                    });
                                })
                                .then(function (result) {
                                    if (!result.success) {
                                        throw new Error(result.data && result.data.message
                                            ? result.data.message : 'Nieznany błąd.');
                                    }

                                    setStatus('ok',
                                        'Zaktualizowano ' + result.data.updated_count +
                                        ' pól w ' + result.data.blocks_count + ' blokach.');

                                    var changes = result.data.changes || [];
                                    if (changes.length > 0) {
                                        previewList.innerHTML = '';
                                        changes.forEach(function (c) {
                                            var li = document.createElement('li');
                                            li.style.marginBottom = '8px';
                                            li.innerHTML =
                                                '<strong>' + escHtml(c.field_path) + '</strong><br>' +
                                                '<span style="color:#d63638">Stara: ' +
                                                    escHtml(c.old_value || '(puste)') + '</span><br>' +
                                                '<span style="color:#00a32a">Nowa: ' +
                                                    escHtml(c.new_value) + '</span>';
                                            previewList.appendChild(li);
                                        });
                                        preview.style.display = 'block';
                                    }

                                    setTimeout(function () {
                                        setStatus('ok', 'Zakończono. Odświeżam stronę...');
                                        location.reload();
                                    }, 2000);
                                })
                                .catch(function (err) {
                                    console.error(err);
                                    setStatus('error', 'Błąd: ' + err.message);
                                    btn.disabled = false;
                                });
                        };

                        reader.onerror = function () {
                            setStatus('error', 'Nie udało się odczytać pliku.');
                            btn.disabled = false;
                        };

                        reader.readAsText(file, 'UTF-8');
                    });
                });
                </script>
                <?php
            });
        }

        /* ------------------------------------------------------------------ */
        /*  AJAX — PHP                                                          */
        /* ------------------------------------------------------------------ */

        public function handle_ajax_import() {

            if (!check_ajax_referer(self::NONCE_ACTION, 'nonce', false)) {
                wp_send_json_error(['message' => 'Błąd sesji (nonce).'], 403);
                return;
            }

            $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

            if (!$post_id || !current_user_can('edit_post', $post_id)) {
                wp_send_json_error(['message' => 'Brak uprawnień.'], 403);
                return;
            }

            $post = get_post($post_id);
            if (!$post) {
                wp_send_json_error(['message' => 'Nie znaleziono wpisu.'], 404);
                return;
            }

            $b64 = isset($_POST['csv_rows']) ? stripslashes(trim($_POST['csv_rows'])) : '';
            if ($b64 === '') {
                wp_send_json_error(['message' => 'Brak danych (csv_rows).'], 400);
                return;
            }

            $decoded = base64_decode($b64, true);
            if ($decoded === false) {
                wp_send_json_error(['message' => 'Błąd dekodowania Base64.'], 400);
                return;
            }

            $raw = json_decode($decoded, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($raw) || empty($raw)) {
                wp_send_json_error([
                    'message' => 'Błąd parsowania JSON: ' . json_last_error_msg()
                ], 400);
                return;
            }

            $rows = $this->map_rows($raw);
            if (empty($rows)) {
                wp_send_json_error(['message' => 'CSV nie zawiera prawidłowych wierszy.'], 400);
                return;
            }

            $blocks_data = $this->group_by_blocks($rows);
            $result      = $this->update_post_blocks($post, $blocks_data);

            if ($result['success']) {
                wp_send_json_success([
                    'updated_count' => $result['updated_count'],
                    'blocks_count'  => $result['blocks_count'],
                    'changes'       => $result['changes'],
                ]);
            } else {
                wp_send_json_error(['message' => $result['message']], 500);
            }
        }

        /* ------------------------------------------------------------------ */
        /*  Prywatne metody                                                     */
        /* ------------------------------------------------------------------ */

        private function map_rows(array $raw) {
            $rows = [];
            foreach ($raw as $d) {
                if (!is_array($d) || count($d) < 10) continue;
                if (trim((string)$d[0]) === '' || trim((string)$d[8]) === '') continue;

                $rows[] = [
                    'post_id'     => (string)$d[0],
                    'post_type'   => (string)$d[1],
                    'post_title'  => (string)$d[2],
                    'post_slug'   => (string)$d[3],
                    'post_url'    => (string)$d[4],
                    'block_index' => intval($d[5]),
                    'block_name'  => (string)$d[6],
                    'block_slug'  => (string)$d[7],
                    'field_path'  => (string)$d[8],
                    'value'       => (string)$d[9],
                ];
            }
            return $rows;
        }

        private function group_by_blocks(array $rows) {
            $out = [];
            foreach ($rows as $row) {
                $idx = $row['block_index'];
                if (!isset($out[$idx])) {
                    $out[$idx] = [
                        'block_name' => $row['block_name'],
                        'block_slug' => $row['block_slug'],
                        'fields'     => [],
                    ];
                }
                $out[$idx]['fields'][$row['field_path']] = $row['value'];
            }
            return $out;
        }

        private function update_post_blocks(WP_Post $post, array $blocks_data) {
            $blocks  = parse_blocks($post->post_content);
            $counter = 0;
            $updated = 0;
            $changes = [];

            $blocks = $this->walk_blocks($blocks, $blocks_data, $counter, $updated, $changes);

            if ($updated === 0) {
                return [
                    'success' => false,
                    'message' => 'Nie znaleziono bloków do aktualizacji. '
                               . 'Sprawdź czy importujesz plik z tego samego wpisu.',
                ];
            }

            $new_content = serialize_blocks($blocks);

            /*
             * wp_update_post() → wp_insert_post() → wp_unslash() usuwa backslashe
             * z post_content. To niszczy JSON w komentarzach bloków Gutenberga
             * (\n → n, \t → t itp.).
             *
             * wp_slash() dodaje backslashe z wyprzedzeniem — po wp_unslash()
             * treść wychodzi dokładnie taka jaka ma być.
             */
            $update_result = wp_update_post(
                wp_slash([
                    'ID'           => $post->ID,
                    'post_content' => $new_content,
                ]),
                true
            );

            if (is_wp_error($update_result)) {
                return [
                    'success' => false,
                    'message' => 'Błąd zapisu: ' . $update_result->get_error_message(),
                ];
            }

            return [
                'success'       => true,
                'updated_count' => $updated,
                'blocks_count'  => count($blocks_data),
                'changes'       => array_slice($changes, 0, 10),
            ];
        }

        private function walk_blocks(array $blocks, array $blocks_data, &$counter, &$updated, &$changes) {
            foreach ($blocks as &$block) {
                if (empty($block['blockName'])) continue;

                $idx = $counter++;

                if (isset($blocks_data[$idx])) {
                    if (!isset($block['attrs']))         $block['attrs']         = [];
                    if (!isset($block['attrs']['data'])) $block['attrs']['data'] = [];

                    foreach ($blocks_data[$idx]['fields'] as $path => $value) {
                        $old = $this->get_value($block['attrs']['data'], $path);
                        $this->set_value($block['attrs']['data'], $path, $value);
                        $updated++;

                        if (count($changes) < 10) {
                            $changes[] = [
                                'field_path' => $path,
                                'old_value'  => mb_substr((string)$old,   0, 80),
                                'new_value'  => mb_substr((string)$value, 0, 80),
                            ];
                        }
                    }
                }

                if (!empty($block['innerBlocks'])) {
                    $block['innerBlocks'] = $this->walk_blocks(
                        $block['innerBlocks'],
                        $blocks_data,
                        $counter,
                        $updated,
                        $changes
                    );
                }
            }
            return $blocks;
        }

        private function get_value(array $data, $path) {
            $cur = $data;
            foreach ($this->parse_path($path) as $key) {
                if (!is_array($cur) || !isset($cur[$key])) return '';
                $cur = $cur[$key];
            }
            return is_scalar($cur) ? (string)$cur : '';
        }

        private function set_value(array &$data, $path, $value) {
            $keys = $this->parse_path($path);
            $cur  = &$data;
            $last = count($keys) - 1;
            foreach ($keys as $i => $key) {
                if ($i === $last) {
                    $cur[$key] = $value;
                } else {
                    if (!isset($cur[$key]) || !is_array($cur[$key])) {
                        $cur[$key] = [];
                    }
                    $cur = &$cur[$key];
                }
            }
        }

        private function parse_path($path) {
            $keys = [];
            foreach (explode('.', $path) as $part) {
                if (preg_match('/^([^\[]+)\[(\d+)\]$/', $part, $m)) {
                    $keys[] = $m[1];
                    $keys[] = $m[2];
                } else {
                    $keys[] = $part;
                }
            }
            return $keys;
        }
    }

    new WP_Blocks_CSV_Import();
}