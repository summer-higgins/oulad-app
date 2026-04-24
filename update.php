<?php
header('Content-Type: text/plain');

$host = "localhost";
$dbname = "OULAD";
$user = "postgres";
$password = "postgres";
$port = "5432";

function clean($value) {
    return isset($value) ? trim($value) : null;
}

function require_fields($fields, $source) {
    $missing = [];
    foreach ($fields as $field) {
        if (!isset($source[$field]) || trim($source[$field]) === '') {
            $missing[] = $field;
        }
    }
    return $missing;
}

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method. Use POST.");
    }

    $form_type = clean($_POST['form_type']);

    if (!$form_type) {
        throw new Exception("Missing form_type.");
    }

    if ($form_type === 'studentinfo') {
        $required = ['id_student', 'code_module', 'code_presentation'];
        $missing = require_fields($required, $_POST);

        if (!empty($missing)) {
            throw new Exception("Missing required Student Info key field(s): " . implode(', ', $missing));
        }

        $id_student = clean($_POST['id_student']);
        $code_module = clean($_POST['code_module']);
        $code_presentation = clean($_POST['code_presentation']);

        $allowed_updates = [
            'region' => clean($_POST['region'] ?? null),
            'studied_credits' => clean($_POST['studied_credits'] ?? null),
            'final_result' => clean($_POST['final_result'] ?? null)
        ];

        $set_parts = [];
        $params = [];

        foreach ($allowed_updates as $column => $value) {
            if ($value !== null && $value !== '') {
                $set_parts[] = "$column = :$column";
                $params[":$column"] = $value;
            }
        }

        if (empty($set_parts)) {
            throw new Exception("No new Student Info values were provided.");
        }

        $sql = "UPDATE oulad.studentinfo
                SET " . implode(', ', $set_parts) . "
                WHERE id_student = :id_student
                  AND code_module = :code_module
                  AND code_presentation = :code_presentation";

        $params[':id_student'] = $id_student;
        $params[':code_module'] = $code_module;
        $params[':code_presentation'] = $code_presentation;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            echo "Student Info updated successfully.";
        } else {
            echo "No Student Info record was updated. Check the key fields or see whether the values were unchanged.";
        }

    } elseif ($form_type === 'registration') {
        $required = ['id_student', 'code_module', 'code_presentation'];
        $missing = require_fields($required, $_POST);

        if (!empty($missing)) {
            throw new Exception("Missing required Registration key field(s): " . implode(', ', $missing));
        }

        $id_student = clean($_POST['id_student']);
        $code_module = clean($_POST['code_module']);
        $code_presentation = clean($_POST['code_presentation']);

        $allowed_updates = [
            'date_registration' => clean($_POST['date_registration'] ?? null),
            'date_unregistration' => clean($_POST['date_unregistration'] ?? null)
        ];

        $set_parts = [];
        $params = [];

        foreach ($allowed_updates as $column => $value) {
            if ($value !== null && $value !== '') {
                $set_parts[] = "$column = :$column";
                $params[":$column"] = $value;
            }
        }

        if (empty($set_parts)) {
            throw new Exception("No new Registration values were provided.");
        }

        $sql = "UPDATE oulad.studentregistration
                SET " . implode(', ', $set_parts) . "
                WHERE id_student = :id_student
                  AND code_module = :code_module
                  AND code_presentation = :code_presentation";

        $params[':id_student'] = $id_student;
        $params[':code_module'] = $code_module;
        $params[':code_presentation'] = $code_presentation;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            echo "Registration Info updated successfully.";
        } else {
            echo "No Registration record was updated. Check the key fields or see whether the values were unchanged.";
        }

    } elseif ($form_type === 'assessment') {
        $required = ['id_student', 'id_assessment'];
        $missing = require_fields($required, $_POST);

        if (!empty($missing)) {
            throw new Exception("Missing required Assessment key field(s): " . implode(', ', $missing));
        }

        $id_student = clean($_POST['id_student']);
        $id_assessment = clean($_POST['id_assessment']);

        $allowed_updates = [
            'date_submitted' => clean($_POST['date_submitted'] ?? null),
            'score' => clean($_POST['score'] ?? null),
            'is_banked' => clean($_POST['is_banked'] ?? null)
        ];

        $set_parts = [];
        $params = [];

        foreach ($allowed_updates as $column => $value) {
            if ($value !== null && $value !== '') {
                $set_parts[] = "$column = :$column";
                $params[":$column"] = $value;
            }
        }

        if (empty($set_parts)) {
            throw new Exception("No new Assessment values were provided.");
        }

        $sql = "UPDATE oulad.studentassessment
                SET " . implode(', ', $set_parts) . "
                WHERE id_student = :id_student
                  AND id_assessment = :id_assessment";

        $params[':id_student'] = $id_student;
        $params[':id_assessment'] = $id_assessment;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            echo "Student Assessment updated successfully.";
        } else {
            echo "No Student Assessment record was updated. Check the key fields or see whether the values were unchanged.";
        }

    } elseif ($form_type === 'totalclicks') {
        $required = ['id_student', 'code_module', 'code_presentation'];
        $missing = require_fields($required, $_POST);

        if (!empty($missing)) {
            throw new Exception("Missing required Total Clicks key field(s): " . implode(', ', $missing));
        }

        $id_student = clean($_POST['id_student']);
        $code_module = clean($_POST['code_module']);
        $code_presentation = clean($_POST['code_presentation']);
        $total_clicks = clean($_POST['total_clicks'] ?? null);

        if ($total_clicks === null || $total_clicks === '') {
            throw new Exception("No new Total Clicks value was provided.");
        }

        $sql = "UPDATE oulad.studentclicks
                SET total_clicks = :total_clicks
                WHERE id_student = :id_student
                  AND code_module = :code_module
                  AND code_presentation = :code_presentation";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':total_clicks' => $total_clicks,
            ':id_student' => $id_student,
            ':code_module' => $code_module,
            ':code_presentation' => $code_presentation
        ]);

        if ($stmt->rowCount() > 0) {
            echo "Total Clicks Info updated successfully.";
        } else {
            echo "No Total Clicks record was updated. Check the key fields or see whether the values were unchanged.";
        }

    } elseif ($form_type === 'weeklyclicks') {
        $required = ['id_student', 'code_module', 'code_presentation', 'week'];
        $missing = require_fields($required, $_POST);

        if (!empty($missing)) {
            throw new Exception("Missing required Weekly Clicks key field(s): " . implode(', ', $missing));
        }

        $id_student = clean($_POST['id_student']);
        $code_module = clean($_POST['code_module']);
        $code_presentation = clean($_POST['code_presentation']);
        $week = clean($_POST['week']);
        $weekly_clicks = clean($_POST['weekly_clicks'] ?? null);

        if ($weekly_clicks === null || $weekly_clicks === '') {
            throw new Exception("No new Weekly Clicks value was provided.");
        }

        $sql = "UPDATE oulad.weeklyclicks
                SET weekly_clicks = :weekly_clicks
                WHERE id_student = :id_student
                  AND code_module = :code_module
                  AND code_presentation = :code_presentation
                  AND week = :week";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':weekly_clicks' => $weekly_clicks,
            ':id_student' => $id_student,
            ':code_module' => $code_module,
            ':code_presentation' => $code_presentation,
            ':week' => $week
        ]);

        if ($stmt->rowCount() > 0) {
            echo "Weekly Clicks Info updated successfully.";
        } else {
            echo "No Weekly Clicks record was updated. Check the key fields or see whether the values were unchanged.";
        }

    } elseif ($form_type === 'activityclicks') {
        $required = ['id_student', 'code_module', 'code_presentation', 'activity_type'];
        $missing = require_fields($required, $_POST);

        if (!empty($missing)) {
            throw new Exception("Missing required Activity Type Clicks key field(s): " . implode(', ', $missing));
        }

        $id_student = clean($_POST['id_student']);
        $code_module = clean($_POST['code_module']);
        $code_presentation = clean($_POST['code_presentation']);
        $activity_type = clean($_POST['activity_type']);
        $activity_clicks = clean($_POST['activity_clicks'] ?? null);

        if ($activity_clicks === null || $activity_clicks === '') {
            throw new Exception("No new Activity Type Clicks value was provided.");
        }

        $sql = "UPDATE oulad.activitytypeclicks
                SET activity_clicks = :activity_clicks
                WHERE id_student = :id_student
                  AND code_module = :code_module
                  AND code_presentation = :code_presentation
                  AND activity_type = :activity_type";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':activity_clicks' => $activity_clicks,
            ':id_student' => $id_student,
            ':code_module' => $code_module,
            ':code_presentation' => $code_presentation,
            ':activity_type' => $activity_type
        ]);

        if ($stmt->rowCount() > 0) {
            echo "Activity Type Clicks Info updated successfully.";
        } else {
            echo "No Activity Type Clicks record was updated. Check the key fields or see whether the values were unchanged.";
        }

    } else {
        throw new Exception("Invalid form_type received.");
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>