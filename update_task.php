<?php
/**
 * This script is to be used to receive a POST with the object information and then either updates, creates or deletes the task object
 */
require('Task.class.php');
// Assign POST data to variables
$action = $_POST["Action"];
$taskId = $_POST["TaskId"];
if ($taskId == "newTask") {
    // Create task object and assign values
    $newTask = new Task();
    $newTask->TaskName = $_POST["TaskName"];
    $newTask->TaskDescription = $_POST["TaskDescription"];
    $result = $newTask->Save();
    // Return result to index.php
    die($result);
}
if ($action == "saveTask") {
    // Create task object and assign values
    $newTask = new Task($taskId);
    $newTask->TaskName = $_POST["TaskName"];
    $newTask->TaskDescription = $_POST["TaskDescription"];
    $result = $newTask->Save();
    // Tiny pause to allow file write
    sleep(1);
    // Return result to index.php
    die($result);
}
else {
    // Create task object and assign values
    $newTask = new Task($taskId);
    $result = $newTask->Delete();
    // Return result to index.php
    die($result);
}
?>
