<?php 

require_once('includes/head.php');



$findCity = $stateID = $cityID = '';

$findSubj = $levelID = $subjectID = '';



if (isset($_GET['location_id']) && $_GET['location_id'] != '') {

   $splitLocIDs = explode('||', $_GET['location_id']);

   $stateID = $splitLocIDs[0];

   $cityID = $splitLocIDs[1];   

}

if (isset($_GET['subject_id']) && $_GET['subject_id'] != '') {

   $splitSubIDs = explode('||', $_GET['subject_id']);

   $levelID = $splitSubIDs[0];

   $subjectID = $splitSubIDs[1];

}



if (count($_POST) > 0) {

  $data = $_POST;

  

  $output = system::FireCurl(SEACRH_TUTOR_URL, "POST", "JSON", $data);

 // var_dump($output);

  $search = $output->data;

  

} else {

  $data = array('subject' => $_GET['subject'], 'location' => $_GET['location']);

  

  if ($stateID != '') {

     $data['cover_area_state'][] = $stateID;

  }

  if ($stateID != '' && $cityID != '') {

     $data['cover_area_city_'.$stateID][] = $cityID;

  }

  if ($stateID != '' && $cityID == '') {

     $data['location_other'] = 1;

  }



  if ($levelID != '') {

     $data['tutor_course'][] = $levelID;

  }

  if ($levelID != '' && $subjectID != '') {

     $data['tutor_subject_'.$levelID][] = $subjectID;

  }

  if ($levelID != '' && $subjectID == '') {

     $data['subject_other'] = 1;

  }

  



  //$output = system::FireCurl(SEACRH_TUTOR_URL, "POST", "JSON", $data);//punca slow view more tutor

  //var_dump($output);exit();

 // $search = $output->data;

}

if (isset($cityID) && $cityID != '') {

   $findCity = system::FireCurl(LIST_CITY_URL."?city_id=".urlencode($cityID));

}

if (isset($subjectID) && $subjectID != '') {

   $findSubj = system::FireCurl(LIST_SUBJECT_URL."?subject_id=".urlencode($subjectID));

}



$total_result = count($search);
//include('includes/headernonmobile.php');
include('includes/header.php');

//require_once('admin/classes/user.class.php');
//$_SESSION['getPage'] = "Search Tutor";

?>

<!--<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">-->

<script> 

   function tickAll(className) {

      $(className).prop('checked', true);

   }



   function untickAll(className) {

      $(className).prop('checked', false);

   }



   function toggleOther(ele, id) {

      if (ele.checked) {

         $('[name^="'+id+'"]').parent('.col-md-12').show();

      } else {

         $('[name^="'+id+'"]').parent('.col-md-12').hide();

      }

   }



   function check_parent(ele) {



      var parentID = $(ele).data('pid');

      var parentName = $(ele).data('pname');

      var childName = $(ele).data('cname');

      var checkboxes = document.getElementsByTagName('input');

      

      if (ele.checked) {

         for (var i = 0; i < checkboxes.length; i++) {

             if (checkboxes[i].type == 'checkbox' && checkboxes[i].id == parentName+parentID){

               checkboxes[i].checked = true;

            }

         }

      } else {

        for (var i = 0; i < checkboxes.length; i++) {                

            if (checkboxes[i].type == 'checkbox' && checkboxes[i].id == parentName+parentID) {

               if ($('input[name^="'+childName+parentID+'"]:checked').length == 0) {

                  checkboxes[i].checked = false;

               }

               

            }

         }

      }

   }



   function get_cities(StateId, CityId) {

      $.ajax({

         url: "front_ajax_call.php",

         method: "POST",

         data: {action: 'get_cities', state_id: StateId, city_id: CityId}, 

         success: function(result){

            if (result == '') {

               $('.city_check_uncheck_area').hide();

            } else{

               $('.city_check_uncheck_area').show();

            }

            

            $('.city-area').html(result);

            $('.showHide, .showHide .dropPop').show();

            $('#hider, #loadermodaldiv').hide();
      
      <!--<?php
      
       // if(isset($_GET['location_id']))
        //{
        //  echo "$(city_check_".$_GET['location_id'].").prop('checked', true);";
       // }
        
       //?> -->

         }

      });

   }



   function get_subjects(LevelId, SubjectId) {

      $.ajax({

         url: "front_ajax_call.php",

         method: "POST",

         data: {action: 'get_subjects', level_id: LevelId, subject_id: SubjectId}, 

         success: function(result){

            if (result == '') {

               $('.subject_check_uncheck_area').hide();

            } else{

               $('.subject_check_uncheck_area').show();

            }

            $('.subject-area').html(result);

            $('.levelShowHide, .levelShowHide .dropPop').show();

            $('#hider, #loadermodaldiv').hide();
      
     <!-- <?php
      
      /*  if(isset($_GET['subject_id']))
        {
          echo "$(subject_check".$_GET['subject_id'].").prop('checked', true);";
        }
        */
     // ?> -->

         }

      });

   }

   

   $(document).ready(function(){



      $('#state_drop').on('change', function(){

         $('#hider, #loadermodaldiv').show();

         var StateId = $(this).val();

         get_cities(StateId, '');

      });



      $('#level_drop').on('change', function(){

         $('#hider, #loadermodaldiv').show();

         var LevelId = $(this).val();

         get_subjects(LevelId, '');

      });

      // $(".dropbox").click(function(){

      //    // $(this).next('.dropPop').stop();

      //    $(this).next('.dropPop').slideToggle("slow");

      // });

   });

