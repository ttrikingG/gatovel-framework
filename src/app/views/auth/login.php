<?php

use nucleo\auth\protection\Csrf;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<?php if (!empty($error)): ?>

    <p>
        <?= htmlspecialchars($error) ?>
    </p>

<?php endif; ?>

<form
    method="POST"
    action="/login"
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
            required
        >
    </div>

    <div>
        <label for="password">
            Senha
        </label>

        <input
            type="password"
            id="password"
            name="password"
            required
        >
    </div>

    <button type="submit">
        Entrar
    </button>

</form>

</body>
</html>