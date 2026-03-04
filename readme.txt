=== Tulio Spotify Widget ===
Contributors: tuliocareli
Tags: spotify, widget, music, now playing, glassmorphism
Requires at least: 5.8
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A sleek, glassmorphism-styled WordPress widget that displays your real-time Spotify listening activity via shortcode. Built with 1-Click Auth.

== Description ==

Instead of relying on heavy third-party plugins or exposing frontend credentials (a security risk), this plugin provides a clean, native, and highly performant way to show your website visitors what you are currently listening to on Spotify.

The **Tulio Spotify Widget** was built with a strict focus on WordPress Coding Standards, incredible performance, and zero-friction UX. It uses a modern Glassmorphism UI that automatically floats elegantly on your site using a simple `[spotify_widget]` shortcode.

### Features

*   **1-Click Auth Flow:** Forget complex terminals or manually generating tokens. Connect your Spotify developer app through the native WordPress dashboard with a single "Login with Spotify" button.
*   **Total Security (Server-side API):** Communication with the Spotify API happens strictly via PHP. Your sensitive keys (`Client Secret`, `Refresh Token`) never touch the frontend JavaScript (Zero Exposure).
*   **High Performance (WP Transients):** Spotify API requests are cached via the WordPress database for 30 seconds. This prevents rate-limiting and ensures your site stays lightning fast even with high traffic spikes.
*   **Idle State Handling:** If you aren't listening to music right now, the widget gracefully falls back to a "Last Played" mode, securely fetching your recently played track from your history and changing the UI opacity.
*   **Premium Glassmorphism UX:** Responsive, sleek, and animated CSS (backdrop-filter) that blends easily with modern design systems.

== Installation ==

1. Upload the plugin folder `wp-spotify-now-playing-widget` to your `/wp-content/plugins/` directory, or install the `.zip` file via **Plugins > Add New**.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to **Settings > Widget Spotify** in your WordPress dashboard.
4. Follow the **3-Step Setup** on the screen:
    * Create a free App in the [Spotify Developer Dashboard](https://developer.spotify.com/dashboard).
    * Copy the exact *Redirect URI* provided by the plugin into your Spotify App settings.
    * Paste your `Client ID` and `Client Secret` and click **Save**.
    * Click the green **"Login with my Spotify"** button that magically appears. The plugin will securely handle the OAuth2 handshake and save your lifetime Refresh Token automatically.
5. Place the shortcode `[spotify_widget]` anywhere on your site (pages, posts, or footer widgets).

*(Note: The widget remains invisible via `display: none` if the API fails or you disconnect, preventing broken layouts on your live site).*

== Frequently Asked Questions ==

= Does it slow down my site? =
No. The plugin uses WP Transients to cache responses for 30 seconds on the server. The frontend uses a lightweight, asynchronous vanilla JS fetch that runs in the background.

= Will my Spotify credentials be visible to users? =
Absolutely not. Everything is handled via server-to-server communication (`wp_remote_post`). No keys are exposed to the client browser.

= What if I pause my music? =
The plugin intelligently detects inactivity and pulls your "Recently Played" track instead, changing its status and UI styling to reflect that it's from your history.

== Screenshots ==

1. The elegant floating Glassmorphism widget displaying the current song.
2. The native WordPress settings page showing the 1-Click Auth button.

== Changelog ==

= 1.0.0 =
* Initial release.
* 1-Click OAuth2 Automatic Flow setup implemented.
* Fallback to 'Recently Played' if 'Currently Playing' is inactive.
* Glassmorphism UI and shortcode system built securely.