</script>



<section class="search_tutor myform">

   <div class="main-body">

      <div class="container">



         <h1 class="text-center text-uppercase blue-txt"><?php echo SEARCH_TUTOR; ?></h1>

         <hr>

         <div class="col-md-offset-2 col-md-8">

          <div class="clearfix"></div>
            
            <form method="post" id="filter_user">

               <input type="hidden" name="action" value="search_tutor">

               <input type="hidden" name="subject" value="<?php echo isset($_REQUEST['subject']) ? $_REQUEST['subject']: '';?>">

               <input type="hidden" name="location" value="<?php echo isset($_REQUEST['location']) ? $_REQUEST['location']: '';?>">

               <div class="row">

                  <div class="col-md-3"><span class="org-txt text-uppercase"><strong><?php echo STATE; ?>:</strong></span></div>

                  <div class="col-md-9">

                     <div class="form-group">

                        <select class="form-control" name="cover_area_state[]" id="state_drop">

                           <option value=""><?php echo SEARCH_TUTOR_SELECT_STATE; ?></option>

                           <?php 

                           $getAllCountries = system::FireCurl(LIST_COUNTRIES_URL.'?country_id=150');

                           if ($getAllCountries->flag == 'success' && count($getAllCountries->data) > 0) {

                              $i = 0;

                              foreach ($getAllCountries->data as $key => $country) {

                                 // Get State By Country Id

                                 $getCountryWiseStates = system::FireCurl(LIST_STATE_URL.'?country_id='.$country->c_id);

                                 if ($getCountryWiseStates->flag == 'success' && count($getCountryWiseStates->data) > 0) {

                                    foreach ($getCountryWiseStates->data as $key => $state) {

                                       $st_selected = '';

                                       if ($findCity != '') {

                                          foreach ($findCity->data as $cities) {

                                             if ($cities->city_st_id == $state->st_id) {

                                                $st_selected = 'selected';

                                                echo '<script>
                                                $(document).ready(function(){
                                                  get_cities("'.$state->st_id.'", "'.$cities->city_id.'");
                                                });
                                                </script>';

                                             } else {

                                                $st_selected = '';

                                             }

                                          }

                                       } elseif (isset($stateID) && $stateID == $state->st_id) {

                                          $st_selected = 'selected';

                                          echo '<script>
                                          $(document).ready(function(){
                                            get_cities("'.$state->st_id.'", "");
                                          });
                                          </script>';

                                       }

                           ?>

                           <option value="<?php echo $state->st_id; ?>" <?php echo (isset($_POST['cover_area_state']) && in_array($state->st_id, $_POST['cover_area_state'])) ? 'selected' : $st_selected;?>><?php echo $state->st_name; ?></option>

                           <?php 

                                    }

                                 }

                              }

                           }

                           ?>

                        </select>

                     </div>

                     <div class="form-group">

                       <!-- <div class="showHide" --><?php /*
                          if(!isset($_GET['location_id']))
                          { 
                            echo "style='display: none;'"; 
                          } 
                          else if($_GET['location_id'] == "")
                          { 
                            echo "style='display: none;'"; 
                          } */
                        ?> 
						
						 <div class="showHide" style="display: none;">

                           <div class="dropbox"><?php echo PLEASE_TICK_THE_AREA; ?></div>

                           <div class="dropPop">

                              <div class="row">

                                 <div class="col-md-12 city_check_uncheck_area"><a href="javascript:void(0);" onclick="tickAll('.city_check');">Tick All</a> <a href="javascript:void(0);" onclick="untickAll('.city_check');">Untick All</a></div>

                                 <div class="city-area"></div>

                              </div>

                           </div>

                        </div>

                     </div>

                  </div>

               </div>

               <div class="row">

                  <div class="col-md-3"><span class="org-txt text-uppercase"><strong><?php echo SEARCH_JOB_LEVELS; ?>:</strong></span></div>

                  <div class="col-md-9">

                     <div class="form-group">

                        <select class="form-control" name="tutor_course[]" id="level_drop">

                           <option value="">Choose subject</option>

                           <?php 

                           // Get Course

                           $getCourse = system::FireCurl(LIST_COURSE_URL);

                           if ($getCourse->flag == 'success' && count($getCourse->data) > 0) {

                              $i = 0;
//arsort($getCourse->data);


array_multisort(array_column($getCourse->data, "sort_by"), SORT_ASC, $getCourse->data); 

                              foreach ($getCourse->data as $key => $course) {

                                 $sub_selected = '';

                                 if ($findSubj != '') {

                                    foreach ($findSubj->data as $subjects) {

                                       if ($subjects->ts_tc_id == $course->tc_id) {

                                          $sub_selected = 'selected';

                                          echo '<script>$(document).ready(function(){get_subjects("'.$course->tc_id.'", "'.$subjects->ts_id.'");});</script>';

                                       } else {

                                          $sub_selected = '';

                                       }

                                    }

                                 } elseif (isset($levelID) && $levelID == $course->tc_id) {

                                    $sub_selected = 'selected';

                                    echo '<script>$(document).ready(function(){get_subjects("'.$course->tc_id.'", "");});</script>';

                                 }

                           ?>

                           <option value="<?php echo $course->tc_id; ?>" <?php echo (isset($_POST['tutor_course']) && in_array($course->tc_id, $_POST['tutor_course'])) ? 'selected' : $sub_selected;?>><?php echo $course->tc_title; ?></option>

                           <?php 

                             }

                           }

                           ?>

                        </select>

                     </div>

                     <div class="form-group">

                        <div class="levelShowHide" style="display: none;">

                           <div class="dropbox"><?php echo PLEASE_TICK_THE_SUBJECT; ?></div>

                           <div class="dropPop">

                              <div class="row">

                                 <div class="col-md-12 subject_check_uncheck_area"><a href="javascript:void(0);" onclick="tickAll('.subject_check');">Tick All</a> <a href="javascript:void(0);" onclick="untickAll('.subject_check');">Untick All</a></div>

                                 <div class="subject-area"></div>

                              </div>

                           </div>

                        </div>

                     </div>

                  </div>

               </div>

               <div class="row">

                  <div class="col-md-3"><span class="org-txt text-uppercase"><strong><?php echo GENDER; ?>:</strong></span></div>

                  <div class="col-md-3">

                     <select class="form-control" name="u_gender" id="u_gender">

                        <option value="">All</option>

                        <option value="M"><?php echo MALE; ?> </option>

                        <option value="F"><?php echo FEMALE; ?></option>

                     </select>

                  </div>

                  <div class="col-md-3"><span class="org-txt text-uppercase"><strong><?php echo RACE; ?>:</strong></span></div>

                  <div class="col-md-3">

                     <select class="form-control" name="ud_race" id="ud_race">

                        <option value="">All</option>

                        <option value="Malay">Malay</option>

                        <option value="Chinese">Chinese</option>

                        <option value="Indian">Indian</option>

                        <option value="Others">Others</option>

                     </select>

                  </div>

               </div>

               <div class="row">

                  <div class="col-md-3"> <span class="org-txt text-uppercase"><strong><?php echo OCCUPATION; ?>:</strong></span> </div>

                  <div class="col-md-3">

                     <select class="form-control" name="ud_current_occupation" id="ud_current_occupation">

                        <option value="">All</option>

                        <option value="Full-time tutor"><?php echo FULL_TIME_TUTOR; ?></option>

                        <option value="Kindergarten teacher"><?php echo KINDERGARTEN_TEACHER; ?></option>

                        <option value="Primary school teacher"><?php echo PRIMARY_SCHOOL_TEACHER; ?></option>

                        <option value="Secondary school teacher"><?php echo SECONDARY_SCHOOL_TEACHER; ?></option>

                        <option value="Tuition center teacher"><?php echo TUITION_CENTER_TEACHER; ?></option>

                        <option value="Lacturer"><?php echo LACTURER; ?></option>

                        <option value="Ex-teacher"><?php echo EX_TEACHER; ?></option>

                        <option value="Retired teacher"><?php echo RETIRED_TEACHER; ?></option>

                        <option value="Other"><?php echo OTHER; ?></option>

                     </select>

                  </div>

                  <div class="col-md-3"><span class="org-txt text-uppercase"><strong><?php echo TUTOR_STATUS; ?>:</strong></span> </div>

                  <div class="col-md-3">

                     <select class="form-control" name="ud_tutor_status" id="ud_tutor_status">

                        <option value="">All</option>

                        <option value="'Full Time'"><?php echo FULL_TIME_TUTOR; ?></option>

                        <option value="'Part Time'"><?php echo PART_TIME; ?></option>

                     </select>

                  </div>

               </div>

               <div class="row">

                  <div class="col-md-3"><span class="org-txt text-uppercase"><strong><?php echo WILL_TEACH_AT_TUITION_CENTER; ?>:</strong></span></div>

                  <div class="col-md-3">

                     <select class="form-control" name="tution_center" id="tution_center">

                        <option value="">All</option>

                        <option value="1"><?php echo YES; ?></option>

                        <option value="0"><?php echo NO; ?></option>

                     </select>

                  </div>

               </div>

               <div class="row">

                  <div class=" col-sm-12 col-md-12 search-tb" id="submitsearch" align="center">

                     <!-- <button type="submit" class="btn btn-md search_btn" onclick="cariTutor()"><?php echo BUTTON_SEARCH_TUTOR; ?></button> -->
                     <input type="button" name="submit" class="btn btn-md search_btn" onclick="cariTutor()" value="<?php echo BUTTON_SEARCH_TUTOR; ?>">

                  </div>

               </div>

            </form>
      
      <?php /*
        $fromIndex = false;
        if(isset($_GET['location_id']))
        {
          $fromIndex = true;
          $connect = mysqli_connect(HOSTNAME, DB_USER, DB_PASS, DBNAME);
          $sql = "SELECT city_st_id FROM tk_cities WHERE city_id = '".$_GET['location_id']."'";
          $row = mysqli_fetch_array(mysqli_query($connect, $sql));
          
          echo '<script>get_cities("'.$row['city_st_id'].'", ""); $("#state_drop").val("'.$row['city_st_id'].'");</script>'; 
          mysqli_close($connect);
        } 
        if(isset($_GET['subject_id']))
        { 
          $fromIndex = true;
          $connect = mysqli_connect(HOSTNAME, DB_USER, DB_PASS, DBNAME);
          $sql = "SELECT ts_tc_id FROM tk_tution_subject WHERE ts_id = '".$_GET['subject_id']."'";
          $row = mysqli_fetch_array(mysqli_query($connect, $sql));
          
          echo '<script>get_subjects("'.$row['ts_tc_id'].'", ""); $("#level_drop").val("'.$row['ts_tc_id'].'");</script>'; 
          mysqli_close($connect);
        } 
        
        if($fromIndex)
        {
          echo "<script>window.onload = function () { cariTutor(); }</script>";
        }
      */?>

    </div>

         <div class="col-md-12">

            <div class="job-table" style="margin-top:30px;">

               <div class="top">

                <div class="dataTables_info" id="example_info" role="status" aria-live="polite">

                 <!-- <?php echo SEARCH_RESULTS; ?> : <span class="org-txt"><span id="counttutor"></span> Tutor(s) found</span> -->

                  </div>

               </div>

             

				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-responsive text-center" style="background:#fff;" id="dataTable">

                  <thead>

                     <tr class="blue-bg">

                        <td width="10%" align="Center"><?php echo SEARCH_TUTOR_NAME; ?></td>

                        <td width="8%" align="center"><?php echo SEARCH_TUTOR_GENDER; ?></td>

                        <td width="5%" align="center"><?php echo SEARCH_TUTOR_AGE; ?></td>

                      <!--  <th width="10%"><?php // echo SEARCH_TUTOR_RATING; ?></th>-->

                        <td width="10%" align="center"><?php echo SEARCH_TUTOR_CITY; ?></td>

                        <td width="30%" align="center"><?php echo SEARCH_TUTOR_QUALIFICATION; ?></td>

                        <td width="15%" align="center">PROFILE</td>

                     </tr>

                  </thead>
				  
				 <!-- <tbody>

                     <?php /*

                     if(count($search) > 0) {

                        foreach ($search as $key => $row) {                           

                     ?>

                     <tr class="point" onclick="gotoPage('tutor_profile?did=<?php echo $row->u_id;?>')" data-toggle="btnToolTip" data-placement="top" title="Please click to view tutor profile">

                        <td><a><?php echo $row->name;?></a></td>

                        <td><?php echo $row->gender;?></td>

                        <td><?php echo $row->dob;?></td>

                        <td><?php echo $row->rating;?></td>

                        <td><?php echo $row->city_name;?></td>

                        <td><?php echo $row->ud_qualification;?></td>


                     </tr>

                     <?php 

                        }

                     } else { 

                     ?>

                     <tr>

                        <td colspan="7"><?php echo NO_RECORDS_FOUND; ?></td>

                     </tr>

                     <?php } */?>

                  </tbody>-->

               </table>
			   
               <br>

            </div>

         </div>         

      </div>

   </div>

