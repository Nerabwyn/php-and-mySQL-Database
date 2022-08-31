<?php



interface PatientRecord
{
  public function recordID($_id);
  public function RecordAssocPn($pn);
}


class Patient implements PatientRecord
{
  public $_id, $pn, $last, $first, $dob, $isValid;

  public $records = [];

  function __construct($pn)
  {
    $this->pn = $pn;
    $getPatientsRes = mysqli_query(mysqli_connect("localhost", 'root', '', 'the test task'), "SELECT * FROM patient;");

    foreach ($getPatientsRes as $key => $value) {
      if ($value['pn'] == $pn) {
        $this->_id = $value['_id'];
        $this->last = $value['last'];
        $this->first = $value['first'];
        $this->dob = $value['dob'];
      }
    }
    $insurance = new Insurance($pn);
    $this->records = $insurance->colRecords;

  }
  public function recordID($_id) //returns patient id

  {
    return $this->_id;
  }
  public function RecordAssocPn($pn) //returns patient personal number

  {
    return $this->_id;
  }
  public function retPatientName() //returns patiens full name

  {
    $name = "{$this->first} {$this->last}";
    return $name;
  }
  public function retPatientRecords() // returns patient records

  {
    return $this->records;
  }
  public function dateFunc($date) //shows if insurance is still valid

  {
    foreach ($this->records as $key => $value) { //check the records
      $insurance = new Insurance($this->pn);
      $startDate = strtotime($value['from_date']);
      if (!$value['to_date'] == '') { //if there is end date then set it otherwise will continue with no endDate
        $endDate = strtotime($value['to_date']);
      }
      else {
        $endDate = null;
      }

      $isValid = $insurance->dateValid($date, $startDate, $endDate); //check from insurance class if insurance is valid
      if (is_null($isValid)) {
        $isValid = "Effective infinitely";
      }
      else if ($isValid == 1) {
        $isValid = "Yes";
      }
      else if ($isValid == 0) {
        $isValid = "No";
      }
      echo $this->pn, ", ", $this->retPatientName(), ", ", $value['iname'], ", ", $isValid, "\n";
    }
  }
}

class Insurance implements PatientRecord
{
  public $_id, $patient_id, $iname, $from_date, $to_date;
  public $colRecords = []; //collect records later store in patients records
  function __construct($id)
  {
    $getInsuranceRes = mysqli_query(mysqli_connect("localhost", 'root', '', 'the test task'), "SELECT * FROM insurance;");

    foreach ($getInsuranceRes as $key => $value) {
      if ($value['patient_id'] == $id) {
        array_push($this->colRecords, $value);
        $this->patient_id = ltrim($id, '0');
        $this->_id = $value['_id'];
        $this->iname = $value['iname'];
        $this->from_date = $value['from_date'];
        $this->to_date = $value['to_date'];
      }
    }
  }
  public function recordID($_id) //return insurance rec ID

  {
    return $this->$_id;
  }
  public function RecordAssocPn($pn) //return insurance rec associated pn(how)

  {
    return $this->patient_id;
  }

  public function dateValid($date, $startDate, $endDate)
  { //return true or false for date validity
    $arr = explode('-', $date);
    $month = $arr[0];
    $day = $arr[1];
    $year = $arr[2];

    if (checkdate($month, $day, $year) and $endDate) { //check if inserted date is correct(still possible to mishandle)
      $targetDate = strtotime(DateTime::createFromFormat('m-d-y', $date)->format('d-m-Y'));
      if (($targetDate >= $startDate) && ($targetDate <= $endDate)) { //if insurance is within dates then valid
        return true;
      }
      else {
        return false;
      }
    }
    else if (!$endDate) { //check if 
      return null;
    }
    else {
      return 'Invalid date!';
    }
  }

}
?>