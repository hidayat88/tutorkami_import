
<?php 
require_once('config.php.inc');

/*-------------------------------------------------------------------------------------------------------*/
//Set your desire execution timer here.
ini_set('max_execution_time', 30); //Add External PHP max exec timer in seconds. Set 0 for infinity timer.

/*-------------------------------------------------------------------------------------------------------*/
class db {
    var $conn;
	var $count;
	//var $count1;
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
        $displayid          = isset($data['u_id']) ? $this->RealEscape($data['u_id']) : ''; //$this->getRandStr(7); //isset($data['u_displayid']) ? $this->RealEscape($data['u_displayid']) : ''; //

		//Add missing Option
		$LastIpAddress      = isset($data['u_LastIpAddress']) ? $this->RealEscape($data['u_LastIpAddress']) : '';
		$LastVisitedPage	= isset($data['u_LastVisitedPage']) ? $this->RealEscape($data['u_LastVisitedPage']) : '';
		$u_modified_date	= isset($data['u_modified_date']) ? $this->RealEscape($data['u_modified_date']) : '';
		$CreatedOnUtc		= isset($data['u_CreatedOnUtc']) ? $this->RealEscape($data['u_CreatedOnUtc']) : '';
		$PayingClient		= isset($data['u_paying_client']) ? $this->RealEscape($data['u_paying_client']) : 'P'; //P: not check
		$u_id				= isset($data['u_id']) ? $this->RealEscape($data['u_id']) : '';
		$ud_client_status	= isset($data['ud_client_status']) ? $this->RealEscape($data['ud_client_status']) : '';
		$ud_client_status2	= isset($data['ud_client_status_2']) ? $this->RealEscape($data['ud_client_status_2']) : '';
		$ud_proof_of_accepting_terms = isset($data['ud_proof_of_accepting_terms']) ? $this->RealEscape($data['ud_proof_of_accepting_terms']) : '';
		
		//Add Job Import Data
		$j_id				= isset($data['j_id']) ? $this->RealEscape($data['j_id']) : '';
		$j_area				= isset($data['j_area']) ? $this->RealEscape($data['j_area']) : '';
		$j_email			= isset($data['j_email']) ? $this->RealEscape($data['j_email']) : '';
		$j_rate				= isset($data['j_rate']) ? $this->RealEscape($data['j_rate']) : '';
		$j_preferred_date_time	= isset($data['j_preferred_date_time']) ? $this->RealEscape($data['j_preferred_date_time']) : '';
		$j_commission		= isset($data['j_commission']) ? $this->RealEscape($data['j_commission']) : '';
		$j_duration			= isset($data['j_duration']) ? $this->RealEscape($data['j_duration']) : '';
		$j_status			= (isset($data['j_status']) && $data['j_status'] == 0)? 'Closed':'Open'; //0:Close, 1:Open.
		$j_payment_status	= (isset($data['j_payment_status']) && $data['j_payment_status'] == False)? 'Pending':'Paid'; //0:pending, 1:paid
		$j_deadline			= isset($data['j_deadline']) ? $this->RealEscape($data['j_deadline']) : '';
		$j_start_date		= isset($data['j_start_date']) ? $this->RealEscape($data['j_start_date']) : '';
		$j_end_date			= isset($data['j_end_date']) ? $this->RealEscape($data['j_end_date']) : '';
		$j_create_date		= isset($data['j_create_date']) ? $this->RealEscape($data['j_create_date']) : '';
		$j_telephone		= isset($data['j_telephone']) ? $this->RealEscape($data['j_telephone']) : '';
		$j_state			= isset($data['j_state']) ? $this->RealEscape($data['j_state']) : '';
		$j_country_id 		= '150';
		$j_level			= isset($data['j_level']) ? $this->RealEscape($data['j_level']) : '';		
		$j_tutor_hiredID	= isset($data['j_tutor_hiredID']) ? $this->RealEscape($data['j_tutor_hiredID']) : ''; //Added to support tutors applied
		$j_hired_tutor_email= isset($data['j_hired_tutor_email']) ? $this->RealEscape($data['j_hired_tutor_email']) : ''; //Added to support tutors applied
		$ja_job_id			= isset($data1['ja_job_id']) ? $this->RealEscape1($data1['ja_job_id']) : ''; //Added to support tutors applied
		$ja_customer_id		= isset($data1['ja_customer_id']) ? $this->RealEscape1($data1['ja_customer_id']) : ''; //Added to support tutors applied

		//Add Job Translation
		$j_subject			= isset($data['j_subject']) ? $this->RealEscape($data['j_subject']) : '';
		$j_lesson			= isset($data['j_lesson']) ? $this->RealEscape($data['j_lesson']) : '';
		$j_remarks			= isset($data['j_remarks']) ? $this->RealEscape($data['j_remarks']) : '';
		$j_comment			= isset($data['j_comment']) ? $this->RealEscape($data['j_comment']) : '';
		
		// Add Testimonial
		$ut_user_testimonial1 = isset($data['ut_user_testimonial1']) ? $this->RealEscape($data['ut_user_testimonial1']) : '';
		$ut_user_testimonial2 = isset($data['ut_user_testimonial2']) ? $this->RealEscape($data['ut_user_testimonial2']) : '';
		$ut_user_testimonial3 = isset($data['ut_user_testimonial3']) ? $this->RealEscape($data['ut_user_testimonial3']) : '';
		$ut_user_testimonial4 = isset($data['ut_user_testimonial4']) ? $this->RealEscape($data['ut_user_testimonial4']) : '';
		
		//Date Conversion
		if ($CreatedOnUtc != 'null'){
		$CreatedOnUtc = new DateTime($CreatedOnUtc, new DateTimeZone('UTC'));	//Created on date
		$CreatedOnUtc->setTimezone(new DateTimeZone('Asia/Kuala_Lumpur'));
		$CreatedOnUtc=$CreatedOnUtc->format('Y-m-d H:i:s');
		}Else{$CreatedOnUtc = null;}
		if ($u_modified_date != 'null'){
		$u_modified_date = new DateTime($u_modified_date, new DateTimeZone('UTC'));  //Created on date
		$u_modified_date->setTimezone(new DateTimeZone('Asia/Kuala_Lumpur'));
		$u_modified_date=$u_modified_date->format('Y-m-d H:i:s');
		}Else{$u_modified_date = date('Y-m-d H:i:s');}
		/*if (!empty($u_modified_date)){
		$j_start_date = new DateTime($j_start_date, new DateTimeZone('UTC'));  //Created on date
		$j_start_date->setTimezone(new DateTimeZone('Asia/Kuala_Lumpur'));
		$j_start_date1=$j_start_date->format('Y-m-d H:i:s');
		}Else{$j_start_date1 = '';}
		if (!empty($j_end_date)){
		$j_end_date = new DateTime($j_end_date, new DateTimeZone('UTC'));  //Created on date
		$j_end_date->setTimezone(new DateTimeZone('Asia/Kuala_Lumpur'));
		$j_end_date1=$j_end_date->format('Y-m-d H:i:s');
		}Else{$j_end_date1 = '';}*/
		if ($j_create_date!= 'null'){
		$j_create_date = new DateTime($j_create_date, new DateTimeZone('UTC'));  //Created on date
		$j_create_date->setTimezone(new DateTimeZone('Asia/Kuala_Lumpur'));
		$j_create_date=$j_create_date->format('Y-m-d H:i:s');
		}Else{$j_create_date = null;}
		/*if (!empty($j_deadline)){
		$j_deadline = new DateTime($j_deadline, new DateTimeZone('UTC'));  //Created on date
		$j_deadline->setTimezone(new DateTimeZone('Asia/Kuala_Lumpur'));
		$j_deadline1=$j_deadline->format('Y-m-d H:i:s');
		}Else{$j_create_date = '';}*/
		