</section>


<script src="js/jquery-1.12.4.js"></script>

<script src="js/jquery.dataTables.min.js"></script>

<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
--><link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.foundation.min.css">   
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.foundation.min.css">  
    
    
<!--<script src="https://code.jquery.com/jquery-3.3.1.js"></script>-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.foundation.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.foundation.min.js"></script>

<script> 

   function tickAll(className) {

      $(className).prop('checked', true);

   }



   function untickAll(className) {

      $(className).prop('checked', false);

   }



   function toggleOther(ele, id) {

      if (ele.checked) {

         $('[name^="'+id+'"]').parent('.col-md-12').show();

      } else {

         $('[name^="'+id+'"]').parent('.col-md-12').show();

      }

   }



   function check_parent(ele) {



      var parentID = $(ele).data('pid');

      var parentName = $(ele).data('pname');

      var childName = $(ele).data('cname');

      var checkboxes = document.getElementsByTagName('input');

      

      if (ele.checked) {

         for (var i = 0; i < checkboxes.length; i++) {

             if (checkboxes[i].type == 'checkbox' && checkboxes[i].id == parentName+parentID){

               checkboxes[i].checked = true;

            }

         }

      } else {

        for (var i = 0; i < checkboxes.length; i++) {                

            if (checkboxes[i].type == 'checkbox' && checkboxes[i].id == parentName+parentID) {

               if ($('input[name^="'+childName+parentID+'"]:checked').length == 0) {

                  checkboxes[i].checked = false;

               }

               

            }

         }

      }

   }



