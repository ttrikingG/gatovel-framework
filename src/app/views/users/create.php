<?php

use nucleo\auth\protection\Csrf;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <title>Criar usuário</title>
</head>
<body>

<h1>Criar usuário</h1>

<form
    method="POST"
    action="/users"
>

    <input
        type="hidden"
        name="_token"
        value="<?= htmlspecialchars(Csrf::token()) ?>"
    >

    <div>
        <label for="name">
            Nome
        </label>

        <input
            type="text"
            id="name"
            name="name"
            required
        >
    </div>

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
        Criar usuário
    </button>

</form>

</body>
</html>
