<?php require "views/components/header.php"; 


?>


<nav class="navbar" role="navigation" aria-label="Main">
  <div>
  <a href="/posts" class="nav-btn">Home</a>

  <?php if (isTeacher()): ?>
    <a href="/create" class="nav-btn">Create</a>
  <?php endif; ?>
  <?php if (isTeacher()): ?>
    <a href="/createtopics" class="nav-btn">Create Topic</a>
  <?php endif; ?>

  <a href="#" class="nav-btn">Leaderboard</a>
  </div>
  <a href="/logout" class="nav-btn">Logout</a>
</nav>


<?php require "views/components/footer.php"; ?>