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

    <title>Verificação MFA</title>
</head>
<body>

<h1>Verificação em duas etapas</h1>

<p>
    Digite o código de 6 dígitos exibido no seu aplicativo
    autenticador.
</p>

<?php if (!empty($error)): ?>

    <p>
        <?= htmlspecialchars($error) ?>
    </p>

<?php endif; ?>

<form
    method="POST"
    action="/mfa"
>

    <input
        type="hidden"
        name="_token"
        value="<?= htmlspecialchars(Csrf::token()) ?>"
    >

    <div>
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
    </div>

    <button type="submit">
        Verificar
    </button>

</form>

</body>
</html>