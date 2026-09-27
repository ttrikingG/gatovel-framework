<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Reenviar verificação</title>
</head>
<body>

<h1>Reenviar verificação de e-mail</h1>

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
    action="/resend-verification"
>
    <div>
        <label for="email">
            E-mail
        </label>

        <input
            type="email"
            id="email"
            name="email"
            required
        >
    </div>

    <br>

    <input
        type="hidden"
        name="_token"
        value="<?= htmlspecialchars(
            \nucleo\auth\protection\Csrf::token()
        ) ?>"
    >

    <button type="submit">
        Reenviar e-mail
    </button>
</form>

<p>
    <a href="/login">
        Voltar para o login
    </a>
</p>

</body>
</html>