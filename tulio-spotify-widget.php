<?php
/**
 * Plugin Name: Tulio Spotify Widget
 * Plugin URI: https://tuliocareli.com/
 * Description: Um widget elegante (Glassmorphism) para exibir o que você está ouvindo no Spotify. Totalmente focado nas diretrizes de segurança do WordPress.org.
 * Version: 1.0.0
 * Author: Tulio
 * Author URI: https://tuliocareli.com/
 * Text Domain: tulio-spotify-widget
 * License: GPLv2 or later
 */

// Proteção contra acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

// Definição das constantes principais conectando caminhos e versões do sistema
define('TSW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TSW_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TSW_VERSION', '1.0.0');

/**
 * =========================================================================
 * 1. MÓDULO ADMINISTRATIVO (TELA DE CONFIGURAÇÕES E SALVAMENTO SEGURO)
 * =========================================================================
 */

add_action('admin_menu', 'tsw_create_admin_menu');
function tsw_create_admin_menu()
{
    add_options_page(
        esc_html__('Widget Spotify', 'tulio-spotify-widget'),
        esc_html__('Widget Spotify', 'tulio-spotify-widget'),
        'manage_options',
        'tsw-settings',
        'tsw_settings_page_html'
    );
}

add_action('admin_init', 'tsw_register_settings');
function tsw_register_settings()
{
    // Registra os campos aplicando sanitização severa no salvamento de texto
    register_setting('tsw_options_group', 'tsw_client_id', 'sanitize_text_field');
    register_setting('tsw_options_group', 'tsw_client_secret', 'sanitize_text_field');
    register_setting('tsw_options_group', 'tsw_refresh_token', 'sanitize_text_field');
}

function tsw_settings_page_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $redirect_uri = admin_url('options-general.php?page=tsw-settings');
    $client_id = get_option('tsw_client_id');
    $client_secret = get_option('tsw_client_secret');

    // Fluxo mágico de autorização no próprio admin!
    if (isset($_GET['code']) && empty(get_option('tsw_refresh_token'))) {
        $code = sanitize_text_field($_GET['code']);
        $response = wp_remote_post('https://accounts.spotify.com/api/token', array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode(sanitize_text_field($client_id) . ':' . sanitize_text_field($client_secret)),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'body' => array(
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirect_uri
            ),
            'timeout' => 15
        ));

        if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
            $body = json_decode(wp_remote_retrieve_body($response));
            if (isset($body->refresh_token)) {
                update_option('tsw_refresh_token', sanitize_text_field($body->refresh_token));
                echo '<div class="notice notice-success is-dismissible"><p><strong>🎉 Sucesso Total!</strong> O Refresh Token foi recuperado sozinho e salvo para você. Seu Widget de Música está vivo!</p></div>';
            }
        } else {
            echo '<div class="notice notice-error is-dismissible"><p><strong>⚠️ Erro:</strong> Não foi possível puxar os dados. Tem certeza que você colou a Redirect URI exata lá no painel do Spotify Developer?</p></div>';
        }
    }

    // Mostra as mensagens de sucesso de forma homologada
    settings_errors('tsw_messages');
    ?>
    <div class="wrap" style="max-width: 900px;">
        <h2>
            <?php esc_html_e('🎵 Configurações do Widget Spotify', 'tulio-spotify-widget'); ?>
        </h2>
        <p>
            <?php esc_html_e('A mágica funciona aqui. Diga adeus a códigos difíceis. Nós fazemos o trabalho duro!', 'tulio-spotify-widget'); ?>
        </p>

        <form method="post" action="options.php">
            <?php
            settings_fields('tsw_options_group');
            ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="tsw_client_id">
                            <?php esc_html_e('Spotify Client ID', 'tulio-spotify-widget'); ?>
                        </label></th>
                    <td>
                        <input type="text" id="tsw_client_id" name="tsw_client_id"
                            value="<?php echo esc_attr(get_option('tsw_client_id')); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="tsw_client_secret">
                            <?php esc_html_e('Spotify Client Secret', 'tulio-spotify-widget'); ?>
                        </label></th>
                    <td>
                        <input type="password" id="tsw_client_secret" name="tsw_client_secret"
                            value="<?php echo esc_attr(get_option('tsw_client_secret')); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="tsw_refresh_token" style="color: #646970;">
                            <?php esc_html_e('Refresh Token (Auto-Preenchido)', 'tulio-spotify-widget'); ?>
                        </label></th>
                    <td>
                        <input type="password" id="tsw_refresh_token" name="tsw_refresh_token"
                            value="<?php echo esc_attr(get_option('tsw_refresh_token')); ?>" class="regular-text" readonly
                            style="background: #f0f0f1; border-color: #8c8f94; cursor: not-allowed;" />
                        <p class="description">
                            <?php esc_html_e('Você não precisa digitar nada aqui! Siga os 3 Passos Azuis abaixo que o sistema digita sozinho para você.', 'tulio-spotify-widget'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            <?php submit_button(esc_html__('Salvar Chaves Iniciais', 'tulio-spotify-widget')); ?>
        </form>

        <hr style="margin: 30px 0;">

        <h3><?php esc_html_e('⚡ 3 Passos: Conecte seu Spotify em um clique!', 'tulio-spotify-widget'); ?></h3>

        <ol
            style="background: #fff; padding: 20px 20px 20px 40px; border: 1px solid #ccd0d4; border-radius: 4px; font-size: 14px;">
            <li style="margin-bottom: 20px;"><strong>Crie um App para você:</strong> Acesse o site <a
                    href="https://developer.spotify.com/dashboard" target="_blank">painel do desenvolvedor Spotify</a>,
                clique em <em>Create app</em>. Nas configurações dele (Settings), anote essa exata URL de segurança
                garantida abaixo e cole lá no campo deles de "Redirect URIs":<br>
                <code
                    style="display:block; margin-top:5px; background:#f0f0f1; padding:10px;"><?php echo esc_url($redirect_uri); ?></code>
            </li>

            <li style="margin-bottom: 20px;"><strong>Salvar suas 2 chaves base:</strong> Volte e copie o "Client ID" e o seu
                "Client Secret" da página do Spotify que você acabou de criar. Cole aqui preenchendo as duas primeiras
                caixas brancas acima ⬆️ e clique no botão azul de Salvar.
            </li>

            <li><strong>O Botão Mágico:</strong> Clique no novo botão verde abaixo para autorizar o site. Uma tela do
                próprio Spotify vai abrir. É só clicar em Aceitar que ele volta para cá sozinho, já roubando o token e
                salvando sem você precisar fazer NADA.<br>
                <?php
                if ($client_id && $client_secret) {
                    $auth_url = "https://accounts.spotify.com/authorize?client_id={$client_id}&response_type=code&redirect_uri=" . urlencode($redirect_uri) . "&scope=user-read-currently-playing%20user-read-recently-played";
                    echo '<a href="' . esc_url($auth_url) . '" class="button button-primary" style="margin-top: 15px; background: #1DB954; color: white; border: none; font-size: 15px; padding: 5px 20px; text-decoration: none;">🟢 Logar com meu Spotify</a>';
                } else {
                    echo '<p style="color: #d63638; margin-top: 10px;"><em>(⚠️ O grande Botão Mágico Verde aparecerá aqui sozinho após você completar o "Passo 2" salvando o formulário!)</em></p>';
                }
                ?>
            </li>
        </ol>

        <hr style="margin: 30px 0;">

        <h3>
            <?php esc_html_e('Como exibir o Widget no site:', 'tulio-spotify-widget'); ?>
        </h3>
        <p>
            <?php esc_html_e('Cole o código curto abaixo em qualquer pedaço de texto do seu site, Elementor ou final da página (Footer) e veja a mágica flutuar!', 'tulio-spotify-widget'); ?>
        </p>
        <code style="font-size: 16px; padding: 10px; background: #fff; border: 1px solid #ccc;">[spotify_widget]</code>

    </div>
    <?php
}

