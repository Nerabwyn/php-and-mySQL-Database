<?php
$sn = "localhost";
$un = "root";
$pw = "";
$dbN = "the test task";
$con = mysqli_connect($sn, $un, $pw, $dbN);


//input fields
$patientTableName = 'patient';
$insuranceTableName = 'insurance';
$numOfpatientRecords = 50;
$numOfInsuranceRecords = 5;

//Comment: got Field 'patient_id' doesn't have a default value, had to disable strict mode(not a good solution)


$createPatientTable = "CREATE TABLE $patientTableName(
                  _id   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  pn    VARCHAR(11) DEFAULT NULL,
                  first VARCHAR(15) DEFAULT NULL,
                  last  VARCHAR(25) DEFAULT NULL,
                  dob   DATE DEFAULT NULL)";

$createInsuranceTable = "CREATE TABLE $insuranceTableName(
                  _id           INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  patient_id    INT(10) UNSIGNED NOT NULL,
                  iname         VARCHAR(40) DEFAULT NULL,
                  from_date     DATE DEFAULT NULL,
                  to_date       DATE DEFAULT NULL,
                                FOREIGN KEY (patient_id) REFERENCES patient(_id))";


if (mysqli_query($con, $createPatientTable)) {
  echo "Patient table created successfully", "\n";
}
else {
  echo "Error: " . $createPatientTable . "\n" . mysqli_error($con);
}

if (mysqli_query($con, $createInsuranceTable)) {
  echo "Insurance table created successfully", "\n";
}
else {
  echo "Error: " . $createInsuranceTable . "\n" . mysqli_error($con);
}


for ($i = 1; $i <= $numOfpatientRecords; $i++) {
  //Generate random unix integer from 1985 to 2009
  $int = mt_rand(170358599, 1262055681);
  //Convert integer to date format
  $date = date("Y-m-d", $int);
  //Lengthen zeroes of personal number as seen in example
  $number = str_pad($i, 9, '0', STR_PAD_LEFT);

  //Separate name
  $parts = explode(" ", randomName());
  $lastname = array_pop($parts);
  $firstname = implode(" ", $parts);

  //enter i ammount of generated names and other data into the table
  $populatePatients = "INSERT INTO $patientTableName(pn, first, last, dob)
            VALUES(
                '$number',
                '$firstname',
                '$lastname',
                '$date')";

  $date1 = mt_rand(170358599, 1653595256);
  $date2 = mt_rand(170358599, 2063810685);

  if ($date1 <= $date2) {
    $fromDate = $date1;
    $toDate = $date2;
  }
  else {
    $fromDate = $date2;
    $toDate = $date1;
  }

  $fromDate = date("Y-m-d", $fromDate);
  $toDate = date("Y-m-d", $toDate);



  if (mysqli_query($con, $populatePatients)) {
    echo "New patient record ", $i, " created successfully", "\n";
  }
  else {
    echo "Error: " . $populatePatients . "\n" . mysqli_error($con);
  }

  for ($w = 1; $w <= $numOfInsuranceRecords; $w++) {
    $iname = randomIName();
    $populateInsurance = "INSERT INTO $insuranceTableName(patient_id,iname, from_date, to_date)
            VALUES(
                '$i',
                '$iname',
                '$fromDate',
                '$toDate')";

    if (mysqli_query($con, $populateInsurance)) {
      echo "New  record ", $w, " created successfully", "\n";
    }
    else {
      echo "Error: " . $populateInsurance . "\n" . mysqli_error($con);
    }
  }




}


//random name generator, courtesy of https://stackoverflow.com/a/52473779
function randomIName()
{
  $inames = array('Medicare', 'Blue Cross', 'Medicaid', 'Blue shield');
  $name = $inames[rand(0, count($inames) - 1)];
  return $name;
}


function randomName()
{
  $firstname = array(
    'Johnathon',
    'Anthony',
    'Erasmo',
    'Raleigh',
    'Nancie',
    'Tama',
    'Camellia',
    'Augustine',
    'Christeen',
    'Luz',
    'Diego',
    'Lyndia',
    'Thomas',
    'Georgianna',
    'Leigha',
    'Alejandro',
    'Marquis',
    'Joan',
    'Stephania',
    'Elroy',
    'Zonia',
    'Buffy',
    'Sharie',
    'Blythe',
    'Gaylene',
    'Elida',
    'Randy',
    'Margarete',
    'Margarett',
    'Dion',
    'Tomi',
    'Arden',
    'Clora',
    'Laine',
    'Becki',
    'Margherita',
    'Bong',
    'Jeanice',
    'Qiana',
    'Lawanda',
    'Rebecka',
    'Maribel',
    'Tami',
    'Yuri',
    'Michele',
    'Rubi',
    'Larisa',
    'Lloyd',
    'Tyisha',
    'Samatha',
  );

  $lastname = array(
    'Mischke',
    'Serna',
    'Pingree',
    'Mcnaught',
    'Pepper',
    'Schildgen',
    'Mongold',
    'Wrona',
    'Geddes',
    'Lanz',
    'Fetzer',
    'Schroeder',
    'Block',
    'Mayoral',
    'Fleishman',
    'Roberie',
    'Latson',
    'Lupo',
    'Motsinger',
    'Drews',
    'Coby',
    'Redner',
    'Culton',
    'Howe',
    'Stoval',
    'Michaud',
    'Mote',
    'Menjivar',
    'Wiers',
    'Paris',
    'Grisby',
    'Noren',
    'Damron',
    'Kazmierczak',
    'Haslett',
    'Guillemette',
    'Buresh',
    'Center',
    'Kucera',
    'Catt',
    'Badon',
    'Grumbles',
    'Antes',
    'Byron',
    'Volkman',
    'Klemp',
    'Pekar',
    'Pecora',
    'Schewe',
    'Ramage',
  );

  $name = $firstname[rand(0, count($firstname) - 1)];
  $name .= ' ';
  $name .= $lastname[rand(0, count($lastname) - 1)];

  return $name;
}
?>