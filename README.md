# Tulio Spotify Widget 🎵

Um widget elegante com estilo **Glassmorphism** para WordPress que exibe em tempo real o que você está ouvindo no Spotify. Projetado com foco em performance, segurança e UX, funcionando via Shortcode.

## 💡 O Projeto

Em vez de depender de plugins genéricos pesados ou expor credenciais no front-end (um risco comum em integrações de terceiros), decidi construir minha própria ferramenta. O objetivo era ter algo performático, que combinasse estritamente com o Design System do meu portfólio, e que tratasse as informações de forma escalável e segura.

### Recursos & Decisões Arquiteturais:

*   **🔒 Segurança Total (Backend API):** A comunicação com a API do Spotify acontece exclusivamente no servidor via PHP. As chaves sensíveis nunca encostam no JavaScript do visitante.
*   **⚡ Alta Performance (Cache & WP Transients):** As requisições à API oficial do Spotify são cacheadas no WordPress por 30 segundos. Isso previne bloqueios de *Rate Limit* e mantém o site instantâneo mesmo com picos de tráfego.
*   **🧩 Painel Nativo Inteligente:** Em vez de *hardcodar* credenciais, o plugin cria uma interface limpa dentro das Configurações do WordPress (`wp-admin`) para gerenciar as chaves livremente.
*   **🧠 Edge Case Handling (Lógica de Histórico):** Tratamento de estado ocioso. Se não houver música tocando agora, o widget inteligentemente degrada para o modo *Last Played*, resgatando a última faixa do seu histórico com mudança de tom e opacidade.
*   **🎨 Glassmorphism & UX Premium:** UI/UX moderna, projetada com CSS moderno (backdrop-filter) perfeitamente responsiva e com micro-interações flutuantes.
*   **📐 Padrões Oficiais WordPress:** O código fonte segue meticulosamente as rigorosas *WP Coding Standards* exigidas no repositório oficial (prefixação de namespaces `tsw_`, enfileiramento `wp_enqueue_script`, sanitização via `sanitize_text_field` e `esc_html_e`).

## 🛠️ Stack Tecnológica

*   **WordPress REST API** (Rotas Customizadas `/wp-json`)
*   **Spotify Web API** (Fluxo de Autorização `refresh_token`)
*   **PHP** (Lógica de Servidor & WP Transients)
*   **Vanilla JS** (Fetch Assíncrono Periódico & Manipulação de DOM)
*   **CSS3** (Glassmorphism, Flexbox, Animações)

## 📦 Como Instalar e Usar

1.  Baixe o repositório como um arquivo `.zip` ou clone a pasta `tulio-spotify-widget` para dentro de `/wp-content/plugins/` na sua instalação WordPress.
2.  Ative o plugin no painel do WordPress.
3.  Vá em **Configurações > Widget Spotify** no seu painel.
4.  Insira seu `Client ID`, `Client Secret` e gere/cole seu `Refresh Token` eterno do Spotify. Salve.
5.  Adicione o shortcode `[spotify_widget]` em qualquer página, post ou rodapé do seu site!

*(Nota: O widget permanece invisível (`display: none`) no front-end caso a API não consiga ler dados ou se o token expirar, para não quebrar a UI do seu site com espaços vazios).*

## 🔒 Aviso de Segurança para Forks/Clones

**Nunca compartilhe publicamente o seu `Client Secret` ou o seu `Refresh Token` em arquivos de repositório**. Eles devem viver única e exclusivamente nos campos de configuração dentro do banco de dados protegidos do seu ambiente CMS.

---

Feito com ☕ e foco arquitetural por **Túlio**.

🔗 **Conecte-se comigo:** [tuliocareli.com](https://tuliocareli.com/)
