<div align="center">

```
 ███▄    █ ▓█████  ▒█████   ███▄    █
 ██ ▀█   █ ▓█   ▀ ▒██▒  ██▒ ██ ▀█   █
▓██  ▀█ ██▒▒███   ▒██░  ██▒▓██  ▀█ ██▒
▓██▒  ▐▌██▒▒▓█  ▄ ▒██   ██░▓██▒  ▐▌██▒
▒██░   ▓██░░▒████▒░ ████▓▒░▒██░   ▓██░
░ ▒░   ▒ ▒ ░░ ▒░ ░░ ▒░▒░▒░ ░ ▒░   ▒ ▒
░ ░░   ░ ▒░ ░ ░  ░  ░ ▒ ▒░ ░ ░░   ░ ▒░
   ░   ░ ░    ░   ░ ░ ░ ▒     ░   ░ ░
         ░    ░  ░    ░ ░           ░
```

### ⚡ CYBERPUNK NEON THEME ⚡

*Tema visual retrô-arcade em CSS puro — verde-limão, roxo e glow neon*

[![Made with](https://img.shields.io/badge/made%20with-CSS3-00ff00?style=for-the-badge&logo=css3&logoColor=black&labelColor=0a0a0a)](.)
[![Font](https://img.shields.io/badge/font-Orbitron-8a2be2?style=for-the-badge&logoColor=white&labelColor=0a0a0a)](.)
[![Status](https://img.shields.io/badge/status-online-32ff32?style=for-the-badge&labelColor=0a0a0a)](.)
[![License](https://img.shields.io/badge/license-MIT-9932cc?style=for-the-badge&labelColor=0a0a0a)](.)

</div>

<br>

<div align="center">

## ▓▒░ MENU ░▒▓

**[ [🎨 Preview](#-preview) ]&nbsp; [ [📦 Instalação](#-instalação) ]&nbsp; [ [🧬 Variáveis](#-variáveis-de-cor) ]&nbsp; [ [🧩 Componentes](#-componentes) ]&nbsp; [ [🎞️ Animações](#-animações) ]&nbsp; [ [📱 Responsivo](#-responsividade) ]&nbsp; [ [🛠️ Customizar](#-customizar) ]&nbsp; [ [💻 Exemplo](#-exemplo-de-uso) ] ]**

</div>

<br>

---

## 🎨 PREVIEW

<div align="center">

| 🟩 `#00ff00` | 🟢 `#32ff32` | 🟣 `#8a2be2` | 🟪 `#9932cc` | ⬛ `#0a0a0a` |
|:---:|:---:|:---:|:---:|:---:|
| `--color-primary` | `--color-secondary` | `--color-accent` | `--color-accent-light` | `--color-bg` |
| Títulos / bordas | Glow hover / flicker | Gradientes / sombra | Texto secundário | Fundo base |

</div>

> 🖥️ Estética de **terminal hacker + arcade retrô**: fundo escuro, texto verde neon pulsante, contorno roxo, *scanlines* cruzando a tela e pixels flutuantes ao fundo.

---

## 📦 INSTALAÇÃO

```bash
# 1. copie o arquivo do tema
cp theme.css seu-projeto/

# 2. importe no HTML
```

```html
<link rel="stylesheet" href="theme.css">
```

> ⚙️ A fonte **Orbitron** já é importada automaticamente via `@import` no topo do CSS.

---

## 🧬 VARIÁVEIS DE COR

```css
:root {
  --font-base: 'Orbitron', monospace;

  --color-bg: #0a0a0a;              /* ⬛ fundo */
  --color-text: #66ff66;            /* 🟢 texto padrão */
  --color-primary: #00ff00;         /* 🟩 verde neon principal */
  --color-secondary: #32ff32;       /* 🟢 verde claro (flicker) */
  --color-accent: #8a2be2;          /* 🟣 roxo principal */
  --color-accent-light: #9932cc;    /* 🟪 roxo claro */
  --color-dark: #003300;            /* sombra verde escura */
  --color-dark-light: #004400;      /* sombra verde clara */
  --color-purple-dark: #4b0082;     /* sombra roxa escura */

  --radius: 0.5rem;
  --shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}
```

---

## 🧩 COMPONENTES

<table>
<tr><td width="180">🖼️ <b>Hero Background</b></td><td>Fundo <code>fixed</code> full-screen com gradiente diagonal verde → roxo → verde. Imagem opcional com filtro de matiz roxo/verde.</td></tr>
<tr><td>🃏 <b>Hero Content</b></td><td>Card central com fundo preto translúcido, borda neon verde e camadas de <code>box-shadow</code> pra dar o brilho. Título com <code>text-shadow</code> em camadas + animação <code>limeFlicker</code>.</td></tr>
<tr><td>🔘 <b>Botão</b></td><td>Gradiente verde→roxo, borda neon, brilho deslizante no <code>:hover</code> (<code>::before</code>) e leve escala ao clicar.</td></tr>
<tr><td>🌕 <b>Orbes</b></td><td><code>.orb1</code> / <code>.orb2</code>: círculos difusos flutuando ao fundo (<code>floatOrb</code>), dão profundidade.</td></tr>
<tr><td>📺 <b>Scanline</b></td><td>Linha de varredura estilo CRT descendo pela tela em loop.</td></tr>
<tr><td>👾 <b>Pixels</b></td><td>Dois quadrados neon (verde/roxo) flutuando com leve escala — reforça o clima <i>pixel art</i>.</td></tr>
</table>

---

## 🎞️ ANIMAÇÕES

| Keyframe | Onde atua | O que faz |
|---|---|---|
| `limeFlicker` | `.hero-content h1` | Tremulação do brilho neon do título |
| `floatOrb` | `.orb1` `.orb2` | Flutuação suave dos orbes de fundo |
| `scanline` | `.scanline` | Linha de varredura CRT descendo |
| `pixelFloat` | `.pixel-decorations` | Flutuação + escala dos pixels |

---

## 📱 RESPONSIVIDADE

```css
@media (max-width: 768px) {
  /* .hero-content → menos padding/margem */
  /* .button → menor, letter-spacing reduzido */
}
```

---

## 🛠️ CUSTOMIZAR

Troque as variáveis em `:root` e o tema inteiro se adapta (tudo referencia elas). Exemplo — versão **azul/rosa**:

```css
:root {
  --color-primary: #00e5ff;
  --color-secondary: #66f0ff;
  --color-accent: #ff2ee6;
  --color-accent-light: #ff66f0;
}
```

---

## 💻 EXEMPLO DE USO

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

<div align="center">

### ░▒▓ FEITO COM ⚡ NEON POWER ▓▒░

[![Green](https://img.shields.io/badge/-%2300ff00-00ff00?style=flat-square)](.)
[![Purple](https://img.shields.io/badge/-%238a2be2-8a2be2?style=flat-square)](.)
[![Dark](https://img.shields.io/badge/-%230a0a0a-0a0a0a?style=flat-square)](.)

*Licença MIT — use, modifique e distribua livremente.*

</div>