</script>

<script>
    $(document).ready(function(){
     // cariTutor();

      $('#state_drop').on('change', function(){

         $('#hider, #loadermodaldiv').show();

         var StateId = $(this).val();

         $.ajax({

            url: "front_ajax_call.php",

            method: "POST",

            data: {action: 'get_cities', state_id: StateId}, 

            success: function(result){

               if (result == '') {

                  $('.city_check_uncheck_area').hide();

               } else{

                  $('.city_check_uncheck_area').show();

               }

               

               $('.city-area').html(result);

               $('.showHide, .showHide .dropPop').show();

               $('#hider, #loadermodaldiv').hide();

            }

         });

      });



      $('#level_drop').on('change', function(){

         $('#hider, #loadermodaldiv').show();

         var LevelId = $(this).val();

         $.ajax({

            url: "front_ajax_call.php",

            method: "POST",

            data: {action: 'get_subjects', level_id: LevelId}, 

            success: function(result){

               if (result == '') {

                  $('.subject_check_uncheck_area').hide();

               } else{

                  $('.subject_check_uncheck_area').show();

               }

               $('.subject-area').html(result);

               $('.levelShowHide, .levelShowHide .dropPop').show();

               $('#hider, #loadermodaldiv').hide();

            }

         });

      });

   });
