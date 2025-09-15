<?php require "views/components/header.php"; ?>

<h1>Register</h1>

<form method="POST" action="/register">
    <div>
        <label>Username:</label>
        <input type="text" name="username" required> <br>

        <label>Email:</label>
        <input type="email" name="email" required> <br>

        <label>Password:</label>
        <input type="password" name="password" required> <br>

        <button type="submit">Register</button>
    </div>
</form>

<?php require "views/components/footer.php"; ?>
