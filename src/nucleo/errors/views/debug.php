<?php

if (
    !isset($exception) ||
    !$exception instanceof \Throwable
) {
    throw new \RuntimeException(
        'A página de debug requer uma exceção válida.'
    );
}

$statusCode = $statusCode ?? 500;

$exceptionType = get_class(
    $exception
);

$message = $exception->getMessage();

$file = $exception->getFile();

$line = $exception->getLine();

$trace = $exception->getTraceAsString();

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

$shortFile = $file;

$projectRoot = dirname(
    __DIR__,
    3
);

if (
    str_starts_with(
        $file,
        $projectRoot
    )
) {
    $shortFile = ltrim(
        substr(
            $file,
            strlen($projectRoot)
        ),
        DIRECTORY_SEPARATOR
    );
}
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
        <?= $escape($statusCode) ?> Exception | Gatovel Framework
    </title>

    <style nonce="<?= $cspNonce ?? '' ?>">

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

            background:
                radial-gradient(
                    circle at top right,
                    #111811 0%,
                    #070907 35%,
                    #020302 75%
                );

            color: #f2f2f2;

            font-family:
                "Courier New",
                Courier,
                monospace;
        }

        .debug-page {
            width: 100%;
            max-width: 1400px;

            margin: 0 auto;

            padding:
                45px
                35px
                60px;
        }

        /*
        |--------------------------------------------------------------------------
        | Cabeçalho
        |--------------------------------------------------------------------------
        */

        .top {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 30px;

            margin-bottom: 42px;
        }

        .brand {
            color: #00ff22;

            font-size: 14px;
            font-weight: bold;

            letter-spacing: 4px;
            text-transform: uppercase;

            text-shadow:
                0 0 10px
                rgba(0, 255, 34, 0.25);
        }

        .mode {
            padding:
                7px
                12px;

            border:
                1px solid
                #245c2b;

            border-radius: 4px;

            color: #00ff22;

            background:
                rgba(
                    0,
                    255,
                    34,
                    0.04
                );

            font-size: 12px;
            font-weight: bold;

            letter-spacing: 1px;
        }

        /*
        |--------------------------------------------------------------------------
        | Resumo da exceção
        |--------------------------------------------------------------------------
        */

        .summary {
            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                230px;

            gap: 45px;

            align-items: center;

            margin-bottom: 38px;
        }

        .status {
            display: inline-block;

            margin-bottom: 20px;

            padding:
                7px
                11px;

            border:
                1px solid
                #7c2525;

            border-radius: 4px;

            color: #ff5252;

            background:
                rgba(
                    255,
                    50,
                    50,
                    0.07
                );

            font-size: 13px;
            font-weight: bold;

            letter-spacing: 1px;
        }

        .exception {
            margin: 0;

            color: #ff5252;

            font-size:
                clamp(
                    28px,
                    4vw,
                    52px
                );

            line-height: 1.15;

            overflow-wrap: anywhere;

            text-shadow:
                0 0 15px
                rgba(
                    255,
                    50,
                    50,
                    0.12
                );
        }

        .message {
            margin:
                22px
                0
                0;

            padding-left: 17px;

            border-left:
                3px solid
                #ff5252;

            color: #d7d7d7;

            font-size: 18px;
            line-height: 1.7;

            overflow-wrap: anywhere;
        }

        /*
        |--------------------------------------------------------------------------
        | Gatovel
        |--------------------------------------------------------------------------
        */

        .cat-area {
            display: flex;

            align-items: center;
            justify-content: center;
        }

        .cat {
            display: block;

            width: 100%;
            max-width: 230px;

            filter:
                drop-shadow(
                    0 0 7px
                    rgba(
                        0,
                        255,
                        30,
                        0.30
                    )
                )
                drop-shadow(
                    0 0 25px
                    rgba(
                        0,
                        255,
                        30,
                        0.08
                    )
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Painéis
        |--------------------------------------------------------------------------
        */

        .panel {
            margin-top: 22px;

            border:
                1px solid
                #183d1c;

            border-radius: 6px;

            background:
                rgba(
                    0,
                    0,
                    0,
                    0.48
                );

            overflow: hidden;
        }

        .panel-title {
            padding:
                13px
                17px;

            border-bottom:
                1px solid
                #183d1c;

            color: #00ff22;

            background:
                rgba(
                    0,
                    255,
                    34,
                    0.025
                );

            font-size: 12px;
            font-weight: bold;

            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | Local da exceção
        |--------------------------------------------------------------------------
        */

        .location {
            padding: 20px;

            color: #c3c9c3;

            font-size: 15px;
            line-height: 1.9;

            overflow-wrap: anywhere;
        }

        .location-row {
            display: flex;

            gap: 10px;
        }

        .location-label {
            min-width: 55px;

            color: #7f887f;
        }

        .location-value {
            color: #d7ddd7;
        }

        .line-number {
            color: #ff5252;

            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | Stack trace
        |--------------------------------------------------------------------------
        */

        .trace {
            margin: 0;

            padding: 22px;

            max-height: 520px;

            overflow: auto;

            color: #969f97;

            font-family:
                "Courier New",
                Courier,
                monospace;

            font-size: 13px;
            line-height: 1.75;

            white-space: pre-wrap;
            overflow-wrap: anywhere;

            scrollbar-color:
                #245c2b
                #050705;
        }

        /*
        |--------------------------------------------------------------------------
        | Terminal
        |--------------------------------------------------------------------------
        */

        .terminal {
            margin-top: 22px;

            padding:
                15px
                18px;

            border:
                1px solid
                #251515;

            border-radius: 6px;

            background:
                rgba(
                    15,
                    0,
                    0,
                    0.30
                );

            color: #8d958e;

            font-size: 13px;
            line-height: 1.7;
        }

        .terminal-prompt {
            color: #00ff22;
        }

        .terminal-error {
            color: #ff5252;

            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | Rodapé
        |--------------------------------------------------------------------------
        */

        .footer {
            margin-top: 35px;

            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 20px;

            color: #505850;

            font-size: 12px;
            letter-spacing: 2px;
        }

        .footer strong {
            color: #687168;
        }

        .environment {
            color: #00ff22;
        }

        /*
        |--------------------------------------------------------------------------
        | Responsivo
        |--------------------------------------------------------------------------
        */

        @media (
            max-width: 760px
        ) {

            .debug-page {
                padding:
                    30px
                    20px
                    45px;
            }

            .top {
                align-items:
                    flex-start;

                flex-direction:
                    column;

                margin-bottom: 30px;
            }

            .summary {
                grid-template-columns:
                    1fr;

                gap: 25px;
            }

            .cat {
                max-width: 170px;
            }

            .location-row {
                display: block;

                margin-bottom: 8px;
            }

            .location-label {
                display: block;
            }

            .footer {
                align-items:
                    flex-start;

                flex-direction:
                    column;
            }

        }

    </style>

</head>

<body>

    <main class="debug-page">

        <header class="top">

            <div class="brand">
                Gatovel Framework
            </div>

            <div class="mode">
                DEBUG MODE
            </div>

        </header>

        <section class="summary">

            <div>

                <div class="status">
                    HTTP <?= $escape($statusCode) ?>
                </div>

                <h1 class="exception">
                    <?= $escape($exceptionType) ?>
                </h1>

                <p class="message">
                    <?= $escape($message) ?>
                </p>

            </div>

            <?php if ($catImage !== ''): ?>

                <div class="cat-area">

                    <img
                        class="cat"
                        src="<?= $escape($catImage) ?>"
                        alt="Gatovel Framework"
                    >

                </div>

            <?php endif; ?>

        </section>

        <section class="panel">

            <div class="panel-title">
                Exception Location
            </div>

            <div class="location">

                <div class="location-row">

                    <span class="location-label">
                        File:
                    </span>

                    <span class="location-value">
                        <?= $escape($shortFile) ?>
                    </span>

                </div>

                <div class="location-row">

                    <span class="location-label">
                        Line:
                    </span>

                    <span class="line-number">
                        <?= $escape($line) ?>
                    </span>

                </div>

            </div>

        </section>

        <section class="panel">

            <div class="panel-title">
                Stack Trace
            </div>

            <pre class="trace"><?= $escape($trace) ?></pre>

        </section>

        <div class="terminal">

            <span class="terminal-prompt">
                $ gatovel debug exception
            </span>

            <br>

            Inspecting throwable...

            <br>

            <span class="terminal-error">
                EXCEPTION:
            </span>

            <?= $escape($exceptionType) ?>

        </div>

        <footer class="footer">

            <div>

                <strong>GATOVEL</strong>

                / DEVELOPMENT EXCEPTION PAGE

            </div>

            <div class="environment">
                APP_DEBUG=true
            </div>

        </footer>

    </main>

</body>

</html>