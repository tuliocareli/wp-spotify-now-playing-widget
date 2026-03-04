/**
 * Script for Tulio Spotify Widget
 */
(function() {
    'use strict'; // Regra global estrita de JS no WP

    document.addEventListener('DOMContentLoaded', function() {
        const widget = document.getElementById('tsw-spotify-widget');
        
        // Finaliza se o shortcode não estiver impresso na DOM (Performance Check)
        if (!widget) {
            return;
        }

        const cover  = document.getElementById('tsw-cover');
        const song   = document.getElementById('tsw-song');
        const artist = document.getElementById('tsw-artist');
        const link   = document.getElementById('tsw-play-btn');
        const label  = document.getElementById('tsw-label');

        // Recebendo a url da API blindada enviada por wp_localize_script a partir do functions
        const apiUrl = (typeof tswData !== 'undefined' && tswData.apiUrl) ? tswData.apiUrl : '/wp-json/tulio-spotify/v1/now-playing';

        async function fetchNowPlaying() {
            try {
                const res = await fetch(apiUrl);
                if (!res.ok) {
                    throw new Error('WP API Server is unreachable');
                }
                
                const data = await res.json();

                if (data && data.song_name) {
                    // Update DOM (Isolado e Seguro)
                    cover.src           = data.image_url;
                    song.textContent    = data.song_name;
                    artist.textContent  = data.artist_name;
                    link.href           = data.song_url;
                    
                    if (data.is_playing) {
                        label.textContent = 'LISTENING NOW';
                        label.style.color = '#1DB954';
                        cover.style.filter = 'grayscale(0%)';
                    } else {
                        label.textContent = 'LAST PLAYED';
                        label.style.color = 'rgba(255, 255, 255, 0.5)';
                        cover.style.filter = 'grayscale(60%)'; 
                    }

                    // Reveal do widget
                    widget.style.display = 'flex';
                } else {
                    widget.style.display = 'none';
                }
            } catch (e) {
                console.error('Tulio Spotify Widget Falhou Internamente: ', e);
            }
        }

        fetchNowPlaying();
        setInterval(fetchNowPlaying, 15000);
    });
})();
