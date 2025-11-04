<?php 
require "views/components/header.php";
require "views/components/navbar.php";

// Ensure we have a minimum of 15 question blocks
$initialCount = max(15, isset($old['questions']) ? count($old['questions']) : 0);

// Helper to safely fetch old values
function old_q($old, $i, $field, $default = '') {
    return htmlspecialchars($old['questions'][$i][$field] ?? $default, ENT_QUOTES, 'UTF-8');
}
function old_choice($old, $i, $j) {
    return htmlspecialchars($old['questions'][$i]['choices'][$j] ?? '', ENT_QUOTES, 'UTF-8');
}
function is_checked($old, $i, $j) {
    return (isset($old['questions'][$i]['correct']) && (string)$old['questions'][$i]['correct'] === (string)$j) ? 'checked' : '';
}
?>

<div class="create-container">
    <h1 class="page-title">Create a Quiz</h1>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
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

    <?php if (empty($categories)): ?>
        <div class="alert alert-error">
            No topics found. Please <a class="link" href="/createtopics">create a topic</a> first.
        </div>
    <?php endif; ?>

    <form method="POST" action="/create" class="quiz-form" novalidate>
        <div class="grid-2">
            <div class="form-group">
                <label for="title" class="label">Quiz Title</label>
                <input type="text" id="title" name="title" class="input"
                       value="<?= htmlspecialchars($old['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="form-group">
                <label for="category_id" class="label">Topic</label>
                <select id="category_id" name="category_id" class="select" required <?= empty($categories) ? 'disabled' : '' ?>>
                    <option value="" disabled <?= empty($old['category_id']) ? 'selected' : '' ?>>Select a topic</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int)$cat['category_id'] ?>"
                            <?= ((string)$old['category_id'] === (string)$cat['category_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="label">Description (optional)</label>
            <textarea id="description" name="description" rows="3" class="textarea"><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="questions-header">
            <h2 class="section-title">Questions <span class="muted">(minimum 15)</span></h2>
            <button type="button" id="addQuestion" class="btn btn-secondary">+ Add question</button>
        </div>

        <div id="questions" class="question-list">
            <?php for ($i = 0; $i < $initialCount; $i++): ?>
                <div class="question-card" data-index="<?= $i ?>">
                    <div class="q-header">
                        <h3>Question <?= $i + 1 ?></h3>
                    </div>

                    <div class="form-group">
                        <label class="label" for="q<?= $i ?>_text">Question text</label>
                        <input type="text" id="q<?= $i ?>_text" class="input"
                               name="questions[<?= $i ?>][text]"
                               value="<?= old_q($old, $i, 'text') ?>">
                    </div>

                    <div class="options">
                        <?php
                        $letters = ['A', 'B', 'C', 'D'];
                        for ($j = 0; $j < 4; $j++): ?>
                            <div class="option-row">
                                <div class="option-flag">
                                    <input type="radio"
                                           id="q<?= $i ?>_correct_<?= $j ?>"
                                           name="questions[<?= $i ?>][correct]"
                                           value="<?= $j ?>" <?= is_checked($old, $i, $j) ?>>
                                    <label for="q<?= $i ?>_correct_<?= $j ?>" class="flag-label">Correct</label>
                                </div>
                                <div class="option-input">
                                    <label class="sr-only" for="q<?= $i ?>_choice_<?= $j ?>">Option <?= $letters[$j] ?></label>
                                    <input type="text"
                                           id="q<?= $i ?>_choice_<?= $j ?>"
                                           class="input"
                                           placeholder="Option <?= $letters[$j] ?>"
                                           name="questions[<?= $i ?>][choices][<?= $j ?>]"
                                           value="<?= old_choice($old, $i, $j) ?>">
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary" <?= empty($categories) ? 'disabled' : '' ?>>Save Quiz</button>
        </div>
    </form>
</div>

<!-- Template for dynamically added questions -->
<template id="question-template">
    <div class="question-card" data-index="__INDEX__">
        <div class="q-header">
            <h3>Question __NUMBER__</h3>
        </div>

        <div class="form-group">
            <label class="label" for="q__INDEX___text">Question text</label>
            <input type="text" id="q__INDEX___text" class="input"
                   name="questions[__INDEX__][text]">
        </div>

        <div class="options">
            <!-- 4 options -->
            <!-- j = 0..3 -->
            <div class="option-row">
                <div class="option-flag">
                    <input type="radio" id="q__INDEX___correct_0" name="questions[__INDEX__][correct]" value="0">
                    <label for="q__INDEX___correct_0" class="flag-label">Correct</label>
                </div>
                <div class="option-input">
                    <input type="text" id="q__INDEX___choice_0" class="input"
                           placeholder="Option A"
                           name="questions[__INDEX__][choices][0]">
                </div>
            </div>

            <div class="option-row">
                <div class="option-flag">
                    <input type="radio" id="q__INDEX___correct_1" name="questions[__INDEX__][correct]" value="1">
                    <label for="q__INDEX___correct_1" class="flag-label">Correct</label>
                </div>
                <div class="option-input">
                    <input type="text" id="q__INDEX___choice_1" class="input"
                           placeholder="Option B"
                           name="questions[__INDEX__][choices][1]">
                </div>
            </div>

            <div class="option-row">
                <div class="option-flag">
                    <input type="radio" id="q__INDEX___correct_2" name="questions[__INDEX__][correct]" value="2">
                    <label for="q__INDEX___correct_2" class="flag-label">Correct</label>
                </div>
                <div class="option-input">
                    <input type="text" id="q__INDEX___choice_2" class="input"
                           placeholder="Option C"
                           name="questions[__INDEX__][choices][2]">
                </div>
            </div>

            <div class="option-row">
                <div class="option-flag">
                    <input type="radio" id="q__INDEX___correct_3" name="questions[__INDEX__][correct]" value="3">
                    <label for="q__INDEX___correct_3" class="flag-label">Correct</label>
                </div>
                <div class="option-input">
                    <input type="text" id="q__INDEX___choice_3" class="input"
                           placeholder="Option D"
                           name="questions[__INDEX__][choices][3]">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
(function() {
    const container = document.getElementById('questions');
    const addBtn = document.getElementById('addQuestion');
    const tpl = document.getElementById('question-template').innerHTML;

    let nextIndex = <?= (int)$initialCount ?>;

    addBtn?.addEventListener('click', () => {
        const html = tpl
            .replaceAll('__INDEX__', String(nextIndex))
            .replaceAll('__NUMBER__', String(nextIndex + 1));
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
        container.appendChild(wrapper.firstElementChild);
        nextIndex++;
    });
})();
</script>

<?php require "views/components/footer.php"; ?>
