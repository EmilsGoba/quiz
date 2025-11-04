<?php 
require "views/components/header.php";
require "views/components/navbar.php";
?>

<div class="quiz-container">
  <div class="quiz-header">
    <h1 class="quiz-title"><?= htmlspecialchars($quiz['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <div class="quiz-meta">
      <span class="quiz-topic">Topic: <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></span>
    </div>
  </div>

  <?php if (!empty($quiz['description'])): ?>
    <p class="quiz-desc"><?= htmlspecialchars($quiz['description'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <?php if (empty($qa)): ?>
    <div class="alert alert-error">This quiz has no questions yet.</div>
  <?php else: ?>
    <form method="POST" action="/quizz/submit" class="quiz-form">
      <input type="hidden" name="quiz_id" value="<?= (int)$quiz['quiz_id'] ?>">

      <ol class="question-list">
        <?php foreach ($qa as $index => $item): ?>
          <?php $q = $item['question']; $answers = $item['answers']; ?>
          <li class="question-item">
            <div class="question-text">
              <?= htmlspecialchars($q['question_text'], ENT_QUOTES, 'UTF-8') ?>
            </div>

            <div class="answers">
              <?php foreach ($answers as $ans): ?>
                <?php
                  $qid = (int)$q['question_id'];
                  $aid = (int)$ans['answer_id'];
                ?>
                <label class="answer-option">
                  <input type="radio"
                         name="answers[<?= $qid ?>]"
                         value="<?= $aid ?>"
                         required>
                  <span><?= htmlspecialchars($ans['answer_text'], ENT_QUOTES, 'UTF-8') ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>

      <div class="quiz-actions">
  <button type="submit" class="btn btn-primary">Submit</button>
  <a href="/posts" class="btn">Back</a>
</div>

    </form>
  <?php endif; ?>
</div>

<?php require "views/components/footer.php"; ?>