</script>

<script>
  //ni yg loadkan location dengan subject

   // $.noConflict();

   // jQuery(document).ready(function($){      
     

   // });

</script>


<!-- luqman
<!-- <script>
  function cariTutor(){
    $('#searchtable').DataTable({
    })
  }
</script> -->
<script>
  function cariTutor(){

    var gender = $("#u_gender").val();
    var ud_race = $("#ud_race").val();
    var current_occupation = $("#ud_current_occupation").val();
    var ud_tutor_status = $("#ud_tutor_status").val();
    var tution_center = $("#tution_center").val();

    var areas = $("#state_drop").val();
    var location = $("#location").val();
    var course = $("#level_drop").val();
    

  var subject_check = [];
        $('.subject_check:checked').each(function(i){
          subject_check[i] = $(this).val();
        });

    var city_check = [];
        $('.city_check:checked').each(function(i){
          city_check[i] = $(this).val();
        });

    var subject = $("#subject").val();

    // alert(gender + ',' + ud_race + ',' + current_occupation + ',' + ud_tutor_status + ',' + tution_center);
    // alert(subject_check);
   /* if(areas == '' || course == ''){
      alert("Please select State and Levels!");
    }else{*/

    if(gender != '' || ud_race != '' || current_occupation != '' || ud_tutor_status != '' || tution_center != '' || areas != '' || course != '' || subject_check != '' || city_check != ''){
      $.ajax({
        method:"POST",
        url:"new_front_ajax_call.php",
        dataType:"json",
        data:{
          data: {
            u_gender:gender,
            ud_race:ud_race,
            ud_current_occupation:current_occupation,
            ud_tutor_status:ud_tutor_status,
            tution_center:tution_center,
            state_drop:areas,
            location:location,
            level_drop:course,
            subject_check: subject_check,
            city_check:city_check,
            subject:subject,
          },
        },
        success:function(response){
          // alert(response.length);
             createTablerow(response);  //bila on ni,dye tak detect table createtablerow
             // console.log(response);
             
             var x = "Tiada Maklumat";
             var y = response.message;

             if (x = y){ 
             document.getElementById("counttutor").innerHTML = "No";

             }else{
             document.getElementById("counttutor").innerHTML = response.length;
             }
        }
      });

    }
 // }
    return false;
  }

   function createTablerow(data){


          $('#dataTable').DataTable({
			responsive: false,
            pageLength: 10,
            language: {
                          "emptyTable":     "Tiada Maklumat Dijumpai!"
                        },			
            paging: true,
            searching: true,
            deferRender: true,
            destroy:true,//elakkan dari error initialise
            //columnDefs: [ {

            //targets: [0, 1, 4, 5, 6], // column or columns numbers

           // orderable: false,  // set orderable for selected columns

           // }],
            data : data,
            
            
            "columns" : [
                        { "data": "u_displayname" },
                        { "data": "u_gender" },
                        { "data": "ud_dob" },
                       // { "data": "rr_rating" },
                        { "data": "ud_city" },
                        { "data": "ud_qualification" },
                        { "data": "u_email",
                            "render": function ( data, type, JsonResultRow, meta ) {
                              var u_displayid = JsonResultRow['u_displayid'];
                              var u_email = JsonResultRow['u_email'];
                              // console.log(JsonResultRow);
                              return '<a href="tutor_profile.php?did='+u_displayid+'" class="view-button" target="_blank"><?php echo VIEW_PROFILE; ?></a>'
                              
                            }
                         }, 
                        
                   ]
          });
        }

</script>
<!-- luqman -->

<!--<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="js/jquery-1.12.4.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>-->

<?php include('includes/footer.php');?>



