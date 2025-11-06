<?php
$style = "/css/index.css";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure $db is available
if (!isset($db) || !$db) {
    $_SESSION['flash_error'] = 'Database connection not available.';
    header('Location: /posts');
    exit();
}

// Get form data
$quiz_id = filter_input(INPUT_POST, 'quiz_id', FILTER_VALIDATE_INT) ?: 0;
$user_answers = $_POST['answers'] ?? [];
if (!is_array($user_answers)) {
    $user_answers = [];
}
$user_id = $_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? 0;

if (empty($user_id) || empty($quiz_id) || empty($user_answers)) {
    $_SESSION['flash_error'] = 'Missing user, quiz, or answers.';
    header('Location: /posts');
    exit();
}

try {
    // Fetch correct answers for the quiz
    $sql = "
        SELECT q.question_id, a.answer_id
        FROM questions q
        JOIN answers a ON q.question_id = a.question_id
        WHERE q.quiz_id = :quiz_id AND a.is_correct = 1
    ";
    $stmt = $db->query($sql, ['quiz_id' => $quiz_id]);
    $correct_answers_raw = $stmt->fetchAll();

    $correct_answers_map = [];
    foreach ($correct_answers_raw as $row) {
        $correct_answers_map[(int)$row['question_id']] = (int)$row['answer_id'];
    }

    $total_questions = count($correct_answers_map);
    $correct_count = 0;

    // Create quiz attempt
    $db->query(
        "INSERT INTO quiz_attempts (user_id, quiz_id, started_at) VALUES (:uid, :qid, NOW())",
        ['uid' => $user_id, 'qid' => $quiz_id]
    );

    // get attempt id (try lastInsertId, fallback to latest row)
    $attempt_id = 0;
    try {
        if (method_exists($db, 'lastInsertId')) {
            $attempt_id = (int)$db->lastInsertId();
        }
    } catch (\Throwable $e) {
        // ignore
    }

    if (empty($attempt_id)) {
        $row = $db->query(
            "SELECT attempt_id FROM quiz_attempts WHERE user_id = :uid AND quiz_id = :qid ORDER BY attempt_id DESC LIMIT 1",
            ['uid' => $user_id, 'qid' => $quiz_id]
        )->fetch();
        $attempt_id = (int)($row['attempt_id'] ?? 0);
    }

    if (empty($attempt_id)) {
        throw new \RuntimeException('Failed to create quiz attempt record.');
    }

    // Grade answers and save each answer
    foreach ($user_answers as $question_id => $user_answer_id) {
        $q_id = (int)$question_id;
        $a_id = (int)$user_answer_id;

        $is_correct = isset($correct_answers_map[$q_id]) && ($correct_answers_map[$q_id] === $a_id);
        if ($is_correct) {
            $correct_count++;
        }

        $db->query(
            "INSERT INTO attempt_answers (attempt_id, question_id, answer_id, is_correct)
             VALUES (:att_id, :q_id, :a_id, :correct)",
            [
                'att_id' => $attempt_id,
                'q_id' => $q_id,
                'a_id' => $a_id,
                'correct' => (int)$is_correct
            ]
        );
    }

    // Calculate final score (percentage)
    $final_score = ($total_questions > 0) ? (floatval($correct_count) / $total_questions) * 100 : 0.0;

    // Update attempt with score
    $db->query(
        "UPDATE quiz_attempts SET score = :score, completed_at = NOW() WHERE attempt_id = :att_id",
        ['score' => $final_score, 'att_id' => $attempt_id]
    );

    // Prepare result for view (store in session and local var)
    $_SESSION['last_quiz_result'] = [
        'score' => $final_score,
        'correct' => $correct_count,
        'total' => $total_questions,
        'quiz_id' => $quiz_id,
        'attempt_id' => $attempt_id
    ];

    $result = $_SESSION['last_quiz_result'];

    // Instead of redirecting through router (which was causing 404), render result view directly
    $title = "Quiz Result";
    $style = "/css/index.css";
    require __DIR__ . '/../../views/posts/result.view.php';
    exit();
} catch (\Throwable $e) {
    $_SESSION['flash_error'] = 'Error submitting quiz: ' . $e->getMessage();
    header('Location: /posts');
    exit();
}
?>