/**
 * =========================================================================
 * 2. ENFILEIRAMENTO E SEPARAÇÃO DOS ATIVOS (CSS/JS) (REQUISITO DA ORG)
 * =========================================================================
 */

add_action('wp_enqueue_scripts', 'tsw_enqueue_assets');
function tsw_enqueue_assets()
{
    // Registramos globalmente (caso o widget esteja no rodapé/tema direto ao invés de no the_content), mas só enfileiramos lá dentro via Shortcode por performance
    wp_register_style('tsw-widget-style', TSW_PLUGIN_URL . 'assets/css/widget.css', array(), TSW_VERSION);
    wp_register_script('tsw-widget-script', TSW_PLUGIN_URL . 'assets/js/widget.js', array(), TSW_VERSION, true);
}

/**
 * =========================================================================
 * 3. CONTROLADOR DA API ROTÁRIA (REST API) = BACKEND COMUNICATOR
 * =========================================================================
 */

add_action('rest_api_init', 'tsw_register_api_endpoints');
function tsw_register_api_endpoints()
{
    register_rest_route('tulio-spotify/v1', '/now-playing', array(
        'methods' => 'GET',
        'callback' => 'tsw_get_now_playing',
        'permission_callback' => '__return_true' // Permitido para todo mundo pois é um recurso público frontend
    ));
}

