<?php

$title = $title ?? 'Gatovel Framework';
$message = $message ?? 'Gatovel Framework is running.';

$escape = static function (
    string|int $value
): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
};
?>

<style>

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        min-height: 100%;
    }

    body {
        min-height: 100vh;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 30px;

        background:
            radial-gradient(
                circle at center,
                #101710 0%,
                #070907 45%,
                #020302 100%
            );

        color: #f5f5f5;

        font-family:
            "Courier New",
            Courier,
            monospace;
    }

    .welcome-page {
        width: 100%;
        max-width: 1050px;

        display: grid;
        grid-template-columns: 0.9fr 1.1fr;
        align-items: center;

        gap: 70px;
    }

    .brand-area {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gatovel-logo {
        display: block;

        width: 100%;
        max-width: 480px;
        height: auto;

        object-fit: contain;

        filter:
            drop-shadow(
                0 0 6px
                rgba(0, 255, 34, 0.35)
            )
            drop-shadow(
                0 0 25px
                rgba(0, 255, 34, 0.10)
            );
    }

    .framework {
        margin-bottom: 20px;

        color: #00ff22;

        font-size: 14px;
        font-weight: bold;

        letter-spacing: 4px;
        text-transform: uppercase;
    }

    .title {
        margin: 0;

        font-size:
            clamp(
                38px,
                6vw,
                64px
            );

        font-weight: 900;
        line-height: 1.05;

        letter-spacing: -3px;
    }

    .title span {
        color: #00ff22;

        text-shadow:
            0 0 5px
            rgba(0, 255, 34, 0.6),
            0 0 20px
            rgba(0, 255, 34, 0.15);
    }

    .message {
        max-width: 560px;

        margin: 25px 0 0;

        color: #a6ada6;

        font-size: 17px;
        line-height: 1.7;
    }

    .terminal {
        margin-top: 30px;

        padding: 18px 20px;

        border: 1px solid #183d1c;
        border-radius: 5px;

        background:
            rgba(0, 0, 0, 0.45);

        color: #8c968d;

        font-size: 14px;
        line-height: 1.8;
    }

    .terminal .prompt,
    .terminal .success {
        color: #00ff22;
    }

    .status {
        display: flex;
        flex-wrap: wrap;

        gap: 12px;

        margin-top: 30px;
    }

    .status-item {
        padding: 9px 13px;

        border: 1px solid #183d1c;
        border-radius: 4px;

        background:
            rgba(0, 255, 34, 0.03);

        color: #8c968d;

        font-size: 12px;
        letter-spacing: 1px;
    }

    .status-item strong {
        color: #00ff22;
    }

    .footer {
        margin-top: 45px;

        color: #4f574f;

        font-size: 12px;
        letter-spacing: 2px;
    }

    .footer strong {
        color: #697269;
    }

    @media (max-width: 800px) {

        body {
            padding: 30px 20px;
        }

        .welcome-page {
            grid-template-columns: 1fr;

            gap: 45px;

            text-align: center;
        }

        .gatovel-logo {
            max-width: 360px;
        }

        .message {
            margin-left: auto;
            margin-right: auto;
        }

        .terminal {
            text-align: left;
        }

        .status {
            justify-content: center;
        }
    }

</style>

<main class="welcome-page">

    <section class="brand-area">

        <img
            class="gatovel-logo"
            src="/assets/images/logo.png"
            alt="Gatovel Framework"
        >

    </section>

    <section>

        <div class="framework">
            Gatovel Framework
        </div>

        <h1 class="title">

            Build something

            <span>
                amazing.
            </span>

        </h1>

        <p class="message">
            <?= $escape($message) ?>
            Seu ambiente está pronto para começar
            a desenvolver com o Gatovel.
        </p>

        <div class="terminal">

            <span class="prompt">
                $ ./gatovel
            </span>

            <br>

            Initializing framework...

            <br>

            <span class="success">
                READY:
            </span>

            application is running.

        </div>

        <div class="status">

            <div class="status-item">
                <strong>✓</strong>
                APPLICATION
            </div>

            <div class="status-item">
                <strong>✓</strong>
                ROUTING
            </div>

            <div class="status-item">
                <strong>✓</strong>
                ENVIRONMENT
            </div>

        </div>

        <div class="footer">

            <strong>GATOVEL</strong>

            / PHP FRAMEWORK

        </div>

    </section>

</main>
