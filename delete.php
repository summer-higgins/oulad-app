<?php
require_once 'db_test.php';
$form_type = $_POST['form_type'];

switch ($form_type) {
  case "student":
    try {
      // delete from studentInfo table
      $sql = $pdo->prepare("DELETE FROM oulad.studentinfo WHERE id_student = :id_student 
        AND code_module = :code_module AND code_presentation = :code_presentation");
      
      $sql->execute([
          ':id_student' => $_POST['id_student'],
          ':code_module' => $_POST['code_module'],
          ':code_presentation' => $_POST['code_presentation']
      ]);

      echo "studentinfo record deleted!\n";
    } catch (PDOException $e) {
        echo "studentinfo error: " . $e->getMessage();
    }
  break;

  case "registration":
    try {
      // delete from studentRegistration table
      $sql = $pdo->prepare("DELETE FROM oulad.studentregistration WHERE id_student = :id_student 
        AND code_module = :code_module AND code_presentation = :code_presentation");
      
      $sql->execute([
          ':id_student' => $_POST['id_student'],
          ':code_module' => $_POST['code_module'],
          ':code_presentation' => $_POST['code_presentation']
      ]);

      echo "studentregistration record deleted!\n";
    } catch (PDOException $e) {
        echo "studentregistration error: " . $e->getMessage();
    }
    break;

  case "assessment":
    try {
      // delete from studentAssessment table
      $sql = $pdo->prepare("DELETE FROM oulad.studentassessment WHERE id_student = :id_student 
        AND id_assessment = :id_assessment");
      
      $sql->execute([
          ':id_student' => $_POST['id_student'],
          ':id_assessment' => $_POST['id_assessment']
      ]);

      echo "studentassessment record deleted!\n";
    } catch (PDOException $e) {
        echo "studentassessment error: " . $e->getMessage();
    }
    break;

  case "totalclicks":
    try {
      // delete from totalClicks table
      $sql = $pdo->prepare("DELETE FROM oulad.studentclicks WHERE id_student = :id_student 
        AND code_module = :code_module AND code_presentation = :code_presentation");
            
      $sql->execute([
          ':id_student' => $_POST['id_student'],
          ':code_module' => $_POST['code_module'],
          ':code_presentation' => $_POST['code_presentation']
      ]);

      echo "studentclicks record deleted!\n";
    } catch (PDOException $e) {
        echo "studentclicks error: " . $e->getMessage();
    }
    break;

  case "weeklyclicks":
    try {
      // delete from weeklyClicks table
      $sql = $pdo->prepare("DELETE FROM oulad.weeklyclicks WHERE id_student = :id_student 
        AND code_module = :code_module AND code_presentation = :code_presentation AND week = :week");
      
      $sql->execute([
          ':id_student' => $_POST['id_student'],
          ':code_module' => $_POST['code_module'],
          ':code_presentation' => $_POST['code_presentation'],
          ':week' => $_POST['week']
      ]);

      echo "weeklyclicks record deleted!\n";
    } catch (PDOException $e) {
        echo "weeklyclicks error: " . $e->getMessage();
    }
    break;

  case "activity":
    try {
      // delete from activitytypeclicks table
      $sql = $pdo->prepare("DELETE FROM oulad.activitytypeclicks WHERE id_student = :id_student 
        AND code_module = :code_module AND code_presentation = :code_presentation AND activity_type = :activity_type");
      $sql->execute([
          ':id_student' => $_POST['id_student'],
          ':code_module' => $_POST['code_module'],
          ':code_presentation' => $_POST['code_presentation'],
          ':activity_type' => $_POST['activity_type']
      ]);

      echo "activitytypeclicks record deleted!\n";
    } catch (PDOException $e) {
        echo "activitytypeclicks error: " . $e->getMessage();
    }
    break;

  default:
    echo "Invalid form type specified.";
}

?>
