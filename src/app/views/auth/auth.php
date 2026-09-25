<?php

/** @var bool $authenticated */
/** @var app\models\User|null $user */

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Auth Test</title>
</head>
<body>

<h1>Teste de autenticação</h1>

<?php if ($authenticated && $user !== null): ?>

    <p>Usuário autenticado com sucesso.</p>

    <p>
        ID:
        <?= htmlspecialchars((string) $user->getAuthIdentifier()) ?>
    </p>

    <p>
        Usuário:
        <?= htmlspecialchars($user->name) ?>
    </p>

    <p>
        E-mail:
        <?= htmlspecialchars($user->email) ?>
    </p>

<?php else: ?>

    <p>Falha na autenticação.</p>

<?php endif; ?>

</body>
</html>