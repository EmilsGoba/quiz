<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// NO database connection here. $db comes from the main index.php

// 1. Get data from the form
$quiz_id = $_POST['quiz_id'] ?? 0;
$user_answers = $_POST['answers'] ?? [];
$user_id = $_SESSION['user_id'] ?? 0; 

if (empty($user_id) || empty($quiz_id) || empty($user_answers)) {
    header('Location: /posts');
    exit();
}

// 2. Get the correct answers for this quiz
$sql = "
    SELECT q.question_id, a.answer_id
    FROM questions q
    JOIN answers a ON q.question_id = a.question_id
    WHERE q.quiz_id = :quiz_id AND a.is_correct = 1
";
$correct_answers_raw = $db->query($sql, ['quiz_id' => $quiz_id])->fetchAll();

// Map correct answers
$correct_answers_map = [];
foreach ($correct_answers_raw as $row) {
    $correct_answers_map[$row['question_id']] = $row['answer_id'];
}

$total_questions = count($correct_answers_map);
$correct_count = 0;

// 3. Create a quiz attempt entry
$db->query(
    "INSERT INTO quiz_attempts (user_id, quiz_id, started_at) VALUES (:uid, :qid, NOW())",
    ['uid' => $user_id, 'qid' => $quiz_id]
);
$attempt_id = $db->lastInsertId();

// 4. Grade the quiz
foreach ($user_answers as $question_id => $user_answer_id) {
    $is_correct = ($correct_answers_map[$question_id] == $user_answer_id);
    
    if ($is_correct) {
        $correct_count++;
    }

    // Save this specific answer
    $db->query(
        "INSERT INTO attempt_answers (attempt_id, question_id, answer_id, is_correct)
         VALUES (:att_id, :q_id, :a_id, :correct)",
        [
            'att_id' => $attempt_id,
            'q_id' => $question_id,
            'a_id' => $user_answer_id,
            'correct' => (int)$is_correct
        ]
    );
}

// 5. Calculate final score
$final_score = ($total_questions > 0) ? ($correct_count / $total_questions) * 100 : 0;

// 6. Update the attempt with the final score
$db->query(
    "UPDATE quiz_attempts SET score = :score, completed_at = NOW() WHERE attempt_id = :att_id",
    ['score' => $final_score, 'att_id' => $attempt_id]
);

// 7. Store results in session
$_SESSION['last_quiz_result'] = [
    'score' => $final_score,
    'correct' => $correct_count,
    'total' => $total_questions,
    'quiz_id' => $quiz_id
];

// 8. Redirect to the results page
header('Location: /quizz/result');
exit();