<?php

$statusCode = $statusCode ?? 500;
$title = $title ?? 'Erro interno';
$message = $message ?? 'Ocorreu um erro inesperado.';
$terminalCommand = $terminalCommand ?? 'gatovel status';
$terminalMessage = $terminalMessage ?? 'internal error';

$assetFile = dirname(__DIR__)
    . '/assets/gatovel-error.png';

$catImage = '';

if (is_file($assetFile)) {

    $imageData = file_get_contents(
        $assetFile
    );

    if ($imageData !== false) {
        $catImage = 'data:image/png;base64,'
            . base64_encode($imageData);
    }
}

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
<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= $escape($statusCode) ?> | Gatovel Framework
    </title>

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

        .error-page {
            width: 100%;
            max-width: 1050px;

            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;

            gap: 55px;
        }

        .cat-area {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cat {
            display: block;

            width: 100%;
            max-width: 480px;
            height: auto;

            filter:
                drop-shadow(
                    0 0 6px
                    rgba(0, 255, 30, 0.35)
                )
                drop-shadow(
                    0 0 25px
                    rgba(0, 255, 30, 0.10)
                );
        }

        .cat-placeholder {
            color: #00ff22;
            font-size: 80px;
        }

        .framework {
            margin-bottom: 20px;

            color: #00ff22;

            font-size: 14px;
            font-weight: bold;

            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .code {
            margin: 0;

            color: #00ff22;

            font-size:
                clamp(
                    100px,
                    15vw,
                    180px
                );

            font-weight: 900;
            line-height: 0.85;

            letter-spacing: -12px;

            text-shadow:
                0 0 5px
                rgba(0, 255, 34, 0.8),
                0 0 20px
                rgba(0, 255, 34, 0.25);
        }

        .title {
            margin: 30px 0 15px;

            font-size: 29px;
            font-weight: bold;
        }

        .message {
            max-width: 520px;

            margin: 0;

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
            line-height: 1.7;
        }

        .terminal .prompt,
        .terminal .error-label {
            color: #00ff22;
        }

        .actions {
            margin-top: 30px;
        }

        .button {
            display: inline-block;

            padding: 13px 22px;

            border: 1px solid #00ff22;
            border-radius: 4px;

            color: #00ff22;
            background: transparent;

            font-family: inherit;
            font-size: 14px;
            font-weight: bold;

            text-decoration: none;

            transition:
                background 0.2s,
                color 0.2s,
                box-shadow 0.2s;
        }

        .button:hover {
            color: #020302;
            background: #00ff22;

            box-shadow:
                0 0 15px
                rgba(0, 255, 34, 0.35);
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

            .error-page {
                grid-template-columns: 1fr;

                gap: 20px;

                text-align: center;
            }

            .cat {
                max-width: 300px;
            }

            .code {
                letter-spacing: -7px;
            }

            .message {
                margin-left: auto;
                margin-right: auto;
            }

            .terminal {
                text-align: left;
            }
        }

    </style>

</head>

<body>

    <main class="error-page">

        <section class="cat-area">

            <?php if ($catImage !== ''): ?>

                <img
                    class="cat"
                    src="<?= $escape($catImage) ?>"
                    alt="Gato pixel art do Gatovel Framework"
                >

            <?php else: ?>

                <div class="cat-placeholder">
                    &gt;_&lt;
                </div>

            <?php endif; ?>

        </section>

        <section>

            <div class="framework">
                Gatovel Framework
            </div>

            <h1 class="code">
                <?= $escape($statusCode) ?>
            </h1>

            <h2 class="title">
                <?= $escape($title) ?>
            </h2>

            <p class="message">
                <?= $escape($message) ?>
            </p>

            <div class="terminal">

                <span class="prompt">
                    $ <?= $escape($terminalCommand) ?>
                </span>

                <br>

                Searching...

                <br>

                <span class="error-label">
                    ERROR:
                </span>

                <?= $escape($terminalMessage) ?>.

            </div>

            <div class="actions">

                <a
                    class="button"
                    href="/"
                >
                    &lt; Voltar ao início
                </a>

            </div>

            <div class="footer">

                <strong>GATOVEL</strong>

                / ERROR <?= $escape($statusCode) ?>

            </div>

        </section>

    </main>

</body>

</html>
