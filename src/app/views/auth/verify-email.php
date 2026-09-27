<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Verificação de e-mail</title>
</head>
<body>

<h1>Verificação de e-mail</h1>

<?php if (!empty($error)): ?>

    <p>
        <?= htmlspecialchars($error) ?>
    </p>

<?php endif; ?>

<?php if (!empty($success)): ?>

    <p>
        <?= htmlspecialchars($success) ?>
    </p>

    <p>
        <a href="/login">
            Ir para o login
        </a>
    </p>

<?php endif; ?>

</body>
</html>