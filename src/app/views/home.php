<?php

use nucleo\auth\protection\Csrf;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>

<h1><?= htmlspecialchars($title) ?></h1>

<p>
    <?= htmlspecialchars($message) ?>
</p>

<?php if ($user !== null): ?>

    <p>
        Usuário:
        <?= htmlspecialchars($user->name) ?>
    </p>

    <p>
        E-mail:
        <?= htmlspecialchars($user->email) ?>
    </p>

<?php endif; ?>

<form
    method="POST"
    action="/logout"
>

    <input
        type="hidden"
        name="_token"
        value="<?= htmlspecialchars(Csrf::token()) ?>"
    >

    <button type="submit">
        Sair
    </button>

</form>

</body>
</html>
