<?php
/**
 * Created by PhpStorm.
 * User: johangriesel
 * Date: 13052016
 * Time: 08:48
 * @package    ${NAMESPACE}
 * @subpackage ${NAME}
 * @author     johangriesel <info@stratusolve.com>
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Basic Task Manager</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <form action="update_task.php" method="post">
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 5px;;">
                            <input id="InputTaskName" type="text" placeholder="Task Name" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <textarea id="InputTaskDescription" placeholder="Description" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="deleteTask" type="button" class="btn btn-danger">Delete Task</button>
                <button id="saveTask" type="button" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- TaskList -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <h2 class="page-header">Task List</h2>
            <!-- Button trigger modal -->
            <button id="newTask" type="button" class="btn btn-primary btn-lg" style="width:100%;margin-bottom: 5px;" data-toggle="modal" data-target="#myModal">
                Add Task
            </button>
            <div id="TaskList" class="list-group">
                <!-- Div where tasks will be listed -->
            </div>
        </div>
        <div class="col-md-3">
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
    var currentTaskId = -1;
    $('#myModal').on('show.bs.modal', function (event) {
        var triggerElement = $(event.relatedTarget); // Element that triggered the modal
        var modal = $(this);
        if (triggerElement.attr("id") == 'newTask') {
            modal.find('.modal-title').text('New Task');
            // Assign dummy text to modal inputs
            $('#InputTaskName').val("Task Name");
            $('#InputTaskDescription').val("Task Description");
            $('#deleteTask').hide();
            // Mark to get unique Id
            currentTaskId = "newTask";
        }
        else {
            modal.find('.modal-title').text('Task Details');
            // Get clicked task details
            currentTaskId = triggerElement.attr("id");
            currentTaskName = $('#'+currentTaskId+' > h4:first').text();
            currentTaskDesc = $('#'+currentTaskId+' > p:first').text();
            // Assign clicked task details to modal
            $('#InputTaskName').val(currentTaskName);
            $('#InputTaskDescription').val(currentTaskDesc);
            $('#deleteTask').show();
        }
    });
    // Save button clicked
    $('#saveTask').click(function() {
        // Get modified modal values
        newTaskName = $('#InputTaskName').val();
        newTaskDesc = $('#InputTaskDescription').val();
        $('#myModal').modal('hide');
        console.log('Saving: '+newTaskName);
        // Create post data
        taskData = {Action: "saveTask",
                    TaskId: currentTaskId,
                    TaskName: newTaskName,
                    TaskDescription: newTaskDesc};
        // Post data and display result
        $.post("update_task.php", taskData, function(data) {
            if (data) {
                console.log('An error occurred while saving: '+data);
            }
            else {
                console.log('Saved: '+data);
            }
        });
        // Refresh TaskList
        updateTaskList();
    });
    // Delete button clicked
    $('#deleteTask').click(function() {
        $('#myModal').modal('hide');
        console.log('Deleting: '+currentTaskId);
        // Create post data
        taskData = {Action: "deleteTask",
                    TaskId: currentTaskId};
        // Post data and display result
        $.post("update_task.php", taskData, function(data) {
            if (data) {
                console.log('An error occurred while deleting: '+data);
            }
            else {
                console.log('Deleted: '+data);
            }
        });
        // Refresh TaskList
        updateTaskList();
    });
    function updateTaskList() {
        $.post("list_tasks.php", function(data) {
            $("#TaskList").html(data);
        });
    }
    // Refresh TaskList
    updateTaskList();
</script>
</html>
