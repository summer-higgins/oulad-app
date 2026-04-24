<?php
ob_start();
require_once 'db_test.php';
ob_end_clean();

header('Content-Type: text/html; charset=UTF-8');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $snapshotSql = "
        SELECT 'courses' AS table_name, COUNT(*) AS row_count FROM oulad.courses
        UNION ALL
        SELECT 'assessments', COUNT(*) FROM oulad.assessments
        UNION ALL
        SELECT 'vle', COUNT(*) FROM oulad.vle
        UNION ALL
        SELECT 'studentinfo', COUNT(*) FROM oulad.studentinfo
        UNION ALL
        SELECT 'studentregistration', COUNT(*) FROM oulad.studentregistration
        UNION ALL
        SELECT 'studentassessment', COUNT(*) FROM oulad.studentassessment
        UNION ALL
        SELECT 'studentclicks', COUNT(*) FROM oulad.studentclicks
        UNION ALL
        SELECT 'weeklyclicks', COUNT(*) FROM oulad.weeklyclicks
        UNION ALL
        SELECT 'activitytypeclicks', COUNT(*) FROM oulad.activitytypeclicks
        ORDER BY table_name
    ";
    $snapshotStmt = $pdo->query($snapshotSql);
    $snapshotRows = $snapshotStmt->fetchAll(PDO::FETCH_ASSOC);

    $resultsSql = "
        SELECT final_result, COUNT(*) AS student_count
        FROM oulad.studentinfo
        GROUP BY final_result
        ORDER BY student_count DESC
    ";
    $resultsStmt = $pdo->query($resultsSql);
    $resultsRows = $resultsStmt->fetchAll(PDO::FETCH_ASSOC);

    $activitySql = "
        SELECT activity_type, SUM(activity_clicks) AS total_clicks
        FROM oulad.activitytypeclicks
        GROUP BY activity_type
        ORDER BY total_clicks DESC
        LIMIT 10
    ";
    $activityStmt = $pdo->query($activitySql);
    $activityRows = $activityStmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='summary-panel'>";
    echo "<h3>Live Database Snapshot</h3>";
    echo "<table>";
    echo "<thead><tr><th>Table</th><th>Row Count</th></tr></thead><tbody>";

    foreach ($snapshotRows as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['table_name']) . "</td>";
        echo "<td>" . number_format($row['row_count']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    echo "</div>";

    echo "<div class='summary-panel'>";
    echo "<h3>Student Final Results</h3>";
    echo "<table>";
    echo "<thead><tr><th>Final Result</th><th>Number of Students</th></tr></thead><tbody>";

    foreach ($resultsRows as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['final_result']) . "</td>";
        echo "<td>" . number_format($row['student_count']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    echo "</div>";

    echo "<div class='summary-panel'>";
    echo "<h3>Top Activity Types by Clicks</h3>";
    echo "<table>";
    echo "<thead><tr><th>Activity Type</th><th>Total Clicks</th></tr></thead><tbody>";

    foreach ($activityRows as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['activity_type']) . "</td>";
        echo "<td>" . number_format($row['total_clicks']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    echo "</div>";

} catch (PDOException $e) {
    http_response_code(500);
    echo "<div class='note'><strong>Error loading summary data:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>