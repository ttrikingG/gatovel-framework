<?php

use nucleo\auth\protection\Csrf;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Configurar MFA</title>
</head>
<body>

<h1>Configurar autenticação em duas etapas</h1>

<?php if (!empty($enabled)): ?>

    <p>
        A autenticação em duas etapas já está ativada.
    </p>

<?php else: ?>

    <p>
        Abra o Google Authenticator e adicione uma nova conta.
    </p>

    <h2>Configuração manual</h2>

    <p>
        <strong>Codinome:</strong>
        Gatovel Framework
    </p>

    <p>
        <strong>Sua chave:</strong>
    </p>

    <p>
        <code>
            <?= htmlspecialchars($secret ?? '') ?>
        </code>
    </p>

    <p>
        No Google Authenticator, selecione
        <strong>Baseado no horário</strong>.
    </p>

    <?php if (!empty($error)): ?>

        <p>
            <?= htmlspecialchars($error) ?>
        </p>

    <?php endif; ?>

    <hr>

    <h2>Confirmar configuração</h2>

    <p>
        Depois de cadastrar a chave no Google Authenticator,
        digite aqui o código de 6 dígitos gerado pelo aplicativo.
    </p>

    <form
        method="POST"
        action="/mfa/setup"
    >

        <input
            type="hidden"
            name="_token"
            value="<?= htmlspecialchars(Csrf::token()) ?>"
        >

        <label for="code">
            Código de autenticação
        </label>

        <input
            type="text"
            id="code"
            name="code"
            inputmode="numeric"
            autocomplete="one-time-code"
            pattern="[0-9]{6}"
            maxlength="6"
            required
            autofocus
        >

        <button type="submit">
            Ativar MFA
        </button>

    </form>

    <hr>

    <form
        method="POST"
        action="/mfa/setup/regenerate"
    >

        <input
            type="hidden"
            name="_token"
            value="<?= htmlspecialchars(Csrf::token()) ?>"
        >

        <button type="submit">
            Gerar outra chave
        </button>

    </form>

<?php endif; ?>

</body>
</html>