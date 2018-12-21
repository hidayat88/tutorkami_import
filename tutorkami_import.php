
<?php 
require_once('config.php');



class db {
    var $conn;
	var $count;
    function __construct() {
        session_start();
    }

    function con_db() {
        $this->conn = new mysqli(HOSTNAME, DB_USER, DB_PASS, DBNAME);
        if($this->conn->connect_errno) {
            echo "Database Connectivity Error : ".str_replace(DB_USER, '********', $this->conn->connect_error);
            exit();
        } else {
            return $this->conn;
        }
    }

    function RealEscape($data) {
        if(is_array($data)) {
            foreach($data as $key=>$val)
            {
                if(is_array($data[$key])) {
                    $data[$key] = $this->RealEscape($data[$key]);
                } else {
                    $data[$key] = $this->conn->real_escape_string(trim($val));
                }
            }
        } else {
            $data = $this->conn->real_escape_string(trim($data));
        }
        return $data;
    }

    function UltimateCurlSend($URL, $method = 'GET', $data = NULL) {
        // CURL
        if ($method == 'GET') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        } else {
            $ch = curl_init($URL . '?access_token=' . $_SESSION["access_token"]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Check if data is not NULL
            if ($data != '') {
                $data_string = json_encode($data, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string)
                ));
            }
        }

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    function getRandStr($length='6'){
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function ImportData($data) {

        $thisDB = $this->con_db();
        
        $country_id         = isset($data['u_country_id'])      ? $this->RealEscape($data['u_country_id']) : '';
        $username           = isset($data['u_username'])        ? $this->RealEscape($data['u_username']) : '';
        $email              = isset($data['u_email'])           ? $this->RealEscape($data['u_email']) : '';
        $gender             = isset($data['u_gender'])          ? $this->RealEscape($data['u_gender']) : '';
        $password           = isset($data['u_password'])        ? $this->RealEscape($data['u_password']) : '';
        $password_salt      = isset($data['u_password_salt'])   ? $this->RealEscape($data['u_password_salt']) : '';
        $displayname        = isset($data['u_displayname'])     ? $this->RealEscape($data['u_displayname']) : '';
        $profile_pic        = isset($data['u_profile_pic'])     ? $this->RealEscape($data['u_profile_pic']) : '';
        $u_status           = isset($data['u_status'])          ? $this->RealEscape($data['u_status']) : '';
        $firstname          = isset($data['ud_first_name'])     ? $this->RealEscape($data['ud_first_name']) : '';
        $lastname           = isset($data['ud_last_name'])      ? $this->RealEscape($data['ud_last_name']) : '';
        $phonenum           = isset($data['ud_phone_number'])   ? $this->RealEscape($data['ud_phone_number']) : '';
        $postalco           = isset($data['ud_postal_code'])    ? $this->RealEscape($data['ud_postal_code']) : '';
        $address            = isset($data['ud_address'])        ? $this->RealEscape($data['ud_address']) : ''; 
        $address2           = isset($data['ud_address2'])       ? $this->RealEscape($data['ud_address2']) : '';
        $udcountry          = isset($data['ud_country'])        ? $this->RealEscape($data['ud_country']) : '';        
        $udstate            = isset($data['ud_state'])          ? $this->RealEscape($data['ud_state']) : '';
        $udcity             = isset($data['ud_city'])           ? $this->RealEscape($data['ud_city']) : '';
        $ud_dob             = isset($data['ud_dob'])            ? $this->RealEscape($data['ud_dob']) : '';
        $company_name       = isset($data['ud_company_name'])   ? $this->RealEscape($data['ud_company_name']) : '';
        $race               = isset($data['ud_race'])           ? $this->RealEscape($data['ud_race']) : '';
        $marital_status     = isset($data['ud_marital_status'])    ? $this->RealEscape($data['ud_marital_status']) : '';
        $nationality        = isset($data['ud_nationality'])    ? $this->RealEscape($data['ud_nationality']) : '';
        $admin_comment      = isset($data['ud_admin_comment'])  ? $this->RealEscape($data['ud_admin_comment']) : '';
        $tutor_status       = isset($data['ud_tutor_status'])   ? $this->RealEscape($data['ud_tutor_status']) : '';
        $occupation         = isset($data['ud_current_occupation']) ?  $this->RealEscape($data['ud_current_occupation']) : '';
        $occupationother    = isset($data['ud_current_occupation_other']) ?  $this->RealEscape($data['ud_current_occupation_other']) : '';
        $tutor_experience   = isset($data['ud_tutor_experience'])   ? $this->RealEscape($data['ud_tutor_experience']) : '';
        $about_yourself     = isset($data['ud_about_yourself']) ? $this->RealEscape($data['ud_about_yourself']) : '';
        $qualification      = isset($data['ud_qualification']) ? $this->RealEscape($data['ud_qualification']) : '';
        $role               = isset($data['u_role']) ? $this->RealEscape($data['u_role']) : '';
        $tution_center      = (isset($data['tution_center']) && $data['tution_center'] == 1)? 'Tuition Centre':'Not Selected';
        $displayid          = $this->getRandStr(7); //isset($data['u_displayid']) ? $this->RealEscape($data['u_displayid']) : ''; //

        // Validation
        if ($username == '') {
            $res = array('flag' => 'error', 'message' => 'Username is required.');
        }elseif($password == '') {
            $res = array('flag' => 'error', 'message' => 'Password is required.');
        } elseif($firstname == '') {
            $res = array('flag' => 'error', 'message' => 'First name is required.');
        } elseif($lastname == '') {
            $res = array('flag' => 'error', 'message' => 'Last name is required.');
        } elseif($email == '') {
            $res = array('flag' => 'error', 'message' => 'Email is required.');
        } elseif($displayname == '') {
            $res = array('flag' => 'error', 'message' => 'Display name is required.');
        } elseif($phonenum == '') {
            $res = array('flag' => 'error', 'message' => 'Phone number is required.');
        } elseif($gender == '') {
            $res = array('flag' => 'error', 'message' => 'Gender is required.');
        }/* elseif($address == '') {
            $res = array('flag' => 'error', 'message' => 'Location is required.');
        } elseif(!isset($data['cover_area_state']) || count($data['cover_area_state']) == 0) {
            $res = array('flag' => 'error', 'message' => 'Area you can cover is required.');
        }*/ else {
            // Check for Duplicate
            $sql = "SELECT * FROM ".DB_PREFIX."_user WHERE 
            u_status <> 'D' AND (
                u_email = '{$email}' || 
                u_username = '{$email}' || 
                u_email = '{$username}' || 
                u_username = '{$username}'
            )";

            $qry = $thisDB->query($sql);
            
			//role to support varchar
            if ($qry->num_rows == 0) {
                $sqli = "INSERT INTO ".DB_PREFIX."_user SET 
                    u_email = '".$email."',
                    u_username = '".$username."',
                    u_displayname = '".$displayname."',
                    u_displayid = '".$displayid."',
                    u_gender = '".$gender."',
                    u_profile_pic = '".$profile_pic."',
                    u_status = '".$u_status."',
                    u_password = '".$password."',                 
                    u_create_date = '".date('Y-m-d H:i:s')."',
                    u_role  = '{$role}', 
                    u_country_id  = '{$country_id}'" ;
				
                $exe = $thisDB->query($sqli);
                
                  if ($exe){} else { echo $thisDB->error."<br>"; }
                
                
                if($exe) {
                    $insert_iud = $thisDB->insert_id;

                    $sq = "INSERT INTO ".DB_PREFIX."_user_details SET
                        ud_u_id         = '{$insert_iud}',
                        ud_first_name   = '{$firstname}',
                        ud_last_name    = '{$lastname}',
                        ud_dob          = '{$ud_dob}',
                        ud_phone_number = '{$phonenum}',
                        ud_address      = '{$address}',
                        ud_address2     = '{$address2}',
                        ud_country      = '{$udcountry}',
                        ud_state        = '{$udstate}',
                        ud_city         = '{$udcity}',
                        ud_postal_code  = '{$postalco}',
                        ud_company_name = '{$company_name}',
                        ud_race         = '{$race}',
                        ud_marital_status  = '{$marital_status}',
                        ud_nationality  = '{$nationality}',
                        ud_admin_comment = '{$admin_comment}',
                        ud_client_status = '{$tution_center}',
                        ud_tutor_experience = '{$tutor_experience}',
                        ud_current_occupation = '{$occupation}',
                        ud_current_occupation_other = '{$occupationother}',


                        ud_about_yourself = '{$about_yourself}',
                        ud_qualification = '{$qualification}',
                        ud_tutor_status = '{$tutor_status}'";
                    
                    $exe = $thisDB->query($sq);
                  
                   // var_dump($sq);
                    if($exe) {
                        $er = 0;
                        
                        if (isset($data['cover_area_state']) && count($data['cover_area_state']) > 0) {
              
                            foreach ($data['cover_area_state'] as $cid) {
                 
                                if (isset($data['cover_area_city_'.$cid]) && count($data['cover_area_city_'.$cid]) > 0) {
                                    foreach ($data['cover_area_city_'.$cid] as $key => $pid) {
                                        $areaSql = "INSERT INTO ".DB_PREFIX."_tutor_area_cover SET
                                         tac_u_id    = '{$insert_iud}',
                                         tac_st_id   = '{$cid}',
                                         tac_city_id = '{$pid}'";
                                        if ($thisDB->query($areaSql)){} else {
                                            echo $thisDB->error."<br>";
                                            $er++;
                                       }
                                    }
                                }
                            }
                        }
                        
                        if (isset($data['cover_area_other']) && $data['cover_area_other'] != '') {
                            $thisDB->query("INSERT INTO ".DB_PREFIX."_tutor_area_cover SET tac_u_id = '{$insert_iud}', tac_other = '".$data['cover_area_other']."'");
                        }

                        if (isset($data['tutor_course']) && count($data['tutor_course']) > 0) {
                            foreach ($data['tutor_course'] as $cid) {
                              //  var_dump($cid);
                                if (isset($data['tutor_subject_'.$cid]) && count($data['tutor_subject_'.$cid]) > 0) {
                                    foreach ($data['tutor_subject_'.$cid] as $key => $pid) {
                                        $courseSql = "INSERT INTO ".DB_PREFIX."_tutor_subject SET
                                         trs_u_id  = '{$insert_iud}',
                                         trs_tc_id = '{$cid}',
                                         trs_ts_id = '{$pid}'";

                                        if ($thisDB->query($courseSql)){} else {
                                            echo $thisDB->error."<br>";
                                            $er++;
                                        }
                                    }
                                }
                            }
                        }

                        if ($er == 0) {
                            $res = array('flag' => 'success', 'message' => 'Thank you for registering with us. Our team will get back to you shortly after verifying your account.', 'data' => $insert_iud);
                        } else {
                            $res = array('flag' => 'error', 'message' => 'Database error: '.$thisDB->error);
                        }
                        
                    } else {
                        $res = array('flag' => 'error', 'message' => 'Database error: '.$thisDB->error);
                    }
                } else {
                    $res = array('flag' => 'error', 'message' => 'Database error: '.$thisDB->error);
                }
            } else {
                $res = array('flag' => 'error', 'message' => 'Email already exists in our record.');
            }
        }

        // return $res;
    }
}

 function mig() {
	$count = 0; //add count
    $thisInit = new db();
	//var_dump($thisInit);
    $thisDB = $thisInit->con_db();

   $result = $thisInit->UltimateCurlSend('http://localhost:4190/api/tutorkami/GetMemberID');

    $r_decode = json_decode($result);
    // echo "<pre>";
    foreach ($r_decode->data as $key => $value) {

        $info_res = $thisInit->UltimateCurlSend('http://localhost:4190/api/tutorkami/GetMemberInfo?id='.$value->UserId);
        $i_decode = json_decode($info_res);

        /*print_r($i_decode->data[0]);
        echo "<br>";*/

        $info_obj = $i_decode->data[0];
        $data = array();
       
        // Area covred
        $area_covered = $info_obj->AreasCovered;
        $area_arr = explode(';', $area_covered);
        
        
        foreach ($area_arr as $area) {
            if( $area != 'x' ) {
              
                $loc_arr = explode(':', $area);
                $st_name = $loc_arr[0];
                $city_name = $loc_arr[1];

                // State Data
                $ar_sql = "SELECT `st_id` FROM `".DB_PREFIX."_states` WHERE LOWER(`st_name`) = '".strtolower($st_name)."'";
                $ar_qry = $thisDB->query($ar_sql);
                $ar_num = $ar_qry->num_rows;

               
                
                $state_id ="";
                if ($ar_num > 0) {
                    $ar_row = $ar_qry->fetch_array(MYSQLI_BOTH);
                    $state_id = $ar_row['st_id'];
                                                          
                } else {
                    $thisDB->query("INSERT INTO ".DB_PREFIX."_states SET st_name = '".$st_name."', st_c_id = '150', st_status = '1'");
                    $state_id = $thisDB->insert_id;
                }
				//New code to remove warning
                if (array_search($state_id, array_column ($data, 'cover_area_state'))) {
               // if (array_search($state_id, $data['cover_area_state']) == false) {
                    $data['cover_area_state'][] = $state_id;
                  
                }            
                //var_dump ($state_id);
                // City Data
              
                $ars_sql = "SELECT `city_id` FROM `".DB_PREFIX."_cities` WHERE LOWER(`city_name`) = '".strtolower($city_name)."'";
                $ars_qry = $thisDB->query($ars_sql);
                $ars_num = $ars_qry->num_rows;
                

                if ($ars_num > 0) {
                    $ars_row = $ars_qry->fetch_array(MYSQLI_BOTH);
                    $city_id = $ars_row['city_id'];
                } else {
                  
                    $thisDB->query("INSERT INTO ".DB_PREFIX."_cities SET city_name = '".$city_name."', city_st_id = '".$state_id."', city_status = '1'");
                    $city_id = $thisDB->insert_id;
                   // echo $thisDB->error;
                }
				
				if (array_search($city_id, array_column ($data, 'cover_area_city_'.$state_id))) {
             // if (array_search($city_id, $data['cover_area_city_'.$state_id]) == false) {    
                    $data['cover_area_city_'.$state_id][] = $city_id;
                }
            }
        }

        // Course covered
        $course_covered = $info_obj->SubjectsTaught;
        $course_arr = explode(';', $course_covered);
       
        
        foreach ($course_arr as $course) {
            # code...
            if($course != 'x') {
                $c_arr = explode(':', $course);
                $course_name = $c_arr[0];
                $subject_name = $c_arr[1];
                
                // Course Data
                $arcourse_sql = "SELECT `tc_id` FROM `".DB_PREFIX."_tution_course` WHERE LOWER(`tc_title`) = '".strtolower($course_name)."'";
                $arcourse_qry = $thisDB->query($arcourse_sql);
                $arcourse_num = $arcourse_qry->num_rows;
               
              
                
                if ($arcourse_num > 0) {
                    $arcourse_row = $arcourse_qry->fetch_array(MYSQLI_BOTH);
                    $course_id = $arcourse_row['tc_id'];
                } else {
                    $thisDB->query("INSERT INTO ".DB_PREFIX."_tution_course SET tc_title = '".$course_name."', tc_description = '".$course_name."', tc_country_id = '150', tc_status = 'A'");
                    $course_id = $thisDB->insert_id;
                  
                }
                
                if (array_search($course_id, array_column ($data, 'tutor_course'))) {
             //   if (array_search($course_id, $data['tutor_course']) == false) {        
                    $data['tutor_course'][] = $course_id;
                    }
                   
                // Subject Data
                    $subject_sql = "SELECT `ts_id` FROM `".DB_PREFIX."_tution_subject` WHERE LOWER(`ts_title`) = '".strtolower($subject_name)."' AND `ts_tc_id`='".$course_id."'"; 
                $subject_qry = $thisDB->query($subject_sql);
                $subject_num = $subject_qry->num_rows;
                    

                if ($subject_num > 0) {
                    $subject_row = $subject_qry->fetch_array(MYSQLI_BOTH);
                    $subject_id = $subject_row['ts_id'];
                } else {
                    $thisDB->query("INSERT INTO ".DB_PREFIX."_tution_subject SET ts_title = '".$subject_name."', ts_description = '".$subject_name."', ts_tc_id = '".$course_id."', ts_status = 'A', ts_country_id = 150");
                    $subject_id = $thisDB->insert_id;
                   echo $thisDB->error;
                }
                
				if (array_search($subject_id, array_column ($data, 'tutor_subject_'.$course_id))) {
             //   if (array_search($subject_id, $data['tutor_subject_'.$course_id]) == false) {    
                    $data['tutor_subject_'.$course_id][] = $subject_id;
                    
                }
                
                
            }
        }

        // Collect data for only tutor with activated status 
		if ($info_obj->Type == 'tutor' && $info_obj->TutorRegistrationStatus == 'Activated'){
			$data['ud_client_status']      = ($info_obj->Type == 'tutor') ? 'Not Selected' : 'Parent' ;
		} //end if tutor status
		
		// Collect data for only client 
		if ( $info_obj->Type == 'client'){	
			$data['ud_client_status']       = ($info_obj->Type == 'client') ? 'Parent' : 'Not Selected' ;		
		}
        $data['u_username']             = $info_obj->Username;
        $data['u_email']                = $info_obj->Email;
        $data['u_gender']               = $info_obj->Gender;
        $data['u_password']             = $info_obj->Password;
     // $data['u_displayid']            = $info_obj->id;
        $data['u_displayname']          = $info_obj->DisplayName;
        $data['u_profile_pic']          = $info_obj->AvatarPictureId;
        $data['u_role']                 = $info_obj->Type == 'tutor' ? '3' : '4';
        $data['u_status']               = $info_obj->TutorRegistrationStatus == 'Activated' ? 'A' : 'P';
        $data['u_password_salt']        = $info_obj->PasswordSalt;
        
        
        $data['ud_first_name']          = $info_obj->FirstName;
        $data['ud_last_name']           = $info_obj->LastName;
        $data['ud_phone_number']        = $info_obj->Phone;
        // $data['ud_postal_code']         = $info_obj->Username;
        // $data['ud_address']             = $info_obj->Username;
        // $data['ud_address2']            = $info_obj->Username;
        // $data['ud_country']             = $info_obj->Username;
       
        $data['ud_city']                = $info_obj->City;
        $data['ud_dob']                 = $info_obj->DateOfBirth;
        // $data['ud_company_name']        = $info_obj->Username;
        $data['ud_race']                = $info_obj->Race;
        $data['ud_marital_status']      = ($info_obj->IsMarried == False) ? 'Not Married' : 'Married';
        //hardcode nationality
         $data['ud_nationality']         = "Malaysian";//$info_obj->Username;
        // $data['ud_admin_comment']       = $info_obj->Username;
         $data['ud_tutor_status']        = ($info_obj->IsFullTime =='True') ? 'Full Time' : 'Part Time';
         //Occupation
         $data['ud_current_occupation']    = $info_obj->Occupation;
         $data['ud_current_occupation_other']    = $info_obj->OccupationOther;
         $data['ud_tutor_experience']    = $info_obj->YearsOfExperience;
         $data['ud_about_yourself']      = $info_obj->SelfDescription;
        // $data['ud_rate_per_hour']       = $info_obj->Username;
         $data['ud_qualification']       = $info_obj->Qualifications;
        
        // $data['ud_client_status_2']     = $info_obj->Username;
        $data['u_country_id']           = 150;

        /*if ($i_decode->data[0]->id == 229) {
            exit();
        }
*/		//$$count==0;
        if ($info_obj->id != 1) {
			
			$count = $count + 1; //add count number
			 echo $count.")\n";
		echo $data['u_username']." | \n";
			 echo $data['u_role']." | \n"; 
			 echo $data['ud_client_status']." | \n"; 
             $executionTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
             echo $executionTime." | \n";
			 echo "<br />\n";
        }
        try {
        $thisInit->ImportData($data);
	        } catch (Exception $e) {
	 	   			echo 'Caught exception: ',  $e->getMessage(), "\n";
				  }
    
	


			}
}
 mig();
?>