function tsw_get_now_playing($request)
{
    $cache_key = 'tsw_now_playing_cache';
    $cache = get_transient($cache_key);

    // Resposta via Cache para poupar recursos limitados da API do Spotify
    if (false !== $cache) {
        return rest_ensure_response($cache);
    }

    $client_id = get_option('tsw_client_id');
    $client_secret = get_option('tsw_client_secret');
    $refresh_token = get_option('tsw_refresh_token');

    if (empty($client_id) || empty($client_secret) || empty($refresh_token)) {
        return new WP_Error('missing_keys', 'Credenciais não configuradas.', array('status' => 500));
    }

    // Fazemos a requisição via wp_remote_post sanitizando fortemente até os dados lidos do banco nosso próprio banco
    $auth_response = wp_remote_post('https://accounts.spotify.com/api/token', array(
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode(sanitize_text_field($client_id) . ':' . sanitize_text_field($client_secret)),
            'Content-Type' => 'application/x-www-form-urlencoded'
        ),
        'body' => array(
            'grant_type' => 'refresh_token',
            'refresh_token' => sanitize_text_field($refresh_token)
        ),
        'timeout' => 15
    ));

    if (is_wp_error($auth_response) || 200 !== wp_remote_retrieve_response_code($auth_response)) {
        return new WP_Error('auth_failed', 'Falha na comunicação restrita com API do Spotify.', array('status' => 500));
    }

    $auth_body = json_decode(wp_remote_retrieve_body($auth_response));

    if (!isset($auth_body->access_token)) {
        return new WP_Error('invalid_token', 'Integridade do Token violada e inatingível.', array('status' => 500));
    }

    $access_token = sanitize_text_field($auth_body->access_token);

    // Tenta receber info da música sendo tocada agora
    $now_playing_response = wp_remote_get('https://api.spotify.com/v1/me/player/currently-playing', array(
        'headers' => array('Authorization' => 'Bearer ' . $access_token),
        'timeout' => 15
    ));

    $status_code = wp_remote_retrieve_response_code($now_playing_response);
    $track_data = json_decode(wp_remote_retrieve_body($now_playing_response));

    $is_playing = false;
    $track = null;

    if (200 === $status_code && $track_data && isset($track_data->is_playing) && $track_data->is_playing) {
        $is_playing = true;
        if (isset($track_data->item)) {
            $track = $track_data->item;
        }
    } else {
        // Busca a música tocada anteriormente
        $recent_response = wp_remote_get('https://api.spotify.com/v1/me/player/recently-played?limit=1', array(
            'headers' => array('Authorization' => 'Bearer ' . $access_token),
            'timeout' => 15
        ));

        if (200 === wp_remote_retrieve_response_code($recent_response)) {
            $recent_data = json_decode(wp_remote_retrieve_body($recent_response));
            if (!empty($recent_data->items)) {
                $track = $recent_data->items[0]->track;
            }
        }
    }

    if (!$track || !isset($track->name)) {
        $result = array('is_playing' => false, 'error' => 'not_found');
        set_transient($cache_key, $result, 30);
        return rest_ensure_response($result);
    }

    $artist_name = !empty($track->artists) ? $track->artists[0]->name : 'Unknown Artist';
    $song_url = isset($track->external_urls->spotify) ? $track->external_urls->spotify : '#';
    $image_url = !empty($track->album->images) ? $track->album->images[0]->url : '';

    // Sanitiza toda e qualquer saída para o JSON com escape de tags a fim de prever Injections (Regra estrita do WordPress.org)
    $result = array(
        'is_playing' => $is_playing,
        'song_name' => sanitize_text_field($track->name),
        'artist_name' => sanitize_text_field($artist_name),
        'song_url' => esc_url_raw($song_url),
        'image_url' => esc_url_raw($image_url)
    );

    set_transient($cache_key, $result, 30);

    return rest_ensure_response($result);
}

/**
 * =========================================================================
 * 4. RENDERIZAÇÃO NO FRONT-END E VIEWS 
 * =========================================================================
 */

add_shortcode('spotify_widget', 'tsw_render_widget_shortcode');
function tsw_render_widget_shortcode()
{

    // Dispara a impressão exclusiva do CSS e JS da funcionalidade somente quando a chamada Shortcode é acionada (Otimiza Pagespeed e LCP)
    wp_enqueue_style('tsw-widget-style');
    wp_enqueue_script('tsw-widget-script');

    // Passa de forma blindada variáveis seguras do PHP para o frontend global JS
    wp_localize_script('tsw-widget-script', 'tswData', array(
        'apiUrl' => esc_url_raw(rest_url('tulio-spotify/v1/now-playing'))
    ));

    ob_start(); ?>
    <div id="tsw-spotify-widget" class="tsw-container" style="display: none;">
        <img id="tsw-cover" src="" alt="<?php esc_attr_e('Album Cover', 'tulio-spotify-widget'); ?>" class="tsw-cover">

        <div class="tsw-info">
            <span id="tsw-label" class="tsw-label">
                <?php esc_html_e('Listening now', 'tulio-spotify-widget'); ?>
            </span>
            <strong id="tsw-song">
                <?php esc_html_e('Name', 'tulio-spotify-widget'); ?>
            </strong>
            <span id="tsw-artist" class="tsw-artist">
                <?php esc_html_e('Artist', 'tulio-spotify-widget'); ?>
            </span>
        </div>

        <a href="#" id="tsw-play-btn" class="tsw-play-btn" target="_blank" rel="noopener noreferrer"
            aria-label="<?php esc_attr_e('Open on Spotify', 'tulio-spotify-widget'); ?>">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M8 5v14l11-7z" />
            </svg>
        </a>
    </div>
    <?php
    return ob_get_clean();
}
