# 🟢 Neon Cyberpunk Theme

Tema visual **cyberpunk/retro-arcade** em CSS puro, com paleta neon verde-limão e roxo, tipografia `Orbitron`, efeitos de *glow*, *flicker*, *scanlines* e elementos flutuantes. Ideal para landing pages, hero sections e projetos com estética de jogos retrô / terminal hacker.

---

## 📑 Menu

- [Preview do tema](#-preview-do-tema)
- [Estrutura do arquivo](#-estrutura-do-arquivo)
- [Instalação](#-instalação)
- [Variáveis globais (design tokens)](#-variáveis-globais-design-tokens)
- [Componentes](#-componentes)
  - [Hero Background](#hero-background)
  - [Hero Content](#hero-content)
  - [Botão](#botão)
  - [Orbes animados](#orbes-animados)
  - [Scanlines](#scanlines)
  - [Pixels decorativos](#pixels-decorativos)
- [Animações](#-animações)
- [Responsividade](#-responsividade)
- [Como customizar as cores](#-como-customizar-as-cores)
- [Exemplo de uso (HTML)](#-exemplo-de-uso-html)
- [Licença](#-licença)

---

## 🎨 Preview do tema

| Elemento | Cor | Uso |
|---|---|---|
| 🟩 Verde neon (`--color-primary`) | `#00ff00` | Títulos, bordas, glow principal |
| 🟢 Verde claro (`--color-secondary`) | `#32ff32` | Variação do glow no hover/flicker |
| 🟣 Roxo (`--color-accent`) | `#8a2be2` | Gradientes, sombras, contraste |
| 🟪 Roxo claro (`--color-accent-light`) | `#9932cc` | Texto secundário, hover |
| ⬛ Fundo (`--color-bg`) | `#0a0a0a` | Base escura do site |

Fonte: **Orbitron** (Google Fonts), pesos 400 / 700 / 900.

---

## 📁 Estrutura do arquivo

```
theme.css
├── Reset básico
├── Variáveis globais (:root)
├── Estilos base (html, body, img, video)
├── Hero full-screen (background)
├── Hero central (container + content)
├── Botão (.button)
├── Orbes animados (.orb1, .orb2)
├── Scanlines (.scanline)
├── Pixels decorativos (.pixel-decorations)
└── Media queries (responsividade)
```

---

## 🚀 Instalação

1. Copie o arquivo `theme.css` para a pasta do seu projeto.
2. Importe no seu HTML:

```html
<link rel="stylesheet" href="theme.css">
```

3. Monte a estrutura HTML do hero (veja [Exemplo de uso](#-exemplo-de-uso-html)).

> A fonte Orbitron já é carregada automaticamente via `@import` no topo do CSS — não precisa adicionar no `<head>`.

---

## 🧩 Variáveis globais (design tokens)

Todas as cores e tokens ficam centralizados em `:root`, facilitando a customização:

```css
:root {
  --font-base: 'Orbitron', monospace;
  --color-bg: #0a0a0a;
  --color-text: #66ff66;
  --color-primary: #00ff00;
  --color-secondary: #32ff32;
  --color-accent: #8a2be2;
  --color-accent-light: #9932cc;
  --color-dark: #003300;
  --color-dark-light: #004400;
  --color-purple-dark: #4b0082;
  --radius: 0.5rem;
  --shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}
```

---

## 🧱 Componentes

### Hero Background
`.hero-background` cria um fundo fixo em tela cheia com gradiente diagonal (verde → roxo → verde). `.hero-bg-img` permite sobrepor uma imagem com filtro de matiz roxo/verde e opacidade reduzida.

### Hero Content
`.hero-container` centraliza o conteúdo vertical e horizontalmente. `.hero-content` é o "card" principal: fundo preto semitransparente, borda verde neon e múltiplas camadas de `box-shadow` para o efeito de brilho.

- `h1`: texto verde neon com *text-shadow* em camadas + animação `limeFlicker` (efeito de tremulação de neon).
- `p`: texto roxo claro com brilho suave.

### Botão
`.button` usa gradiente verde→roxo, borda neon e uma animação de "brilho passando" (`::before`) ativada no `:hover`. Também tem efeito de escala no hover/active.

### Orbes animados
`.orb1` e `.orb2` são círculos grandes e desfocados (via opacidade baixa) que flutuam suavemente pelo fundo (`floatOrb`), dando profundidade à cena.

### Scanlines
`.scanline` simula uma linha de varredura de monitor CRT, atravessando a tela verticalmente em loop (`scanline`).

### Pixels decorativos
`.pixel-decorations::before/::after` são dois quadrados neon (verde e roxo) que flutuam com leve escala (`pixelFloat`), reforçando a estética *pixel art*.

---

## 🎞️ Animações

| Nome | Aplicada em | Efeito |
|---|---|---|
| `limeFlicker` | `.hero-content h1` | Tremulação do brilho neon do título |
| `floatOrb` | `.orb1`, `.orb2` | Flutuação suave dos orbes |
| `scanline` | `.scanline` | Linha de varredura descendo pela tela |
| `pixelFloat` | `.pixel-decorations` | Flutuação + escala dos pixels decorativos |

---

## 📱 Responsividade

Em telas até `768px`:
- `.hero-content` reduz margens e padding.
- `.button` fica menor (padding e `letter-spacing` reduzidos).

---

## 🛠️ Como customizar as cores

Basta alterar os valores em `:root`. Exemplo — trocar para tema **azul/rosa**:

```css
:root {
  --color-primary: #00e5ff;
  --color-secondary: #66f0ff;
  --color-accent: #ff2ee6;
  --color-accent-light: #ff66f0;
}
```

Como todas as sombras e gradientes referenciam essas variáveis, o tema inteiro se adapta automaticamente.

---

## 💻 Exemplo de uso (HTML)

```html
<body>
  <div class="hero-background">
    <img class="hero-bg-img" src="sua-imagem.jpg" alt="">
  </div>

  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="scanline"></div>
  <div class="pixel-decorations"></div>

  <section class="hero-container">
    <div class="hero-content">
      <h1>Seu Título Aqui</h1>
      <p>Sua descrição ou slogan aqui.</p>
      <a href="#" class="button">Chamada para Ação</a>
    </div>
  </section>
</body>
```

---

## 📄 Licença

Sinta-se livre para usar, modificar e distribuir este tema no seu projeto. Recomenda-se dar crédito caso ele seja publicado publicamente.
