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
    <title>Recuperar senha</title>
</head>
<body>

<h1>Recuperar senha</h1>

<p>
    Informe o e-mail da sua conta para recuperar sua senha.
</p>

<?php if (!empty($error)): ?>
    <p>
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p>
        <?= htmlspecialchars($success) ?>
    </p>
<?php endif; ?>

<form
    method="POST"
    action="/forgot-password"
>
    <input
        type="hidden"
        name="_token"
        value="<?= htmlspecialchars(Csrf::token()) ?>"
    >

    <div>
        <label for="email">
            E-mail
        </label>

        <input
            type="email"
            id="email"
            name="email"
            autocomplete="email"
            required
            autofocus
        >
    </div>

    <button type="submit">
        Recuperar senha
    </button>
</form>

<p>
    <a href="/login">
        Voltar para o login
    </a>
</p>

</body>
</html>