<?php 
require "views/components/header.php";
require "views/components/navbar.php";
?>

<div class="quiz-container">
  <div class="quiz-header">
    <h1 class="quiz-title">Quiz Completed!</h1>
  </div>

  <div class="leaderboard-card"> <div class="leaderboard-header">
        <h3>Your Result</h3>
    </div>
    
    <ul class="leaderboard-list">
        <li class="leaderboard-row" style="grid-template-columns: 1fr 80px;">
            <span class="lb-name">Questions Answered</span>
            <span class="lb-score"><?= (int)$result['correct'] ?> / <?= (int)$result['total'] ?></span>
        </li>
        <li class="leaderboard-row" style="grid-template-columns: 1fr 80px;">
            <span class="lb-name" style="font-weight: 700;">Final Score</span>
            <span class="lb-score" style="font-size: 1.25rem;"><?= number_format($result['score'], 0) ?>%</span>
        </li>
    </ul>

    <div class="leaderboard-footer">
        <a href="/posts" class="leaderboard-link">Back to Home</a>
        <a href="/leaderboard" class="leaderboard-link">View Leaderboard</a>
    </div>
  </div>

</div> 

<?php require "views/components/footer.php"; ?>