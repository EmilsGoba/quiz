<?php require "views/components/header.php"; ?>

<div class="form-wrapper">
    <h1>Register</h1>

    <form method="POST" action="/register">
        <div>
            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            <?php if (!empty($errors['username'])): ?>
                <p class="error"><?= htmlspecialchars($errors['username']) ?></p>
            <?php endif; ?>
            <br>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            <?php if (!empty($errors['email'])): ?>
                <p class="error"><?= htmlspecialchars($errors['email']) ?></p>
            <?php endif; ?>
            <br>

            <label>Password:</label>
            <input type="password" name="password" required>
            <br>

            <button type="submit">Register</button>
        </div>
    </form>
</div>

<?php require "views/components/footer.php"; ?>
