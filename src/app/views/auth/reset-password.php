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

    <title>Redefinir senha</title>
</head>
<body>

<h1>Redefinir senha</h1>

<?php if (!empty($error)): ?>
    <p>
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<?php if (!empty($token)): ?>

    <p>
        Digite sua nova senha.
    </p>

    <form
        method="POST"
        action="/reset-password/<?= htmlspecialchars($token) ?>"
    >

        <input
            type="hidden"
            name="_token"
            value="<?= htmlspecialchars(Csrf::token()) ?>"
        >

        <div>
            <label for="password">
                Nova senha
            </label>

            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                autocomplete="new-password"
                required
                autofocus
            >
        </div>

        <div>
            <label for="password_confirmation">
                Confirmar nova senha
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="8"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit">
            Redefinir senha
        </button>

    </form>

<?php endif; ?>

<p>
    <a href="/login">
        Voltar para o login
    </a>
</p>

</body>
</html>