		//var_dump ($CreatedOnUtc);
		// Job info migration
		$j_sql = "SELECT * FROM ".DB_PREFIX."_job WHERE 
            (
                j_id = '{$j_id}' 
			)"; 
		$j_qry = $thisDB->query($j_sql);
	
		//role to integer number only, use old DB user ID to make ease of other OLD DB table transfer
		if ($j_qry->num_rows == 0) {
			
			$j_sql_state = "SELECT * FROM ".DB_PREFIX."_states WHERE st_name = '{$j_state}'";
			$j_qry_state = $thisDB->query($j_sql_state);		
			$j_ar_row = $j_qry_state->fetch_array(MYSQLI_BOTH);
			$j_st_id = $j_ar_row['st_id'];
			
			
			//Job Applied migrated for tutors who have been awarded for the job
			if ($j_tutor_hiredID!=NULL){
				$ja_sqli1 = "INSERT IGNORE INTO ".DB_PREFIX."_applied_job SET
						aj_j_id = {$j_id},
						aj_u_id = {$j_tutor_hiredID},
						aj_status = 'A',
						aj_date	= '".date('Y-m-d H:i:s')."'			
						";
				$ja_qry1 = $thisDB->query($ja_sqli1);
				}
				//VAR_DUMP($j_tutor_hiredID);
			
			//To skip the old outside categories job
				if ($j_level == "Pre-school") {
					$j_level_Id		= "1";
				}
				else if ($j_level == "Standard 1 - 6 (UPSR)") {
					$j_level_Id		= "3";
				}
				else if ($j_level == "Form 1 - 3 (PMR)") {
					$j_level_Id		= "4";
				}
				else if ($j_level == "Form 4 - 5 (SPM)") {
					$j_level_Id		= "5";
				}
				else if ($j_level == "Primary (Year 1 - 6) / Elementary School (1st - 5th Grade)") {
					$j_level_Id		= "6";
				}
				else if ($j_level == "Secondary (Year 7 - 9) / Middle School (6th - 8th Grade)") {
					$j_level_Id		= "7";
				}
				else if ($j_level == "Year 10 - 11 (IGCSE / O-Levels)") {
					$j_level_Id		= "8";
				}
				else if ($j_level == "Others / Non-Academics") {
					$j_level_Id		= "9";
				}
				else {
					goto Check_validation; //Skip the process to Check_validation
				}
					//$count1 = $count1 + 1;
					if ($j_id==$j_id ){ //To make sure only existed job id will be imported		
					//var_dump($j_level_Id);
						$j_sqli = "INSERT IGNORE INTO ".DB_PREFIX."_job SET
									j_id = '{$j_id}',
									j_jl_id = '".$j_level_Id."',
									j_email = '".$j_email."',
									j_area = '".$j_area."',
									j_rate = '".$j_rate."',
									j_preferred_date_time = '".$j_preferred_date_time."',
									j_commission = '".$j_commission."',
									j_duration = '".$j_duration."',
									j_status = '".$j_status."',
									j_payment_status = '".$j_payment_status."',                 
									j_deadline = '".date('Y-m-d',strtotime($j_deadline))."',
									j_hired_tutor_email = '{$j_hired_tutor_email}',
									j_start_date  = '".date('Y-m-d',strtotime($j_start_date))."',
									j_end_date  = '".date('Y-m-d',strtotime($j_end_date))."',
									j_create_date = '".date('Y-m-d H:i:s',strtotime($j_create_date))."',
									j_telephone = '".$j_telephone."',
									j_state_id = '{$j_st_id}',
									j_country_id = '{$j_country_id}'
									/*j_modified_date = '".date('Y-m-d H:i:s')."'*/
									";
						$j_exe = $thisDB->query($j_sqli);
					}			
			}			
			
			// Job Translation migration
			$jt_sql = "SELECT * FROM ".DB_PREFIX."_job_translation WHERE 
				(
					jt_j_id = '{$j_id}' 
				)"; //Refer to the tk_job id table
	  
			$jt_qry = $thisDB->query($jt_sql);
			
			if ($jt_qry->num_rows == 0) {
				//Add English Translation
				$jt_sqli_en = "INSERT IGNORE INTO ".DB_PREFIX."_job_translation SET
					jt_j_id = '{$j_id}',
					jt_lang_code = 'en',
					jt_subject = '".$j_subject."',
					jt_lessons = '".$j_lesson."',
					jt_remarks = '".$j_remarks."',
					jt_comments = '".$j_comment."'
				";
				$jt_exe_en = $thisDB->query($jt_sqli_en);
				//Add Malay Translation
				$jt_sqli_ms = "INSERT IGNORE INTO ".DB_PREFIX."_job_translation SET
					jt_j_id = '{$j_id}',
					jt_lang_code = 'ms',
					jt_subject = '".$j_subject."',
					jt_lessons = '".$j_lesson."',
					jt_remarks = '".$j_remarks."',
					jt_comments = '".$j_comment."'
				";
				$jt_exe_ms = $thisDB->query($jt_sqli_ms);
			}

		Check_validation:
        // Validation for user information
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
            
