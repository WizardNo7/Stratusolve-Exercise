<?php
/**
 * This class handles the modification of a task object
 */
class Task {
    public $TaskId;
    public $TaskName;
    public $TaskDescription;
    protected $TaskDataFile = "Task_Data.txt";
    protected $TaskDataSource;
    public function __construct($Id = null) {
        $this->TaskDataSource = file_get_contents($this->TaskDataFile);
        if (strlen($this->TaskDataSource) > 0)
            $this->TaskDataSource = json_decode($this->TaskDataSource, true); // Should decode to an array of Tasks
        else
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array

        if (!$this->TaskDataSource)
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
        if (!$this->LoadFromId($Id))
            $this->Create();
    }
    protected function Create() {
        $this->TaskId = $this->getUniqueId();
        $this->TaskName = "New Task";
        $this->TaskDescription = "Task Description";
    }
    // Generate GUID
    protected function getUniqueId() {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        }
        else {
            mt_srand((double)microtime()*10000);
            $charid = strtoupper(md5(uniqid(mt_srand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8).$hyphen
                    .substr($charid, 8, 4).$hyphen
                    .substr($charid,12, 4).$hyphen
                    .substr($charid,16, 4).$hyphen
                    .substr($charid,20,12);
            return $uuid;
        }
    }
    protected function LoadFromId($Id = null) {
        if ($Id) {
            foreach ($this->TaskDataSource as $Task) {
                if ($Task["TaskId"] == $Id) {
                    $this->TaskId = $Task["TaskId"];
                    $this->TaskName = $Task["TaskName"];
                    $this->TaskDescription = $Task["TaskDescription"];
                    // Leave loop if task was found
                    break;
                }
            }
            return True;
        }
        else
            return False;
    }

    public function Save() {
        $found = False;
        // Find correct task to save changes
        foreach($this->TaskDataSource as $key => $Task) {
            if ($Task["TaskId"] == $this->TaskId) {
                $found = True;
                $this->TaskDataSource[$key]["TaskName"] = $this->TaskName;
                $this->TaskDataSource[$key]["TaskDescription"] = $this->TaskDescription;
                // Leave loop if task was found
                break;
            }
        }
        // If task was not found and modified
        if (!$found) {
            // Create newTask array
            $newTask = [
                    "TaskId"            => $this->TaskId,
                    "TaskName"          => $this->TaskName,
                    "TaskDescription"   => $this->TaskDescription,
            ];
            // Append newTask to TaskDataSource
            $this->TaskDataSource[] = $newTask;
        }
        // Write TaskDataSource to file
        $result = file_put_contents($this->TaskDataFile, json_encode($this->TaskDataSource));
        return $result;
    }
    public function Delete() {
        foreach($this->TaskDataSource as $key => $Task) {
            // Find correct task to delete
            if($Task["TaskId"] == $this->TaskId) {
                unset($this->TaskDataSource[$key]);
                // Re-index the array so it encodes correctly
                $this->TaskDataSource = array_values($this->TaskDataSource);
                // Leave loop if task was found
                break;
            }
        }
        // Write TaskDataSource to file
        $result = file_put_contents($this->TaskDataFile, json_encode($this->TaskDataSource));
        return $result;
    }
}
?>
