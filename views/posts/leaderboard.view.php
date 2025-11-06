<?php 
require "views/components/header.php";
require "views/components/navbar.php";
?>

<div class="main-container" style="justify-content: center; padding-top: 24px;">
  
  <div class="leaderboard" style="max-width: 700px; width: 100%; padding: 0;">
    <div class="leaderboard-card">
      <div class="leaderboard-header">
        <h3>Full Leaderboard</h3>
        <span class="leaderboard-subtitle">All-time top scores</span>
      </div>
      
      <ul class="leaderboard-list">
        <?php if (empty($leaderboard_data)): ?>
          
          <li class="leaderboard-row" style="grid-template-columns: 1fr; text-align: center; padding: 24px;">
            No scores have been recorded yet.
          </li>

        <?php else: ?>
          <?php foreach ($leaderboard_data as $index => $row): ?>
            
            <li class="leaderboard-row">
              <span class="lb-rank"><?= $index + 1 ?></span>
              <span class="lb-name"><?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?></span>
              <span class="lb-score"><?= number_format($row['highest_score'], 0) ?></span>
            </li>

          <?php endforeach; ?>
        <?php endif; ?>
      </ul>

      <div class="leaderboard-footer">
        <a href="/posts" class="leaderboard-link">Back to Home</a>
      </div>
    </div>
  </div>

</div> 

<?php require "views/components/footer.php"; ?>