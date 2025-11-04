<?php 
require "views/components/header.php";
require "views/components/navbar.php";
?>

<div class="container">
    <h1 class="page-title">Create Topic</h1>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul class="list">
                <?php foreach ($errors as $msg): ?>
                    <li><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/createtopics" class="form">
        <div class="form-group">
            <label for="name" class="label">Topic name</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="30"
                value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                class="input"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>

    <h2 class="section-title">Existing Topics</h2>
    <?php if (!empty($categories)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th class="th">ID</th> <!-- display numbering, not DB id -->
                    <th class="th">Name</th>
                    <th class="th">Actions</th>
                </tr>
            </thead>
            <?php $total = count($categories); ?>
<tbody>
  <?php foreach ($categories as $idx => $cat): ?>
    <?php $displayNo = $total - $idx; // top shows N, bottom shows 1 ?>
    <tr>
      <td class="td"><?= $displayNo ?></td>
      <td class="td"><?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?></td>
      <td class="td">
        <form method="POST" action="/createtopics" onsubmit="return confirm('Delete this topic?');" style="display:inline">
          <input type="hidden" name="delete_id" value="<?= (int)$cat['category_id'] ?>">
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</tbody>

            </tbody>
        </table>
    <?php else: ?>
        <p class="muted">No topics yet.</p>
    <?php endif; ?>
</div>

<?php require "views/components/footer.php"; ?>
