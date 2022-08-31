<?php
$sn = "localhost";
$un = "root";
$pw = "";
$dbN = "the test task";
//-------------------------------------------exercise 2 A
echo "\n", 'Excercise 2A', "\n";
$con = mysqli_connect($sn, $un, $pw, $dbN);
$getRecordDate = "SELECT patient.pn, patient.first, patient.last, insurance.iname, insurance.from_date, insurance.to_date
                  FROM insurance 
                  LEFT JOIN patient 
                  ON patient._id = insurance.patient_id 
                  ORDER BY insurance.from_date ASC, patient.last DESC";

$getPatients = "SELECT patient.first, patient.last
                FROM patient;";

$getPatientsRes = mysqli_query($con, $getPatients);

$recDateRes = mysqli_query($con, $getRecordDate);
$recResCheck = mysqli_num_rows($recDateRes);



if ($recResCheck > 0) {
  while ($recRow = mysqli_fetch_assoc($recDateRes)) {
    echo $recRow['pn'], ', ', $recRow['last'], ', ', $recRow['first'], ', ', $recRow['iname'], ', ',
      date("m-d-y", strtotime($recRow['from_date'])), ', ', date("m-d-y", strtotime($recRow['to_date'])), "\n";
  }
}


//-------------------------------------------exercise 2 A
echo "\n", 'Excercise 2B', "\n";

//-------------------------------------------exercise 2 B
$data = [];
while ($patientRow = mysqli_fetch_assoc($getPatientsRes)) {
  array_push($data, $patientRow);
}

$letters = array();
$percentage = array();
$total = 0;


foreach ($data as $x_key => $x_value) {
  $lastFirst = trim(strtoupper("{$x_value['last']}{$x_value['first']}")); //get the first and last name, trim and set to uppercase.

  $total += strlen($lastFirst); // count all total letters
  foreach (count_chars($lastFirst, 1) as $i => $val) { //add all letters to array and count them
    if (!array_key_exists(chr($i), $letters)) {
      $letters[chr($i)] = 0;
    }
    $letters[chr($i)] += $val;
  }
}

foreach ($letters as $key => $value) { //add percentages into array
  if (!array_key_exists($key, $percentage)) {
    $percentage[$key] = 0;
  }
  $percentage[$key] = number_format(100 * (int)$letters[$key] / (int)$total, 2);
}

foreach ($letters as $key => $value) {
  echo $key, "\t", $value, "\t", $percentage[$key], " %\n";
}
//-------------------------------------------exercise 2 B

?>