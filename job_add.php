<?php
require_once('includes/head.php'); 
require_once('classes/user.class.php');
require_once('classes/app.class.php');
require_once('classes/job.class.php');
$instApp = new app;
$instJob = new job;
$userInit = new user;

$resLang = $instApp->FetchLanguage();
$resJobLevel = $instJob->FetchJobLevel();
$resStates = $instApp->FetchStatesByCountry(150);

if(count($_POST) > 0){
 $data = $instJob->RealEscape($_REQUEST);
 $res =  $instJob->SaveJob($data);
 if ($res !== false) {
    if (isset($_POST['save'])) {
       header('Location:job-list.php');
       exit();
    } elseif (isset($_POST['save_edit'])) {               
       header('Location:job-add.php?j='.$res);
       exit();
    }       
 }
}

if(isset($_GET['action']) && $_GET['action'] == 'delete_job') {
   if (isset($_GET['j_id']) && $_GET['j_id'] != '') {
      $saveData = $instJob->DeleteJob($_GET['j_id']);
      header('Location:job-list.php');
      exit();
   }   
}

if(isset($_REQUEST['j'])){
  $arrJb  = $instJob->GetJob($_REQUEST['j']);
  $resJbt = $instJob->GetJobTranslationByJob($_REQUEST['j']);
  $arrJbt = $resJbt->fetch_array(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
    <head>
    <?php 
    $title = 'Job Add | Tutorkami';
    require_once('includes/html_head.php'); 
    ?>
    </head>
    <body>
   <div id="wrapper">
   <?php include_once('includes/sidebar.php'); ?>
   <div id="page-wrapper" class="gray-bg">
      <?php include_once('includes/header.php'); ?>
      
      <div class="wrapper wrapper-content animated fadeInRight">
         <div class="row">
            <div class="col-lg-12">
               <form action="" method="post" class="form-horizontal">
                  <input type="hidden" name="j_id" id="j_id" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_id'] : ''; ?>"> 
                  <div class="ibox float-e-margins localization">
                     <div class="ibox-title">
                        <h5>Job  Add</h5>
                        <div class="ibox-tools">
                          <!-- <a href="classes-add.php?job_id=<?php echo $arrJb['j_id'];?>"><button class="btn btn-sm btn-primary sign-btn-box mrg-right-15" name="view_class" type="button">Add Class</button></a> -->
                           <button class="btn btn-sm btn-primary sign-btn-box mrg-right-15" name="save" type="submit">Save</button>
                           <button class="btn btn-sm btn-primary sign-btn-box mrg-right-15" name="save_edit" type="submit">Save and Continue Edit</button>
                           <?php if(isset($_REQUEST['j'])) { ?>
                           <button class="btn btn-sm btn-primary sign-btn-box mrg-right-15" type="button" title="Delete" onClick="if(confirm('Are you sure, you want to remove the job?'))document.location.href='job-add.php?action=delete_job&j_id=<?php echo $arrJb['j_id'];?>'">Delete</button>
                           <?php } ?>
                        </div>
                     </div>
                     <div class="ibox-content">
                      <div class="form-group">
                           <!-- <label class="col-lg-3 control-label">User ID:</label> -->
                           <div class="col-lg-7">
                            <input type="hidden" name="u_id" id="u_id" value="<?php echo $_GET['u_id'];?>">                            
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-3 control-label">Job ID:</label>
                           <div class="col-sm-7">
                               <!-- <p><?php echo isset($_REQUEST['j']) ? $arrJb['j_id'] : ''; ?></p> -->
                               <?php 
                             if (isset($_REQUEST['j'])) {
                              $jobResEm = $userInit->GetJobIdLink($arrJb['j_id']);
                              $jobRowEm = $jobResEm->fetch_array(MYSQLI_ASSOC);
                              if ($jobResEm->num_rows > 0) {
                                // echo '<label class="label label-primary"><a href="classes-add?cl='.$jobRowEm['cl_id'].'" target="_blank" title="ID: '.$jobRowEm['cl_id'].'" style="color:#FFF; text-decoration: none;">View Class</a></label> ';
                                echo '<p>'.$arrJb['j_id'].' <label class="label label-primary"><a href="classes-add?cl='.$jobRowEm['cl_id'].'" target="_blank" title="ID: '.$jobRowEm['cl_id'].'" style="color:#FFF; text-decoration: none;">View Class</a></label></p>';
                              }else{
                                ?>

                                   
                               <p><?php echo isset($_REQUEST['j']) ? $arrJb['j_id'] : ''; ?> <i class="label label-danger">No classes created yet!</i>
                                 <label class="label label-warning"><a href="classes-add.php?job_id=<?php echo $arrJb['j_id'];?>" style="color:#FFF; text-decoration: none;">Add Class</a></label>
                               </p>
                                <?php
                                
                              }
                             }
                             ?>
                           </div>
                        </div>
                        <!--<div class="form-group" id="data_1">-->
						<div class="form-group">
                           <label class="col-sm-3 control-label">Date:</label>
                           <div class="col-sm-7">
                              <div class="input-group date">
                                 <!--<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" required="" name="j_date" value="<?php //echo isset($_REQUEST['j']) ? $arrJb['j_create_date'] : ''; ?>">-->
								 <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" readonly name="j_date" value="<?php if(isset($_REQUEST['j'])){echo $arrJb['j_create_date'];}else{echo date('Y-m-d H:i:s');} ?>">
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Level:</label>
                           <div class="col-lg-7">
                              <select class="form-control" name="j_jl_id">
                                <?php 
                                $resJobLvl = $instJob->FetchJobLevelByLanguage('en');
                                while($arrJobLvl = $resJobLvl->fetch_assoc()){
                                ?>
                                <option value="<?=$arrJobLvl['jlt_jl_id']?>" <?php if(isset($_REQUEST['j'])) echo ($arrJobLvl['jlt_jl_id']==$arrJb['j_jl_id'])?'selected':''?>><?=$arrJobLvl['jlt_title']?></option>
                                <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Subject:</label>
                           <div class="col-lg-7">
                            <input type="text" class="form-control" name="jt_subject[en]" value="<?php echo isset($_REQUEST['j']) ? $arrJbt['jt_subject'] : ''; ?>" required/> 
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label" >Area:</label>
                           <div class="col-lg-7"><input type="text" class="form-control" name="j_area" id="j_area" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_area'] : ''; ?>" required> 
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">State:</label>
                           <div class="col-lg-7">
                              <select class="form-control" name="j_state_id" id="j_state_id" required>
                                 <option value="">Select State</option>
                                 <?php while($arrStates = $resStates->fetch_assoc()){?>
                                 <option value="<?=$arrStates['st_id']?>" <?php if(isset($_REQUEST['j'])) echo ($arrStates['st_id']==$arrJb['j_state_id'])?'selected':''?>><?php echo $arrStates['st_name']?></option>
                                 <?php }?>
                              </select>
                           </div>
                        </div>
                        <!-- luqman -->
                        <div class="form-group">
                           <label class="col-lg-3 control-label" >Email:</label>
                           <div class="col-lg-5"><input type="email" class="form-control" name="j_email" id="j_email" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_email'] : ''; ?>" required> 
                           </div>
                           <div class="col-lg-4">
                             <?php 
                             if (isset($_REQUEST['j'])) {
                              $userResEm = $userInit->GetUserJobAddLink('4', $arrJb['j_email']);
                              $userRowEm = $userResEm->fetch_array(MYSQLI_ASSOC);
                              if ($userResEm->num_rows > 0) {
                                
                                echo '<label class="label label-primary"><a href="manage_user.php?action=edit&u_id='.$userRowEm['u_displayid'].'" target="_blank" title="ID: '.$userRowEm['u_displayid'].'" style="color:#FFF; text-decoration: none;">'.$userRowEm['u_email'].'</a></label> ';
                              }else{
                                echo '<label class="label label-danger">Parent not exists</label> ';
                              }
                             }
                             ?>
                           </div>
                        </div>
                        <!-- luqman -->
                        <div class="form-group">
                           <label class="col-lg-3 control-label" >Telephone:</label>
                           <div class="col-lg-7"><input type="text" class="form-control" name="j_telephone" id="j_telephone" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_telephone'] : ''; ?>" required> 
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label" >Rate:</label>
                           <div class="col-lg-7"><input type="text" class="form-control" name="j_rate" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_rate'] : ''; ?>" required> 
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Lessons:</label>
                           <div class="col-lg-7"><input type="text" class="form-control" name="jt_lessons[en]" value="<?php echo isset($_REQUEST['j']) ? $arrJbt['jt_lessons'] : ''; ?>" required /> 
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Preferred Time & Day:</label>
                           <div class="col-lg-7"><input type="text" class="form-control" name="j_preferred_date_time" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_preferred_date_time'] : ''; ?>" required> 
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label" >Commission:</label>
                           <div class="col-lg-7"><input type="text" class="form-control" name="j_commission" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_commission'] : ''; ?>" required> 
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label" >Duration:</label>
                           <div class="col-lg-7"><input type="text" class="form-control" name="j_duration" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_duration'] : ''; ?>" required> 
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Status:</label>
                           <div class="col-lg-7">
                              <select class="form-control" name="j_status" required>
                                 <option value='open' <?php if(isset($_REQUEST['j'])) echo $arrJb['j_status']=="open"?'selected':''?>>Open</option>
                                 <option value='closed' <?php if(isset($_REQUEST['j'])) echo $arrJb['j_status']=="closed"?'selected':''?>>Closed</option>
                                 <option value='negotiating' <?php if(isset($_REQUEST['j'])) echo $arrJb['j_status']=="negotiating"?'selected':''?>>Negotiating</option>
                              </select>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Payment Status:</label>
                           <div class="col-lg-7">
                              <select class="form-control" name="j_payment_status" required>
                                 <option value='pending' <?php if(isset($_REQUEST['j'])) echo $arrJb['j_payment_status']=="pending"?'selected':''?>>Unpaid</option>
                                 <option value='paid' <?php if(isset($_REQUEST['j'])) echo $arrJb['j_payment_status']=="paid"?'selected':''?>>Paid</option>
                              </select>
                           </div>
                        </div>
                        <div class="form-group" id="date_deadline">
                           <label class="col-sm-3 control-label">Deadline:</label>
                           <div class="col-sm-7">
                              <div class="input-group date">
                                 <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="j_deadline" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_deadline'] : ''; ?>" />
                              </div>
                           </div>
                        </div>
                        <!-- luqman -->
                        <div class="form-group">
                           <label class="col-lg-3 control-label" >Hired Tutor's Email:</label>
                           <div class="col-lg-5"><input type="text" class="form-control" name="j_hired_tutor_email" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_hired_tutor_email'] : ''; ?>" />
                           </div>
                           <div class="col-lg-4">
                            <?php 
                            if(isset($_REQUEST['j']) && $arrJb['j_hired_tutor_email'] != '') {
                              $userRes = $userInit->GetUserJobAddLink('3', $arrJb['j_hired_tutor_email']);
                              $userRow = $userRes->fetch_array(MYSQLI_ASSOC);
                              // var_dump($userRes);die;
                              if ($userRes->num_rows > 0) {
                                // print_r('ada row');die;
                                echo '<label class="label label-primary"><a href="manage_user.php?action=edit&u_id='.$userRow['u_displayid'].'" target="_blank" title="ID: '.$userRow['u_displayid'].'" style="color:#FFF; text-decoration: none;">'.$userRow['u_email'].'</a></label> ';
                              }else{
                                echo '<label class="label label-danger">Tutor not exists</label> ';
                              }
                            }
                            ?>
                           </div>
                        </div>
                        <!-- luqman -->
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Tutor's applying:</label>
                           <div class="col-lg-7">
                              <div class="well">
                                <?php 
                                if (isset($_GET['j']) && $_GET['j'] != '') {                                    
                                  $resAJ = $instJob->JobWiseAppliedJobs($_GET['j']);
                                  if ($resAJ->num_rows > 0) {
                                    while( $j_row = $resAJ->fetch_assoc() ){
                                      echo '<label class="label label-primary"><a href="manage_user.php?action=edit&u_id='.$j_row['u_displayid'].'" target="_blank" title="ID: '.$j_row['u_displayid'].'" style="color:#FFF; text-decoration: none;">'.$j_row['u_email'].'</a></label> ';
                                    }
                                  }
                                }
                                ?>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Remarks:</label>
                           <div class="col-lg-7">
                              <textarea class="form-control" rows="5" name="jt_remarks[en]"><?php echo isset($_REQUEST['j']) ? $arrJbt['jt_remarks'] : ''; ?></textarea>
                           </div>
                        </div>
                        <div class="form-group" id="data_3">
                           <label class="col-sm-3 control-label">Start Date:</label>
                           <div class="col-sm-7">
                              <div class="input-group date">
                                 <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_start_date'] : ''; ?>"  name="j_start_date" />
                              </div>
                           </div>
                        </div>
                        <div class="form-group" id="data_4">
                           <label class="col-sm-3 control-label">End Date:</label>
                           <div class="col-sm-7">
                              <div class="input-group date">
                                 <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="<?php echo isset($_REQUEST['j']) ? $arrJb['j_end_date'] : ''; ?>" name="j_end_date" />
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Created By:</label>
                           <div class="col-lg-7">
                              <textarea class="form-control" name="j_creator_email" readonly="true"><?php echo isset($_REQUEST['j']) ? $arrJb['j_creator_email'] : $_SESSION[DB_PREFIX]['u_email']; ?></textarea>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">Admin's Comment:</label>
                           <div class="col-lg-7">
                              <textarea class="form-control" rows="5" id="jt_comments" name="jt_comments[en]"><?php echo isset($_REQUEST['j']) ? $arrJbt['jt_comments'] : ''; ?></textarea>
                           </div>
                        </div>
                        <div class="form-group mrg-top-30">
                           <div class="col-lg-offset-3 col-lg-9">
                              <button class="btn btn-sm btn-primary sign-btn-box mrg-right-15" name="save" type="submit">Save</button>
                              <button class="btn btn-sm btn-primary sign-btn-box mrg-right-15" name="save_edit" type="submit">Save and Continue Edit</button>
                           </div>
                        </div>
                     </div>               
                  </div>
                </form>
            </div>
         </div>
         <?php include_once('includes/footer.php'); ?>
      </div>
   </div>
   <!-- Mainly scripts -->
   <script src="js/plugins/jeditable/jquery.jeditable.js"></script>
   <script src="js/plugins/dataTables/datatables.min.js"></script>
   <!-- Data picker -->
   <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Date range picker -->
   <script src="js/plugins/daterangepicker/daterangepicker.js"></script>
   <!-- Custom and plugin javascript -->
   <script src="js/plugins/pace/pace.min.js"></script>
   <!-- Image cropper -->
   <script src="js/plugins/cropper/cropper.min.js"></script>
   <!-- Page-Level Scripts -->
   <script>
    $(document).ready(function(){
      allUserInfo();

        $('.dataTables-example').DataTable({
              dom: '<"html5buttons"B>lTfgitp',
              buttons: [
              { extend: 'copy'},
              {extend: 'csv'},
              {extend: 'excel', title: 'ExampleFile'},
              {extend: 'pdf', title: 'ExampleFile'},
      
              {extend: 'print',
              customize: function (win){
                  $(win.document.body).addClass('white-bg');
                  $(win.document.body).css('font-size', '10px');
      
                  $(win.document.body).find('table')
                  .addClass('compact')
                  .css('font-size', 'inherit');
              }
            }
          ]
      
        });
      
          /* Init DataTables */
          var oTable = $('#editable').DataTable();
      
          /* Apply the jEditable handlers to the table */
          oTable.$('td').editable( '../example_ajax.php', {
              "callback": function( sValue, y ) {
                  var aPos = oTable.fnGetPosition( this );
                  oTable.fnUpdate( sValue, aPos[0], aPos[1] );
              },
              "submitdata": function ( value, settings ) {
                  return {
                      "row_id": this.parentNode.getAttribute('id'),
                      "column": oTable.fnGetPosition( this )[2]
                  };
              },
      
              "width": "90%",
              "height": "100%"
          } );
      
      
      });
      
      function fnClickAddRow() {
          $('#editable').dataTable().fnAddData( [
              "Custom row",
              "New row",
              "New row",
              "New row",
              "New row" ] );
      
      }
   </script>
   <script>
      $(document).ready(function(){
      
          var $image = $(".image-crop > img");
          $($image).cropper({
              aspectRatio: 1.618,
              preview: ".img-preview",
              done: function(data) {
                      // Output the result data for cropping image.
                  }
              });
      
          
          $('#data_1 .input-group.date').datepicker({
              todayBtn: "linked",
              keyboardNavigation: false,
              forceParse: false,
              calendarWeeks: true,
              autoclose: true,
              format: "yyyy-mm-dd"
          });
      
          $('#date_deadline .input-group.date').datepicker({
              startView: 0,
              todayBtn: "linked",
              keyboardNavigation: false,
              forceParse: false,
              autoclose: true,
              todayHighlight: true,
              format: "yyyy-mm-dd"
          });
      
          $('#data_3 .input-group.date').datepicker({
              todayBtn: "linked",
              keyboardNavigation: false,
              forceParse: false,
              autoclose: true,
              todayHighlight: true,
              format: "yyyy-mm-dd"
          });
      
          $('#data_4 .input-group.date').datepicker({
              keyboardNavigation: false,
              forceParse: false,
              autoclose: true,
              todayHighlight: true,
              format: "yyyy-mm-dd"
          });
      
          $('#data_5 .input-daterange').datepicker({
              keyboardNavigation: false,
              forceParse: false,
              autoclose: true
          });
      
          $('.clockpicker').clockpicker();
      
          $('input[name="daterange"]').daterangepicker();
      
          $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
      
          $('#reportrange').daterangepicker({
              format: 'yyyy-mm-dd',
              startDate: moment().subtract(29, 'days'),
              endDate: moment(),
              minDate: '01/01/2012',
              maxDate: '12/31/2015',
              dateLimit: { days: 60 },
              showDropdowns: true,
              showWeekNumbers: true,
              timePicker: false,
              timePickerIncrement: 1,
              timePicker12Hour: true,
              ranges: {
                  'Today': [moment(), moment()],
                  'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                  'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                  'This Month': [moment().startOf('month'), moment().endOf('month')],
                  'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              opens: 'right',
              drops: 'down',
              buttonClasses: ['btn', 'btn-sm'],
              applyClass: 'btn-primary',
              cancelClass: 'btn-default',
              separator: ' to ',
              locale: {
                  applyLabel: 'Submit',
                  cancelLabel: 'Cancel',
                  fromLabel: 'From',
                  toLabel: 'To',
                  customRangeLabel: 'Custom',
                  daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                  monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                  firstDay: 1
              }
          }, function(start, end, label) {
              console.log(start.toISOString(), end.toISOString(), label);
              $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          });
      
      
          $(".touchspin1").TouchSpin({
              buttondown_class: 'btn btn-white',
              buttonup_class: 'btn btn-white'
          });
      
          $(".touchspin2").TouchSpin({
              min: 0,
              max: 100,
              step: 0.1,
              decimals: 2,
              boostat: 5,
              maxboostedstep: 10,
              postfix: '%',
              buttondown_class: 'btn btn-white',
              buttonup_class: 'btn btn-white'
          });
      
          $(".touchspin3").TouchSpin({
              verticalbuttons: true,
              buttondown_class: 'btn btn-white',
              buttonup_class: 'btn btn-white'
          });
      
      
      });
      
   </script>

   <script type="text/javascript">
     function allUserInfo(){
      var u_id = $('#u_id').val();
      // alert(u_id);

      if(u_id != ''){
        $.ajax({
          url: "ajax/ajax_call_new.php",
          type: "POST",
          dataType: "json",
          data: {
            dataUJ: {
              u_id : u_id,
            },
          },
          success: function(response){
            console.log(response);

            $('#j_area').val(response.area);
            $('#j_state_id').val(response.state);
            $('#j_email').val(response.email);
            $('#j_telephone').val(response.phoneno);
            $('#jt_comments').val(response.admincomment);


          }
        })
      }else{
      }
     }
   </script>
</body>
</html>
