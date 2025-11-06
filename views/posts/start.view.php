<?php 
require "views/components/header.php";
require "views/components/navbar.php";

// Get total question count for the progress bar
$totalQuestions = empty($qa) ? 0 : count($qa); 
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

  <?php if ($totalQuestions === 0): ?>
    <div class="alert alert-error">This quiz has no questions yet.</div>
  <?php else: ?>

    <div class="progress-container">
      <div class="progress-text">Question 1 of <?= $totalQuestions ?></div>
      <div class="progress-bar">
        <div class="progress-bar-inner"></div>
      </div>
    </div>

    <form method="POST" action="/quizz/submit" class="quiz-form">
      <input type="hidden" name="quiz_id" value="<?= (int)$quiz['quiz_id'] ?>">

      <ol class="question-list">
        <?php foreach ($qa as $index => $item): ?>
          <?php $q = $item['question']; $answers = $item['answers']; ?>
          
          <li class="question-item <?= $index === 0 ? 'active' : '' ?>">
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
            <div class="validation-message"></div>
          </li>
        <?php endforeach; ?>
      </ol>

      <div class="quiz-actions">
        <button type="button" class="btn" id="prev-btn" style="display: none;">Previous</button>
        <button type="button" class="btn btn-primary" id="next-btn">Next</button>
        <button type="submit" class="btn btn-primary" id="submit-btn" style="display: none;">Submit Quiz</button>
        <a href="/posts" class="btn">Back</a>
      </div>

    </form>
  <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const quizForm = document.querySelector('.quiz-form');
  const questions = document.querySelectorAll('.question-item');
  const totalQuestions = questions.length;

  if (totalQuestions === 0) return; // No questions, do nothing

  const nextBtn = document.getElementById('next-btn');
  const prevBtn = document.getElementById('prev-btn');
  const submitBtn = document.getElementById('submit-btn');
  
  const progressBar = document.querySelector('.progress-bar-inner');
  const progressText = document.querySelector('.progress-text');
  
  let currentQuestion = 0;

  function showQuestion(index) {
    // Hide all questions and remove error messages
    questions.forEach((q, i) => {
      q.classList.remove('active');
      q.querySelector('.validation-message').textContent = ''; // Clear error
    });
    
    // Show the current question
    if (questions[index]) {
      questions[index].classList.add('active');
    }

    // Update progress bar
    const progressPercent = ((index + 1) / totalQuestions) * 100;
    progressBar.style.width = `${progressPercent}%`;
    progressText.textContent = `Question ${index + 1} of ${totalQuestions}`;

    // Update button visibility
    prevBtn.style.display = (index === 0) ? 'none' : 'inline-block';
    nextBtn.style.display = (index === totalQuestions - 1) ? 'none' : 'inline-block';
    submitBtn.style.display = (index === totalQuestions - 1) ? 'inline-block' : 'none';
  }

  function validateCurrentQuestion() {
    const currentQuestionElement = questions[currentQuestion];
    const inputs = currentQuestionElement.querySelectorAll('input[type="radio"]');
    const isChecked = Array.from(inputs).some(input => input.checked);
    
    const validationMessageElement = currentQuestionElement.querySelector('.validation-message');

    if (!isChecked) {
      validationMessageElement.textContent = 'Please select an answer to continue.';
      return false;
    }
    
    validationMessageElement.textContent = ''; // Clear error if valid
    return true;
  }

  nextBtn.addEventListener('click', () => {
    if (validateCurrentQuestion()) {
      currentQuestion++;
      if (currentQuestion < totalQuestions) {
        showQuestion(currentQuestion);
      }
    }
  });

  prevBtn.addEventListener('click', () => {
    // No validation needed when going back
    currentQuestion--;
    if (currentQuestion >= 0) {
      showQuestion(currentQuestion);
    }
  });

  // Also validate on the final submit click
  quizForm.addEventListener('submit', (e) => {
    if (!validateCurrentQuestion()) {
      e.preventDefault(); // Stop form submission
    }
  });

  // Initial setup: show the first question
  showQuestion(0);
});
</script>

<?php require "views/components/footer.php"; ?>