<?php
require_once 'db_test.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Invalid request method.");
}

function clean($value) {
    return isset($value) ? trim($value) : null;
}

try {
    $id_student = clean($_POST['id_student'] ?? null);
    $gender = clean($_POST['gender'] ?? null);
    $region = clean($_POST['region'] ?? null);
    $highest_education = clean($_POST['highest_education'] ?? null);
    $imd_band = clean($_POST['imd_band'] ?? null);
    $age_band = clean($_POST['age_band'] ?? null);
    $num_of_prev_attempts = clean($_POST['num_of_prev_attempts'] ?? null);
    $studied_credits = clean($_POST['studied_credits'] ?? null);
    $disability = clean($_POST['disability'] ?? null);
    $final_result = clean($_POST['final_result'] ?? null);
    $code_module = clean($_POST['code_module'] ?? null);
    $code_presentation = clean($_POST['code_presentation'] ?? null);
    $date_registration = clean($_POST['date_registration'] ?? null);
    $date_unregistration = clean($_POST['date_unregistration'] ?? null);
    $id_assessment = clean($_POST['id_assessment'] ?? null);
    $date_submitted = clean($_POST['date_submitted'] ?? null);
    $is_banked = clean($_POST['is_banked'] ?? null);
    $score = clean($_POST['score'] ?? null);

    $required_fields = [
        'id_student' => $id_student,
        'gender' => $gender,
        'region' => $region,
        'highest_education' => $highest_education,
        'imd_band' => $imd_band,
        'age_band' => $age_band,
        'num_of_prev_attempts' => $num_of_prev_attempts,
        'studied_credits' => $studied_credits,
        'disability' => $disability,
        'final_result' => $final_result,
        'code_module' => $code_module,
        'code_presentation' => $code_presentation,
        'date_registration' => $date_registration,
        'id_assessment' => $id_assessment,
        'date_submitted' => $date_submitted,
        'is_banked' => $is_banked,
        'score' => $score
    ];

    $missing = [];
    foreach ($required_fields as $field => $value) {
        if ($value === null || $value === '') {
            $missing[] = $field;
        }
    }

    if (!empty($missing)) {
        http_response_code(400);
        exit("Missing required field(s):\n" . implode(', ', $missing));
    }

    if ($imd_band === 'NULL') {
        $imd_band = null;
    }

    if ($date_unregistration === '') {
        $date_unregistration = null;
    }

    $pdo->beginTransaction();

    $sql1 = $pdo->prepare("
        INSERT INTO oulad.studentinfo (
            code_module, code_presentation, id_student, gender, region,
            highest_education, imd_band, age_band, num_of_prev_attempts,
            studied_credits, disability, final_result
        )
        VALUES (
            :code_module, :code_presentation, :id_student, :gender, :region,
            :highest_education, :imd_band, :age_band, :num_of_prev_attempts,
            :studied_credits, :disability, :final_result
        )
    ");

    $sql1->execute([
        ':code_module' => $code_module,
        ':code_presentation' => $code_presentation,
        ':id_student' => $id_student,
        ':gender' => $gender,
        ':region' => $region,
        ':highest_education' => $highest_education,
        ':imd_band' => $imd_band,
        ':age_band' => $age_band,
        ':num_of_prev_attempts' => $num_of_prev_attempts,
        ':studied_credits' => $studied_credits,
        ':disability' => $disability,
        ':final_result' => $final_result
    ]);

    $sql2 = $pdo->prepare("
        INSERT INTO oulad.studentregistration (
            code_module, code_presentation, id_student, date_registration, date_unregistration
        )
        VALUES (
            :code_module, :code_presentation, :id_student, :date_registration, :date_unregistration
        )
    ");

    $sql2->execute([
        ':code_module' => $code_module,
        ':code_presentation' => $code_presentation,
        ':id_student' => $id_student,
        ':date_registration' => $date_registration,
        ':date_unregistration' => $date_unregistration
    ]);

    $sql3 = $pdo->prepare("
        INSERT INTO oulad.studentassessment (
            id_student, id_assessment, date_submitted, is_banked, score
        )
        VALUES (
            :id_student, :id_assessment, :date_submitted, :is_banked, :score
        )
    ");

    $sql3->execute([
        ':id_student' => $id_student,
        ':id_assessment' => $id_assessment,
        ':date_submitted' => $date_submitted,
        ':is_banked' => $is_banked,
        ':score' => $score
    ]);

    $pdo->commit();

    echo "\nRecord(s) added successfully.\n\nInserted into studentinfo, studentregistration, and studentassessment.";
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo "Database error:\n\n" . $e->getMessage();
}