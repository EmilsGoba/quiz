<?php 
require "views/components/header.php";
require "views/components/navbar.php";
?>
<div class="quiz-container">
  <div class="alert alert-error">
    <?= htmlspecialchars($error ?? 'Something went wrong.', ENT_QUOTES, 'UTF-8') ?>
  </div>
  <div class="quiz-actions">
  <a href="/posts" class="btn">Back</a>
</div>

</div>
<?php require "views/components/footer.php"; ?>