			//role to integer number only, use old DB user ID to make ease of other OLD DB table transfer
            if ($qry->num_rows == 0) {
                $sqli = "INSERT IGNORE INTO ".DB_PREFIX."_user SET
					u_id = '{$u_id}',
                    u_email = '".$email."',
                    u_username = '".$username."',
                    u_displayname = '".$displayname."',
                    u_displayid = '".$displayid."',
                    u_gender = '".$gender."',
                    u_profile_pic = '".$profile_pic."',
                    u_status = '".$u_status."',
                    u_password = '".$password."',                 
                    u_create_date = '".date('Y-m-d H:i:s',strtotime($CreatedOnUtc))."',
                    u_role  = '{$role}', 
                    u_country_id  = '{$country_id}',
					ip_address = '".$LastIpAddress."',
					last_page = 'Old DB',
					u_paying_client = '{$PayingClient}',
					u_modified_date = '".date('Y-m-d H:i:s',strtotime($u_modified_date))."'
				";					
                $exe = $thisDB->query($sqli);  	
				$u_displayname_del = $thisDB->query("UPDATE ".DB_PREFIX."_user SET u_displayname = '' WHERE u_displayname = 'null'");
				$u_profile_pic_del = $thisDB->query("UPDATE ".DB_PREFIX."_user SET u_profile_pic = '' WHERE u_profile_pic = 'null'");
				//$last_page_del = $thisDB->query("UPDATE ".DB_PREFIX."_user SET last_page = '' WHERE last_page = 'null'");				
                if ($exe){} else { echo $thisDB->error."<br>"; }
				
				
				
				  
                //Tutors Testimonial migration
				if ($ut_user_testimonial1 !='null'){ //To make sure the 1st testimonial must be filled in
                $t_sqli = "INSERT IGNORE INTO ".DB_PREFIX."_user_testimonial SET
				ut_u_id = '{$u_id}',
				ut_user_testimonial1 = '".$ut_user_testimonial1."',
				ut_user_testimonial2 = '".$ut_user_testimonial2."',
				ut_user_testimonial3 = '".$ut_user_testimonial3."',
				ut_user_testimonial4 = '".$ut_user_testimonial4."',
				ut_create_date = '".date('Y-m-d H:i:s')."'
				";
				$t_exe = $thisDB->query($t_sqli);
				$ut_user_testimonial1_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_testimonial SET ut_user_testimonial1 = '' WHERE ut_user_testimonial1 = 'null'");
				$ut_user_testimonial2_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_testimonial SET ut_user_testimonial2 = '' WHERE ut_user_testimonial2 = 'null'");
				$ut_user_testimonial3_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_testimonial SET ut_user_testimonial3 = '' WHERE ut_user_testimonial3 = 'null'");
				$ut_user_testimonial4_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_testimonial SET ut_user_testimonial4 = '' WHERE ut_user_testimonial4 = 'null'");
				}
				
				
				
				// Convert city name ->city id->state id->state name
				$ud_link_sql = 	"SELECT * FROM `tk_cities` 
								INNER JOIN tk_states ON tk_states.st_id = tk_cities.city_st_id
								WHERE tk_cities.city_name LIKE '%".$udcity."%'
								";
				$ud_link_qry 		= $thisDB->query($ud_link_sql);		
				$ud_link_ar_row 	= $ud_link_qry->fetch_array(MYSQLI_BOTH);
				$ud_state_id 	 	= $ud_link_ar_row['st_id'];
				//var_dump($ud_state_id);
				
				
                if($exe) {
                    $insert_iud = $thisDB->insert_id;
					if ($ud_proof_of_accepting_terms!="null"){	//proof not x
                    $sq = "INSERT IGNORE INTO ".DB_PREFIX."_user_details SET
                        ud_u_id         = '{$u_id}',
                        ud_first_name   = '{$firstname}',
                        ud_last_name    = '{$lastname}',
                        ud_dob          = '{$ud_dob}',
                        ud_phone_number = '{$phonenum}',
                        ud_address      = '{$address}',
                        ud_address2     = '{$address2}',
                        ud_country      = '{$udcountry}',
                        ud_state        = '{$ud_state_id}',
                        ud_city         = '{$udcity}',
                        ud_postal_code  = '{$postalco}',
                        ud_current_company = '{$company_name}',
                        ud_race         = '{$race}',
                        ud_marital_status  = '{$marital_status}',
                        ud_nationality  = '{$nationality}',
                        ud_admin_comment = '{$admin_comment}',
                        ud_client_status = '{$ud_client_status}',
						ud_client_status_2 = '{$ud_client_status2}',
                        ud_tutor_experience = '{$tutor_experience}',
                        ud_current_occupation = '".ucwords($occupation)."',
                        ud_current_occupation_other = '".ucwords($occupationother)."',
                        ud_about_yourself = '{$about_yourself}',
                        ud_qualification = '{$qualification}',
                        ud_tutor_status = '{$tutor_status}',
						ud_proof_of_accepting_terms = 'files/proof_000{$ud_proof_of_accepting_terms}.jpg'
					";
                    //Modified proof accpeting terms to new system file directory, just copy old images and put into files folder
                    $exe = $thisDB->query($sq);
					$ud_first_name_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_first_name = '' WHERE ud_first_name = 'null' ");
					$ud_last_name_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_last_name = '' WHERE ud_last_name = 'null' ");
					$ud_dob_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_dob = '' WHERE ud_dob = 'null' ");
					$ud_current_company_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_current_company = '' WHERE ud_current_company = 'null' ");
					$ud_tutor_experience_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_tutor_experience = '' WHERE ud_tutor_experience = 'null' ");
					$ud_current_occupation_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_current_occupation = '' WHERE ud_current_occupation = 'null' ");
					$ud_current_occupation_other_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_current_occupation_other = '' WHERE ud_current_occupation_other = 'null' ");
					$ud_about_yourself_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_about_yourself = '' WHERE ud_about_yourself = 'null' ");
					$ud_qualification_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_qualification = '' WHERE ud_qualification = 'null' ");
					}
					
					if ($ud_proof_of_accepting_terms=="null"){ //proof is x
                    $sq1 = "INSERT IGNORE INTO ".DB_PREFIX."_user_details SET
                        ud_u_id         = '{$u_id}',
                        ud_first_name   = '{$firstname}',
                        ud_last_name    = '{$lastname}',
                        ud_dob          = '{$ud_dob}',
                        ud_phone_number = '{$phonenum}',
                        ud_address      = '{$address}',
                        ud_address2     = '{$address2}',
                        ud_country      = '{$udcountry}',
                        ud_state        = '{$ud_state_id}',
                        ud_city         = '{$udcity}',
                        ud_postal_code  = '{$postalco}',
                        ud_current_company = '{$company_name}',
                        ud_race         = '{$race}',
                        ud_marital_status  = '{$marital_status}',
                        ud_nationality  = '{$nationality}',
                        ud_admin_comment = '{$admin_comment}',
                        ud_client_status = '{$ud_client_status2}',
						ud_client_status_2 = '{$ud_client_status}',
                        ud_tutor_experience = '{$tutor_experience}',
                        ud_current_occupation = '".ucwords($occupation)."',
                        ud_current_occupation_other = '".ucwords($occupationother)."',
                        ud_about_yourself = '{$about_yourself}',
                        ud_qualification = '{$qualification}',
                        ud_tutor_status = '{$tutor_status}',
						ud_proof_of_accepting_terms = '{$ud_proof_of_accepting_terms}'
					";
                    //Modified proof accpeting terms to new system file directory, just copy old images and put into files folder
                    $exe = $thisDB->query($sq1);
					$ud_first_name_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_first_name = '' WHERE ud_first_name = 'null' ");
					$ud_last_name_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_last_name = '' WHERE ud_last_name = 'null' ");
					$ud_dob_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_dob = '' WHERE ud_dob = 'null' ");
					$ud_address_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_address = '' WHERE ud_address = 'null' ");
					$ud_current_company_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_current_company = '' WHERE ud_current_company = 'null' ");
					$ud_tutor_experience_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_tutor_experience = '' WHERE ud_tutor_experience = 'null' ");
					$ud_current_occupation_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_current_occupation = '' WHERE ud_current_occupation = 'null' ");
					$ud_current_occupation_other_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_current_occupation_other = '' WHERE ud_current_occupation_other = 'null' ");
					$ud_about_yourself_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_about_yourself = '' WHERE ud_about_yourself = 'null' ");
					$ud_qualification_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_qualification = '' WHERE ud_qualification = 'null' ");
					$ud_proof_of_accepting_terms_del = $thisDB->query("UPDATE ".DB_PREFIX."_user_details SET ud_proof_of_accepting_terms = '' WHERE ud_proof_of_accepting_terms = 'null' ");
					}
					

					
					/*$courseSql = "INSERT IGNORE INTO ".DB_PREFIX."_tutor_subject SET
						 trs_u_id  = '{$insert_iud}',
						 trs_tc_id = '{$cid}',
						 trs_ts_id = '{$pid}'										
						 ";
					$courseSql_exe = $thisDB->query($courseSql);*/
					
                   // var_dump($sq);
                    if($exe) {
                       /* $er = 0;
                        //Not working need to fix this
                        if (isset($data['cover_area_state']) && count($data['cover_area_state']) > 0) {
                            foreach ($data['cover_area_state'] as $cid) {
                                if (isset($data['cover_area_city_'.$cid]) && count($data['cover_area_city_'.$cid]) > 0) {
                                    foreach ($data['cover_area_city_'.$cid] as $key => $pid) {
                                        $areaSql = "INSERT IGNORE INTO ".DB_PREFIX."_tutor_area_cover SET
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
                        }*/
                        //Skip import others
                        /*if (isset($data['cover_area_other']) && $data['cover_area_other'] != '') {
                            $thisDB->query("INSERT IGNORE INTO ".DB_PREFIX."_tutor_area_cover SET tac_u_id = '{$insert_iud}', tac_other = '".$data['cover_area_other']."'");
                        }*/
						//Need to fix this
                       /* if (isset($data['tutor_course']) && count($data['tutor_course']) > 0) {
                            foreach ($data['tutor_course'] as $cid) {
                                //var_dump($cid);
                                if (isset($data['tutor_subject_'.$cid]) && count($data['tutor_subject_'.$cid]) > 0) {
                                    foreach ($data['tutor_subject_'.$cid] as $key => $pid) {
                                        $courseSql = "INSERT IGNORE INTO ".DB_PREFIX."_tutor_subject SET
                                         trs_u_id  = '{$insert_iud}',
                                         trs_tc_id = '{$cid}',
                                         trs_ts_id = '{$pid}'										
										 ";

                                        if ($thisDB->query($courseSql)){} else {
                                            echo $thisDB->error."<br>";
                                            $er++;
                                        }
                                    }
                                }
                            }
                        }*/

                        /*if ($er == 0) {
                            $res = array('flag' => 'success', 'message' => 'Thank you for registering with us. Our team will get back to you shortly after verifying your account.', 'data' => $insert_iud);
                        } else {
                            $res = array('flag' => 'error', 'message' => 'Database error: '.$thisDB->error);
                        }*/
                        
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

//Title Column
echo "User ID | Username | Role | Status | Exec Time  | <br />";
 function mig() {
	$count = 0; //add count
	$count1 = 0;
    $thisInit = new db();
	//var_dump($thisInit);
    $thisDB = $thisInit->con_db();


	$result_subject = $thisInit->UltimateCurlSend('http://tutorkami.azurewebsites.net/api/tutorkami/GetSubjectsCourseId'); //get subject ID from localstringresource
	$r_sub_decode = json_decode($result_subject);	//Decode to JSON of subjects course
	$r_sub_dec = json_decode($result_subject,true);		//Member ID decode using json listing
	$max_sub_decode = count($r_sub_dec["data"]);		//Getting Max Subject ID
	//$max_scid = $max_sub_decode["UserId"];	
	//var_dump($max_sub_decode);
	//Add for checking current database to check the max id number and starts with it. To continue import for sure.
	$check_uid_sql1		= "SELECT max(ts_id) as max_ts_id FROM `tk_tution_subject`"; //Select the max number from new database
	$check_uid_sql_exe1 	= $thisDB->query($check_uid_sql1);
	$check_uid_ar_row1	= $check_uid_sql_exe1->fetch_array(MYSQLI_BOTH);
	$check_uid_res1 = $check_uid_ar_row1['max_ts_id'];
	
	$uid_row_sql1 	= "SELECT COUNT(*) as RowCount FROM `tk_tution_subject`";	//select total row count at new database
	$uid_row_exe1	= $thisDB->query($uid_row_sql1);
	$uid_row_count1 	= $uid_row_exe1->fetch_array(MYSQLI_BOTH);
	
	$uid_count_row1 = $uid_row_count1['RowCount']; //Initialize
	
	if ($uid_count_row1 > 1){
			$uid_count_row1 = $uid_row_count1['RowCount']; //Minus 1 due to array_slice if needed
		}	// Use in customer, jobs jobs applied, continue same row to avoid data lost
	else if ($uid_count_row1 <= 1){ //Nothing in DB
			$uid_count_row1 = 0;
		}
		
		// Tuition Course Manual Transfer
	$tu_co_sql = "INSERT IGNORE INTO ".DB_PREFIX."_tution_course (tc_id,tc_title,tc_description,tc_status,tc_country_id,sort_by)
				 VALUES 	(1,'Pre-School','Pre-School','A','150','1'),
						(2,'Tahap 1 (Tahun 1-3)','Lower Primary','A','150','2'),
						(3,'Tahap 2 (UPSR)','Tahap 2 (UPSR)','A','150','3'),
						(4,'Form 1-3 (PT3)','Form 1-3 (PT3)','A','150','4'),
						(5,'Form 4-5 (SPM)','Upper Secondary (SPM)','A','150','5'),
						(6,'Primary (International Syllabus)','Primary (Cambridge)','A','150','6'),
						(7,'Lower Secondary (International Syllabus)','Lower Secondary (IGCSE)','A','150','7'),
						(8,'Year 10-11 (IGCSE)','Year 10-11 (IGCSE)','A','150','8'),
						(9,'Others / Lain-lain','Others / Lain-lain','A','150','9')								
				";
	$tu_co_exe = $thisDB->query($tu_co_sql);		
		
	$counting = $uid_count_row1;
	
	if ($uid_count_row1 < $max_sub_decode){		//Check max subject must be no exceed the total max subject applied
		//Get subjects from id filtered
		foreach (array_slice($r_sub_decode->data,$uid_count_row1) as $key =>$value1){
			$counting = $counting + 1;
			
			$sub_co_res = $thisInit->UltimateCurlSend('http://tutorkami.azurewebsites.net/api/tutorkami/getSubjectsCourse?id='.$value1->Id); 
			$sub_co_dec = json_decode($sub_co_res);
			//$data_sub_co = array($sub_arr);
			$sub_co_info = $sub_co_dec->data[0];
			$data = array($sub_co_info);
			
			$sub_co_rn = $sub_co_info->ResourceName;
			$sub_co_val = $sub_co_info->ResourceValue;			
			$sub_co_arr = explode('.', $sub_co_rn);
				
			$courses_name = strtoupper($sub_co_arr[1]); //Course Name from array
			$subjects_name = $sub_co_val;
		
			if ($courses_name == "PRESCHOOL") {
				$courses_name		= "Pre-school";
			}
			else if ($courses_name == "STANDARD16UPSR") {
				$courses_name		= "Tahap 2 (UPSR)";
			}
			else if ($courses_name == "FORM13PMR"){
				$courses_name 		= "Form 1-3 (PT3)";
			}
			else if ($courses_name == "FORM45SPM") {
				$courses_name		= "Form 4-5 (SPM)";
			}
			else if ($courses_name == "PRIMARYYEAR16ELEMENTARYSCHOOL1ST5THGRADE") {
				$courses_name		= "Primary (International Syllabus)";
			}
			else if ($courses_name == "SECONDARYYEAR79MIDDLESCHOOL6TH8THGRADE") {
				$courses_name		= "Lower Secondary (International Syllabus)";
			}
			else if ($courses_name == "YEAR1011IGCSEOLEVELS") {
				$courses_name		= "Year 10-11 (IGCSE)";
			}
			else if ($courses_name == "OTHERSLAINLAIN") {
				$courses_name		= "Others / Lain-lain";
			}else{
			}
			//var_dump($courses_name);
			//var_dump($subjects_name);
			$select_subject = "SELECT `tc_id` FROM `".DB_PREFIX."_tution_course` WHERE LOWER(`tc_title`) = '".$courses_name."'";
			$sel_sub_qry = $thisDB->query($select_subject);
			$sel_sub_num = $sel_sub_qry->num_rows;
			
			//var_dump($sel_sub_num);
			if ($sel_sub_num > 0) {
				$sel_sub_rows = $sel_sub_qry->fetch_array(MYSQLI_ASSOC	);
				$sel_sub_id = $sel_sub_rows['tc_id'];

				$tui_sub_sql = "INSERT IGNORE INTO ".DB_PREFIX."_tution_subject SET
								ts_id			= '{$counting}',
								ts_tc_id    	= '{$sel_sub_id}',
								ts_title	   	= '{$subjects_name}',
								ts_description 	= '{$subjects_name}',	
								ts_status 		= 'A',
								ts_country_id 	= '150'								
							";
				$tui_sub_exe = $thisDB->query($tui_sub_sql);
			}
			
		}
	} 
	else if ($uid_count_row1 >= $max_sub_decode){ 
	Goto AddUsersJobs; } //Once completed import object, skip task, directly import users and jobs
	
	AddUsersJobs:
	$result = $thisInit->UltimateCurlSend('http://tutorkami.azurewebsites.net/api/tutorkami/GetMemberID'); //get member user id
	$result_j = $thisInit->UltimateCurlSend('http://tutorkami.azurewebsites.net/api/tutorkami/GetJob'); //get job id
   
    $r_id_decode = json_decode($result,true);		//Member ID decode using json listing
	$max_r_decode = max($r_id_decode["data"]);
	$max_uid = $max_r_decode["UserId"];				//Getting Max User ID
	//var_dump($max_uid);
	$r_decode = json_decode($result);				//Member ID decode using json listing
	$r_decode_j = json_decode($result_j);			//Member ID decode using json listing	
	
	//Add for checking current database to check the max id number and starts with it. To continue import for sure.
	$check_uid_sql 		= "SELECT max(u_id) as max_u_id FROM `tk_user`"; //Select the max number from new database
	$check_uid_sql_exe 	= $thisDB->query($check_uid_sql);
	$check_uid_ar_row	= $check_uid_sql_exe->fetch_array(MYSQLI_BOTH);
	$check_uid_res = $check_uid_ar_row['max_u_id'];
	
	$uid_row_sql 	= "SELECT COUNT(*) as RowCount FROM `tk_user`";	//select total row count at new database
	$uid_row_exe	= $thisDB->query($uid_row_sql);
	$uid_row_count 	= $uid_row_exe->fetch_array(MYSQLI_BOTH);
	
	$uid_count_row = $uid_row_count['RowCount']; //Initialize
	if ($uid_count_row > 1){
			$uid_count_row = $uid_row_count['RowCount']-1; // Minus 1 because of array_slice
		}	// Use in customer, jobs jobs applied, continue same row to avoid data lost
	else if ($uid_count_row <= 1){ //Nothing in DB
			$uid_count_row = 0;
		}
	
	
	foreach (array_slice($r_decode->data,$uid_count_row) as $key => $value) { // User Id skip to latest user ID
	//var_dump($value->UserId);
		
	
		$info_res = $thisInit->UltimateCurlSend('http://tutorkami.azurewebsites.net/api/tutorkami/GetMemberInfo?id='.$value->UserId);
		$i_decode = json_decode($info_res);
		$max_data_uid = $value->UserId;
		if ($max_data_uid >=$max_uid){
			Goto End_script;
		}

		$info_obj = $i_decode->data[0];
		$data = array($info_obj);
		
		// Area covered
		$area_covered = $info_obj->AreasCovered;
		$area_arr = explode(';', $area_covered);
		
		$tutor_u_id_ta = $info_obj->id;
		//To avoid clone and redundant data
		$tac_sql = "SELECT * FROM ".DB_PREFIX."_tutor_area_cover WHERE 
		(
			tac_u_id = '{$tutor_u_id_ta}' 
		)"; 
		$tac_qry = $thisDB->query($tac_sql);
		$tac_row = $tac_qry->fetch_array(MYSQLI_BOTH);
		$tac_u_id_check = $tac_row['tac_u_id'];				
		
		 //Tutor User Id using in Tutor Areas covered and tutor subjects
		if ($tutor_u_id_ta ==$tac_u_id_check){ //To skip if already imported
			goto Tutor_Area_End;
		} 
		else{
			foreach ($area_arr as $area) {
				if( $area != 'null' ) {
		  
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
						$thisDB->query("INSERT INGORE INTO ".DB_PREFIX."_states SET st_name = '".$st_name."', st_c_id = '150', st_status = '1'");
						$state_id = $thisDB->insert_id;
					}
					//New code to remove warning
					if (array_search($state_id, array_column ($data, 'cover_area_state'))== false) {
					// if (array_search($state_id, $data['cover_area_state'],false) == false) {
						$data['cover_area_state'][] = $state_id;
						//var_dump($state_id);
					  
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
					  
						$thisDB->query("INSERT IGNORE INTO ".DB_PREFIX."_cities SET city_name = '".$city_name."', city_st_id = '".$state_id."', city_status = '1'");
						$city_id = $thisDB->insert_id;
					   // echo $thisDB->error;
					}
					
					if (array_search($city_id, array_column ($data, 'cover_area_city_'.$state_id))== false) {
					// if (array_search($city_id, $data['cover_area_city_'.$state_id],false) == false) {    
						$data['cover_area_city_'.$state_id][] = $city_id;
					}

					if ($tac_qry -> num_rows == 0){
					
					$areaSql = "INSERT IGNORE INTO ".DB_PREFIX."_tutor_area_cover SET
											 tac_u_id    = '{$tutor_u_id_ta}',
											 tac_st_id   = '{$state_id}',
											 tac_city_id = '{$city_id}'";
					$areaSql_exe = $thisDB->query($areaSql);
					}
				}
			}
		}
		Tutor_Area_End:

		// Course covered to be INSERT IGNORE INTO tutor's subject
		$course_covered = $info_obj->SubjectsTaught;
		$course_arr = explode(';', $course_covered);
		
		$tutor_u_id_ts = $info_obj->id;
		//To avoid clone and redundant data
		$tsc_sql = "SELECT * FROM ".DB_PREFIX."_tutor_subject WHERE 
		(
			trs_u_id = '{$tutor_u_id_ts}' 
		)"; 
		$tsc_qry = $thisDB->query($tsc_sql);
		$tsc_row = $tsc_qry->fetch_array(MYSQLI_BOTH);
		$ts_u_id_check = $tsc_row['trs_u_id'];
		//var_dump($ts_u_id_check);
		//Tutor User Id using in Tutor Areas covered and tutor subjects
		if ($tutor_u_id_ts ==$ts_u_id_check){ //To skip if already imported
		goto Tuition_Courses_End;
		} 
		else{
			foreach ($course_arr as $course) {	
				
				//INSERT IGNORE INTO Tutor's Subject program
				if($course != 'null') {
					$c_arr = explode(':', $course);
					$course_name = $c_arr[0];
					$subject_name = $c_arr[1];
					
					// tuition Course search after added
					/*$tc_sql_course = "SELECT * FROM ".DB_PREFIX."_tution_course WHERE tc_title = '{$course_name}'";
					$tc_qry_course = $thisDB->query($tc_sql_course);		
					$tc_ar_row = $tc_qry_course->fetch_array(MYSQLI_BOTH);
					$tc_co_id = $tc_ar_row['tc_id'];  */
					
					
					//To skip the old outside categories for tuition course
					if ($course_name == "Pre-school") {
						$course_name		= "Pre-school";
					}
					else if ($course_name == "Standard 1 - 6 (UPSR)") {
						$course_name		= "Tahap 2 (UPSR)";
					}
					else if ($course_name == "Form 1 - 3 (PMR)"){
						$course_name = "Form 1-3 (PT3)";
					}
					else if ($course_name == "Form 4 - 5 (SPM)") {
						$course_name		= "Form 4-5 (SPM)";
					}
					else if ($course_name == "Primary (Year 1 - 6) / Elementary School (1st - 5th Grade)") {
						$course_name		= "Primary (International Syllabus)";
					}
					else if ($course_name == "Secondary (Year 7 - 9) / Middle School (6th - 8th Grade)") {
						$course_name		= "Lower Secondary (International Syllabus)";
					}
					else if ($course_name == "Year 10 - 11 (IGCSE / O-Levels)") {
						$course_name		= "Year 10-11 (IGCSE)";
					}
					else if ($course_name == "Others / Non-Academics") {
						$course_name		= "Others / Lain-lain";
					}
					else {
						$course_name=0;
						$course_id = 0;
						$subject_id=0;
						goto Tuition_Courses_End; //Skip the process to Tuition_Courses_End
					}      
					
					// Course Data
					$arcourse_sql = "SELECT `tc_id` FROM `".DB_PREFIX."_tution_course` WHERE LOWER(`tc_title`) = '".strtolower($course_name)."'";
					$arcourse_qry = $thisDB->query($arcourse_sql);
					$arcourse_num = $arcourse_qry->num_rows;
				   
				  
					
					if ($arcourse_num > 0) {
						$arcourse_row = $arcourse_qry->fetch_array(MYSQLI_BOTH);
						$course_id = $arcourse_row['tc_id'];
					} else {
						$course_id = 0;				  
					}
					//Serach for course id
					/*if (array_search($course_id, array_column ($data, 'tutor_course'))) {
					//if (array_search($course_id, $data['tutor_course'], false) == false) {        
						$data['tutor_course'][] = $course_id;
						}*/
					 
					// Subject Data
					$subject_sql = "SELECT `ts_id` FROM `".DB_PREFIX."_tution_subject` WHERE LOWER(`ts_title`) = '".strtolower($subject_name)."' AND `ts_tc_id`='".$course_id."'"; 
					$subject_qry = $thisDB->query($subject_sql);
					$subject_num = $subject_qry->num_rows;
						
					
					if ($subject_num > 0) {
						$subject_row = $subject_qry->fetch_array(MYSQLI_BOTH);
						$subject_id = $subject_row['ts_id'];
					} 
					else { 
					$subject_id = 0; 
					}
					//Search for subject id
					/*if (array_search($subject_id, array_column ($data, 'tutor_subject_'.$course_id))) {
					//if (array_search($subject_id, $data['tutor_subject_'.$course_id], false) == false) {    
						$data['tutor_subject_'.$course_id][] = $subject_id;
						
					} */  
					
					//var_dump($course_id);
					//var_dump($subject_id);
					//Subject search after added
					/*$ts_sql_course = "SELECT * FROM ".DB_PREFIX."_tution_subject WHERE ts_title = '{$subject_name}'";
					$ts_qry_course = $thisDB->query($ts_sql_course);		
					$ts_ar_row = $ts_qry_course->fetch_array(MYSQLI_BOTH);
					$ts_co_id = $ts_ar_row['ts_id']; */
					
						
						if ($tsc_qry -> num_rows == 0){
							//Insert Tutor Subject ID information
							 if ($course_name == 0 && $course_id==0 || $subject_id == 0){
								 goto skip_course; //Skip the no listed course
							 }else {
								//var_dump($tutor_u_id_ts);
								$ts_Sql = "INSERT IGNORE INTO ".DB_PREFIX."_tutor_subject SET
											 trs_u_id  = '{$tutor_u_id_ts}',
											 trs_tc_id = '{$course_id}',
											 trs_ts_id = '{$subject_id}'										
											 ";
								$ts_Sql_exe = $thisDB->query($ts_Sql);   
								$ts_sub_zero_del = $thisDB->query("DELETE FROM `tk_tutor_subject` where trs_ts_id = 0");				
							}
						}skip_course:

					}			
				}
			}
		Tuition_Courses_End:
		
		//$j_decode_arr = $r_decode_j->data[0];
		//$data_jid = array($j_decode_arr);
		//var_dump($data_jid);
		//Add for checking current database to check the max id number and starts with it. To continue import for sure.
		$check_jid_sql = "SELECT COUNT(*) as RowCount FROM `tk_user`";
		$check_jid_sql_exe = $thisDB->query($check_jid_sql);
		$check_jid_ar_row = $check_jid_sql_exe->fetch_array(MYSQLI_BOTH);
		
		$check_jid_res = $check_jid_ar_row['RowCount']; //initialize
		if ($check_jid_res > 1){ //Got data 
			$check_jid_res = $check_jid_ar_row['RowCount'];
		}		
		else if ($check_jid_res <= 1){ //Nothing in DB
			$check_jid_res = 1;
		}

		//var_dump($check_jid_res);
		
		/*$current_jid = $check_jid_res;
		if ($check_jid_res >= $current_jid){ //re apply exec to make sure data transfer cycle complete
			$current_jid = $check_jid_res+1;
		}
		else if ($check_jid_res < $current_jid OR empty($check_jid_res)){
			$current_jid = 1;
		}*/
		//var_dump($check_jid_res);
		
		//$count1 = $count1+1;
		// Add Job Migration
		$jobmap = $thisInit->UltimateCurlSend('http://tutorkami.azurewebsites.net/api/tutorkami/GetJobInfo?id='.$check_jid_res); //Get Job list
		$j_decode = json_decode($jobmap);	//Job Mapping Decode using json listing		
		$job_list = $j_decode->data[0];	//Store decoded Job list to array table
		$data = array($job_list);

		$jobapplied = $thisInit->UltimateCurlSend('http://tutorkami.azurewebsites.net/api/tutorkami/GetCustomerJobMapping?id='.$check_jid_res); //Get Job Appliedlist
		$ja_decode = json_decode($jobapplied,true);	//Job Mapping Decode using json listing

		$j_tutor_hiredID1 = $job_list->TutorHiredId;
		$j_id1			 = $check_jid_res;
		//var_dump($j_id1);
		// Job Applied migration
		$ja_sql = "SELECT * FROM ".DB_PREFIX."_applied_job WHERE 
			(
				aj_j_id = '{$j_id1}' 
			)"; //Refer to the tk_applied_job id table
		$ja_qry = $thisDB->query($ja_sql);
		$ja_row = $ja_qry->fetch_array(MYSQLI_BOTH);
		$ja_id_check = $ja_row['aj_j_id'];
		if ($j_id1==$ja_id_check){ 
			goto Job_Applied_End;	//To skip to Job_Applied_End if already imported
		} else{	
			foreach ($ja_decode as $u => $z){
				foreach ($z as $n => $line){
					$ja_id 			= $line['Job_Id'];
					$ja_customer_id	= $line['Customer_Id'];
							
						if ($ja_qry->num_rows == 0) {			
							//if ($ja_id == $j_id1){ //To import to applied job table
							$ja_sqli = "INSERT IGNORE INTO ".DB_PREFIX."_applied_job SET
									aj_j_id = {$ja_id},
									aj_u_id = {$ja_customer_id},
									aj_status = 'P',
									aj_date	= '".date('Y-m-d H:i:s')."'			
									";
							$ja_exe = $thisDB->query($ja_sqli);
						}
						
					}
				}
			}
		Job_Applied_End:
		
		//Collect Job Info from OLD DB
		$data['j_id']            			= $job_list->id; //Use old DB ID to link Job
		$data['j_level']            		= $job_list->Level; //Level refer to tk_level table
		$data['j_area']            			= $job_list->Area;	//Same as old system
		$data['j_email']    				= $job_list->CustomerEmail; //new system using customer's email not ID
		$data['j_rate']            			= $job_list->Rate; //Rate per hour
		$data['j_preferred_date_time']    	= $job_list->PreferredTimes; //Same as old system
		$data['j_commission']          		= $job_list->Commission; //Same as old system
		$data['j_duration']					= $job_list->DurationOfEngagement; //Same as old system
		$data['j_status']           		= $job_list->StatusId; //new system using Open:0, Close:1 or nego:2
		$data['j_payment_status']         	= $job_list->PaymentStatusId; //New system pending:0, paid:1
		$data['j_deadline']           		= $job_list->PaymentDeadlineUtc; //Same as old system
		$data['j_hired_tutor_email']     	= $job_list->TutorEmail; //New system using tutor's email not ID
		$data['j_remarks']           		= $job_list->Remarks; //Parent remarks? No longer in new job table
		$data['j_start_date']           	= $job_list->StartDateUtc; //Same as old system
		$data['j_end_date']           		= $job_list->EndDateUtc; //Same as old system
		$data['j_comment']           		= $job_list->AdminComment; //No admin Comment Exist on new DB, just comment
		$data['j_create_date']           	= $job_list->CreatedOnUtc; //Same as old system
		$data['j_subject']           		= $job_list->SubJect; //Refer to tk_tutor_subject table, no longer in job table
		//$data['j_create_date']           	= $job_list->DateUtc;	//Shows date only
		$data['j_state']           			= $job_list->State; //New system using ID instead of string
		$data['j_telephone']           		= $job_list->Phone; //Same as old system
		$data['j_lesson']           		= $job_list->Lesson; //new system inside class table
		$data['j_tutor_hiredID']           	= $job_list->TutorHiredId; //added to support job applied
					
		// Collect data for only tutor with activated status 
		if ($info_obj->Type == 'tutor' && $info_obj->TutorRegistrationStatus == 'Activated'){
			$data['ud_client_status_2']      = ($info_obj->Type == 'tutor') ? 'Not Selected' : 'Parent' ;
			$data['ud_address2']             = $info_obj->StreetAddress;	//Address2 for tutor
		} //end if tutor status
		
		// Collect data for only client 
		if ( $info_obj->Type == 'client'){	
			$data['ud_client_status']       = ($info_obj->Type == 'client') ? 'Parent' : 'Not Selected' ;
			$data['ud_address']             = $info_obj->StreetAddress;		//Address for client
		}
		
		$data['u_id']            		= $info_obj->id; //Use old DB ID to link Job later
		$data['u_username']             = $info_obj->Username;
		$data['u_email']                = $info_obj->Email;
		$data['u_gender']               = $info_obj->Gender;
		$data['u_password']             = $info_obj->Password; 
		$data['u_displayname']          = $info_obj->DisplayName;
		$data['u_profile_pic']          = $info_obj->AvatarPictureId;
		$data['u_role']                 = $info_obj->Type == 'tutor' ? '3' : '4';
		$data['u_status']               = $info_obj->TutorRegistrationStatus == 'Activated' ? 'A' : 'P';
		$data['u_password_salt']        = $info_obj->PasswordSalt;
		
		
		$data['ud_first_name']          = $info_obj->FirstName;
		$data['ud_last_name']           = $info_obj->LastName;
		$data['ud_phone_number']        = $info_obj->Phone;
		// $data['ud_postal_code']         = $info_obj->;
		//$data['ud_address']             = $info_obj->StreetAddress;  //Add Address
		// $data['ud_address2']            = $info_obj->;
		$data['ud_country']            = "Malaysia";//$info_obj->Username;
	   
		$data['ud_city']                = $info_obj->City;
		$data['ud_dob']                 = $info_obj->DateOfBirth;
		$data['ud_company_name']        = $info_obj->Company; //Add company
		$data['ud_race']                = $info_obj->Race;
		$data['ud_marital_status']      = ($info_obj->IsMarried == False) ? 'Not Married' : 'Married';       
		$data['ud_nationality']         = "Malaysian";	//hardcode nationality
		$data['ud_admin_comment']       = $info_obj->AdminComment; //add admin comment
		$data['ud_tutor_status']        = ($info_obj->IsFullTime =='True') ? 'Full Time' : 'Part Time';		
		$data['ud_current_occupation']    = $info_obj->Occupation; //Occupation
		$data['ud_current_occupation_other']    = $info_obj->OccupationOther;
		$data['ud_tutor_experience']    = $info_obj->YearsOfExperience;
		$data['ud_about_yourself']      = $info_obj->SelfDescription;

		$data['ud_qualification']       = $info_obj->Qualifications;        
		// $data['ud_client_status_2']     = $info_obj->;
		$data['u_country_id']           = 150;
		//$data['u_state']           		= $info_obj->State;
		$data['u_LastIpAddress']       	= $info_obj->LastIpAddress;	 //Add IP address
		$data['u_LastVisitedPage']     	= $info_obj->LastVisitedPage;	//Not exists in new DB
		$data['u_modified_date']		= $info_obj->LastActivityDateUtc;
		$data['u_CreatedOnUtc']        	= $info_obj->CreatedOnUtc;	//Add Creation Date
		$data['ut_user_testimonial1']   = $info_obj->Testimonial1Id;	//Add Testimonial 1
		$data['ut_user_testimonial2']   = $info_obj->Testimonial2Id;	//Add Testimonial 2
		$data['ut_user_testimonial3']   = $info_obj->Testimonial3Id;	//Add Testimonial 3
		$data['ut_user_testimonial4']   = $info_obj->Testimonial4Id;	//Add Testimonial 4
		$data['ud_proof_of_accepting_terms']   = $info_obj->IdentityProofPictureId; //Add identity proof of accepting terms
		
		
	//The ID at 1st column is fixed to takeoff the redundant and re-add of the rows. The migration is manually assigned
			// Job Level Manual ID creation
			$jl_sqli = "INSERT IGNORE INTO ".DB_PREFIX."_job_level (jl_id,jl_status,jl_country_id,jl_create_date,jl_modified_date)
						VALUES 	(1,'A','150','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'),
								(2,'A','150','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'),
								(3,'A','150','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'),
								(4,'A','150','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'),
								(5,'A','150','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'),
								(6,'A','150','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'),
								(7,'A','150','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'),
								(8,'A','150','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."'),
								(9,'A','150','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."') 
				";
			$jl_exe = $thisDB->query($jl_sqli);
			// Job Level Manual Transfer (Job Level Translation)
			$jlt_sqli = "INSERT IGNORE INTO ".DB_PREFIX."_job_level_translation (jlt_id,jlt_jl_id,jlt_lang_code,jlt_title,jlt_description)
						VALUES 	(1,1,'en','Pre-School','Pre-School'),
								(2,1,'BM','Pra-Sekolah','Pra-Sekolah'),
								(3,2,'en','Standard 1 (Year 1-3)','Standard 1 (Year 1-3)'),
								(4,2,'BM','Tahap 1 (Tahun 1-3)','Tahap 1 (Tahun 1-3)'),
								(5,3,'en','Standard 2 (UPSR)','Standard 2 (UPSR)'),
								(6,3,'BM','Tahap 2 (UPSR)','Tahap 2 (UPSR)'),
								(7,4,'en','Form 1-3 (PT3)','Form 1-3 (PT3)'),
								(8,4,'BM','Tingkatan 1-3 (PT3)','Tingkatan 1-3 (PT3)'),
								(9,5,'en','Form 4-5 (SPM)','Form 4-5 (SPM)'),
								(10,5,'BM','Tingkatan 4-5 (SPM)','Tingkatan 4-5 (SPM)'),
								(11,6,'en','Primary (International Syllabus)','Primary (International Syllabus)'),
								(12,6,'BM','Primary (Sukatan Pelajaran Antarabangsa)','Primary (Sukatan Pelajaran Antarabangsa)'),
								(13,7,'en','Lower Secondary (International Syllabus)','Lower Secondary (International Syllabus)'),
								(14,7,'BM','Lower Secondary (Sukatan Pelajaran Antarabangsa)','Lower Secondary (Sukatan Pelajaran Antarabangsa)'),
								(15,8,'en','Year 10-11 (IGCSE)','Year 10-11 (IGCSE)'),
								(16,8,'BM','Tahun 10-11 (IGCSE)','Tahun 10-11 (IGCSE)'),
								(17,9,'en','Others','Others'),
								(18,9,'BM','Lain-lain','Lain-lain')
			";
			$jlt_exe = $thisDB->query($jlt_sqli);
		//To Stop data at certain record
		/*if ($i_decode->data[0]->id == 50) {
			exit();
		}*/

		if ($info_obj->id != 1) {
			
			$count = $count + 1; //add count number
			
			 echo $data['u_id'].")\n";
			 echo $data['u_username']." | \n";
			 echo $data['u_role']." | \n"; 
			 //echo $data['ud_client_status']." | \n"; 
			 $executionTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
			 echo $executionTime." |\n";
			 //echo "<br />\n";
			 echo "| <i>Total Rows Executed: <strong>".$count." </strong></i>| ";
		echo " <i>Total Rows Imported in DB: <strong>".$check_jid_res."</strong></i><br />";
		}
				
		try {		
		$thisInit->ImportData($data);
		} catch (Exception $e) {
				echo "Caught exception: ",  $e->getMessage(), "\n";				
		}
	}
		End_script:
		echo "Migration completed and all data successfully transferred! Well Done!";
	
}
 mig();
?>
