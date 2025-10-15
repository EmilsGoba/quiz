<?php require "views/components/header.php"; 
?>

<div class="form-wrapper">
    <h1>Login</h1>

    <form action="/login" method="POST">
        <div>
            <label>Email:</label>
            <input type="text" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            <?php if (!empty($errors['email'])): ?>
                <p class="error"><?= htmlspecialchars($errors['email']) ?></p>
            <?php endif; ?>
            <br>

            <label>Password:</label>
            <input type="password" name="password" required>
            <?php if (!empty($errors['password'])): ?>
                <p class="error"><?= htmlspecialchars($errors['password']) ?></p>
            <?php endif; ?>
            <br>

            <button type="submit">Login</button>
        </div>
    </form>
</div>

<?php require "views/components/footer.php"; ?>
