# Tulio Spotify Widget 🎵

Um widget elegante para WordPress que exibe em tempo real o que você está ouvindo no Spotify. Projetado com foco absoluto em performance, segurança e uma **UX sem atritos (1-Click Auth)**.

## 💡 O Projeto

Em vez de depender de plugins de terceiros, decidi construir minha própria ferramenta. O objetivo era ter algo performático, que combinasse estritamente com o estilo do meu portfólio, e que tratasse as informações de forma escalável e segura.

Nesta versão mais recente, **eliminei a necessidade de qualquer uso de Terminal ou comandos complexos** para conectar o Spotify. O plugin agora lida com todo o fluxo de OAuth2 nativamente dentro do painel do WordPress.

### Recursos & Decisões Arquiteturais:

*   **⚡ 1-Click Auth Flow:** O fluxo de autenticação foi totalmente reimaginado para UX. Você conecta o Spotify clicando em um botão verde no painel do WP. O plugin intercepta o redirecionamento, faz o handshake encriptado com a API e salva o *Refresh Token* eterno em banco, sem você copiar ou rodar códigos.
*   **🔒 Segurança Total (Backend API):** A comunicação com a API do Spotify acontece exclusivamente no servidor via PHP. As chaves sensíveis nunca encostam no JavaScript do visitante (Zero Exposure).
*   **🚀 Alta Performance (Cache & WP Transients):** As requisições à API oficial do Spotify são cacheadas no WordPress por 30 segundos. Isso previne bloqueios de *Rate Limit* e mantém o site instantâneo mesmo com milhares de acessos simultâneos.
*   **🧠 Edge Case Handling (Lógica de Histórico):** Tratamento de estado ocioso. Se não houver música tocando agora, o widget inteligentemente degrada para o modo *Last Played*, resgatando a última faixa do seu histórico com mudança de tom e opacidade.
*   **🎨 Glassmorphism & UX Premium:** UI/UX moderna, projetada com CSS moderno (backdrop-filter) perfeitamente responsiva e com micro-interações.
*   **📐 Padrões Oficiais WordPress:** O código fonte segue meticulosamente as rigorosas *WP Coding Standards* exigidas no repositório oficial (prefixação de namespaces, enfileiramento correto, sanitização via `sanitize_text_field` e `esc_html_e`).

## 🛠️ Stack Tecnológica

*   **WordPress REST API** & Options API
*   **Spotify Web API** (Fluxo automatizado OAuth2)
*   **PHP** (cURL, WP Transients)
*   **Vanilla JS** (Fetch Assíncrono Periódico)
*   **CSS3** (Glassmorphism, Variáveis, Animações)

## 📦 Como Instalar e Usar (Em 2 Minutos)

1.  Baixe este repositório como `.zip` e faça o upload em **Plugins > Adicionar Novo** no seu WordPress, ou extraia a pasta `wp-spotify-now-playing-widget` dentro de `/wp-content/plugins/`.
2.  Ative o plugin.
3.  No menu esquerdo do WordPress, vá em **Configurações > Widget Spotify**.
4.  Siga o **Passo a Passo de 3 Etapas** direto na tela do plugin:
    *   Crie seu App gratuitamente no [Spotify Developer Dashboard](https://developer.spotify.com/dashboard).
    *   Cole lá a *Redirect URI* exata e blindada que o painel do plugin gerou para você.
    *   Cole seu `Client ID` e `Client Secret` baixados de lá, e clique em **Salvar**.
    *   O grande **botão verde "Logar com meu Spotify"** vai aparecer mágicamente. Clique nele, dê "Aceitar" na tela do Spotify e pronto. Seu Token Eterno foi roubado com sucesso! 😂
5.  Adicione o shortcode `[spotify_widget]` em qualquer página, post, widget de texto ou rodapé (Footer) do seu Elementor/Gutenberg!

*(Nota: O widget permanece invisível (`display: none`) no front-end caso a API não consiga ler dados ou se a internet falhar, para não quebrar o layout do seu site de forma alguma).*

---

Feito com ☕ por **Túlio Careli** e Antigravity.

🔗 **Conecte-se comigo:** [tuliocareli.com](https://tuliocareli.com/)
