<?php 
require "views/components/header.php";
require "views/components/navbar.php";
?>
<div class="main-container">
  <div class="right-side">
    <div class="quizz-container">
      <form class="quizz-box" action="/quizz/start" method="GET">
        <h2 class="quizz-title">Select a Quiz</h2>

        <div class="quizz-field">
          <label for="topic">Topic</label>
          <select
            class="quizz-input"
            id="topic"
            name="topic"
            required
            onchange="if(this.value){ window.location='/posts?topic='+this.value; }"
          >
            <option value="" disabled <?= $selectedTopic ? '' : 'selected' ?>>Select a topic</option>
            <?php if (!empty($topics)): ?>
              <?php foreach ($topics as $t): ?>
                <option value="<?= (int)$t['category_id'] ?>"
                  <?= $selectedTopic === (int)$t['category_id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="" disabled>No topics yet</option>
            <?php endif; ?>
          </select>
        </div>

        <?php if ($selectedTopic > 0): ?>
          <div class="quizz-field">
            <label for="quiz">Quiz</label>
            <select class="quizz-input" id="quiz" name="quiz" required <?= empty($quizzes) ? 'disabled' : '' ?>>
              <?php if (empty($quizzes)): ?>
                <option value="" selected disabled>No quizzes for this topic</option>
              <?php else: ?>
                <option value="" disabled <?= $selectedQuiz ? '' : 'selected' ?>>Select a quiz</option>
                <?php foreach ($quizzes as $q): ?>
                  <option value="<?= (int)$q['quiz_id'] ?>"
                    <?= $selectedQuiz === (int)$q['quiz_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($q['title'], ENT_QUOTES, 'UTF-8') ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        <?php endif; ?>

        <button type="submit" class="quizz-btn"
          <?= ($selectedTopic > 0 && !empty($quizzes)) ? '' : 'disabled' ?>>
          Start Quiz
        </button>
      </form>
    </div>
  </div>

  <div class="left-side">
    <div class="leaderboard">
      <div class="leaderboard-card">
        <div class="leaderboard-header">
          <h3>Leaderboard</h3>
          <span class="leaderboard-subtitle">Top 5</span>
        </div>
        
        <ul class="leaderboard-list">
          <?php if (empty($top_scores)): ?>
            
            <li class="leaderboard-row" style="grid-template-columns: 1fr; text-align: center; padding: 16px;">
              No scores yet.
            </li>

          <?php else: ?>
            <?php foreach ($top_scores as $index => $row): ?>
              
              <li class="leaderboard-row">
                <span class="lb-rank"><?= $index + 1 ?></span>
                <span class="lb-name"><?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?></span>
                <span class="lb-score"><?= number_format($row['highest_score'], 0) ?></span>
              </li>

            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
        
        <div class="leaderboard-footer">
          <a href="/leaderboard" class="leaderboard-link">View all</a>
        </div>
      </div>
    </div>
  </div>
</div> 

<?php require "views/components/footer.php"; ?>