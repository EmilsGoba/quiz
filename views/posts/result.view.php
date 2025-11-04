<?php 
require "views/components/header.php";
require "views/components/navbar.php";
?>
<div class="quiz-container">
  <div class="quiz-header">
    <h1 class="quiz-title">Results: <?= htmlspecialchars($quiz['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <div class="quiz-meta">
      <span class="quiz-topic">Topic: <?= htmlspecialchars($quiz['category_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
    </div>
  </div>

  <div class="alert alert-success">
    Score: <strong><?= (int)$correctCount ?>/<?= (int)$total ?></strong> (<?= $scorePct ?>%)
  </div>

  <ol class="question-list">
    <?php foreach ($breakdown as $row): ?>
      <li class="question-item">
        <div class="question-text"><?= htmlspecialchars($row['question'], ENT_QUOTES, 'UTF-8') ?></div>
        <div class="answers review">
          <div class="<?= $row['is_correct'] ? 'good' : 'bad' ?>">
            Your answer: <?= htmlspecialchars($row['chosen_text'], ENT_QUOTES, 'UTF-8') ?>
          </div>
          <?php if (!$row['is_correct']): ?>
            <div class="correct">
              Correct: <?= htmlspecialchars($row['correct_text'], ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>
        </div>
      </li>
    <?php endforeach; ?>
  </ol>

  <div class="quiz-actions">
  <a href="/quizz/start?topic=<?= (int)$quiz['category_id'] ?>&quiz=<?= (int)$quiz['quiz_id'] ?>&r=<?= time() ?>" class="btn btn-secondary">Try Again</a>
  <a href="/posts" class="btn">Back</a>
</div>

</div>
<?php require "views/components/footer.php"; ?>
