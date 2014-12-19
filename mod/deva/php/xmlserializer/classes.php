<?php

class Course{
	public $id;
	public $name;
	public $description;


}

class SlotCount{
	public $date;
	public $count;
	public $dayStr;


}

class ExamSlot{
	public $start;
	public $end;
	public $course;
	public $available;
	public $slotStr;

	


}

class AllocateExamResponse{
	public $message;
	public $success;
	public $examurl;

}

class ExamAttempt{

	public $id;
	public $start;
	public $end;
	public $courseid;
	public $coursename;
	public $available;
	public $slotStr;
	public $grade1;
	public $grade2;
	public $totalGrade;
	public $state;


}

?>
