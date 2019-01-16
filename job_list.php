<?php 
require_once('includes/head.php');

require_once('classes/app.class.php');
require_once('classes/job.class.php');

$instApp = new app;
$instJob = new job;
$resJobLvl = $instJob->FetchJobLevelByLanguage('en-US');
$resStates = $instApp->FetchStatesByCountry(150);
if(isset($_REQUEST['jd'])){
  $res = $instJob->DeleteJob($_REQUEST['jd']);
}
if(isset($_REQUEST['j-search'])){
  $data = $instJob->RealEscape($_REQUEST);
  $resJob = $instJob->SearchJob($data);
  //echo $resJob;
  //exit();
}else{
  $resJob = $instJob->FetchJob();
} 

$resJobEmail = $instJob->FetchJobEmail();

$sorting = (isset($_REQUEST['sort_by'])) ? $_REQUEST['sort_by'] : '0';
$ordering = (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != 0) ? 'asc' : 'desc';
?>
<!DOCTYPE html>
<html>

<head>

  <?php 
  $title = 'Job List | Tutorkami';
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

          <div class="ibox float-e-margins localization">
            <div class="ibox-title">
              <h5>Job List</h5>
              <div class="ibox-tools">                            
               <a href="job-add.php" class="btn btn-primary">Add New</a> 

             </div>
           </div>
           <form action="" method="post" class="form-horizontal">                       
             <div class="ibox-content"> 
               <div class="form-horizontal">     
                <!-- <div class="form-group">
                  <label class="col-lg-3 control-label">Go to Id:</label>

                  <div class="col-lg-7"><input type="text" class="form-control"  name=""> 
                  </div>
                </div> -->

                <div class="form-group">
                  <label class="col-lg-3 control-label">Search Contact Person Email:</label>

                  <div class="col-lg-7"><input type="email" class="form-control"  name="j_email" value="<?php echo isset($_REQUEST['j-search']) ? $data['j_email'] : ''; ?>"> 
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-3 control-label">Search Rate:</label>

                  <div class="col-lg-7"><input type="text" class="form-control"  name="j_rate" value="<?php echo isset($_REQUEST['j-search']) ? $data['j_rate'] : ''; ?>"> 
                  </div>
                </div>


                <div class="form-group">
                  <label class="col-lg-3 control-label">Search Tutor Hired Email:</label>

                  <div class="col-lg-7"><input type="email" class="form-control"  name="j_hired_tutor_email" value="<?php echo isset($_REQUEST['j-search']) ? $data['j_hired_tutor_email'] : ''; ?>"> 
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-3 control-label">Search Phone:</label>

                  <div class="col-lg-7"><input type="text" class="form-control"  name="j_telephone" value="<?php echo isset($_REQUEST['j-search']) ? $data['j_telephone'] : ''; ?>"> 
                  </div>
                </div>


                <div class="form-group" id="data_1">
                  <label class="col-sm-3 control-label">Search Date Posted:</label>
                  <div class="col-sm-7">
                    <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="" placeholder="select date"  name="j_date" value="<?php echo isset($_REQUEST['j-search']) ? $data['j_date'] : ''; ?>">
                    </div></div>
                  </div>

                  <div class="form-group"><label class="col-lg-3 control-label">Search Level:</label>
                    <div class="col-lg-7">
                     <select class="form-control" name="j_jl_id">
                       <option value="">Select Job Level</option>
                      
                      <?php while($arrJobLvl = $resJobLvl->fetch_assoc()){ ?>
                      <option value="<?=$arrJobLvl['jlt_jl_id']?>" <?php if(isset($_REQUEST['j-search'])) echo ($data['j_jl_id']==$arrJobLvl['jlt_jl_id'])?'selected':''?>><?=$arrJobLvl['jlt_title']?></option>
                      <?php } ?>                                       
                    </select>  
                  </div>
                </div>


                <div class="form-group"><label class="col-lg-3 control-label">Search State:</label>
                  <div class="col-lg-7">
                   <select class="form-control" name="j_state_id">
                    <option value="">Select State</option>
                    <?php while($arrStates = $resStates->fetch_assoc()){?>
                     <option value="<?=$arrStates['st_id']?>" <?php if(isset($_REQUEST['j-search'])) echo ($data['j_state_id']==$arrStates['st_id'])?'selected':''?>><?php echo $arrStates['st_name']?></option>
                    <?php } ?>                                       
                  </select>  
                </div>
              </div>


              <div class="form-group"><label class="col-lg-3 control-label">Search Status:</label>
                <div class="col-lg-7">
                 <select class="form-control" name="j_status">
                  <option value="">Select Status</option>
                  <option value='open' <?php if(isset($_REQUEST['j-search'])) echo $data['j_status']=="open"?'selected':''?>>Open</option>
                  <option value='closed' <?php if(isset($_REQUEST['j-search'])) echo $data['j_status']=="closed"?'selected':''?>>Closed</option>
                  <option value='negotiating' <?php if(isset($_REQUEST['j-search'])) echo $data['j_status']=="negotiating"?'selected':''?>>Negotiating</option>
                                                   
                </select>  
              </div>
            </div>

            <div class="form-group"><label class="col-lg-3 control-label">Search Payment Status:</label>
              <div class="col-lg-7">
               <select class="form-control" name="j_payment_status">
                <option value="">Select Payment Status</option>
                <option value='paid' <?php if(isset($_REQUEST['j-search'])) echo $data['j_payment_status']=="paid"?'selected':''?>>Paid</option>
                <option value='unpaid' <?php if(isset($_REQUEST['j-search'])) echo $data['j_payment_status']=="unpaid"?'selected':''?>>Unpaid</option>                                     
              </select>  
            </div>
          </div>

          <div class="form-group" id="data_2">
            <label class="col-sm-3 control-label">Search Payment Deadline:</label>
            <div class="col-sm-7">
              <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="" placeholder="select date"  name="j_deadline" value="<?php echo isset($_REQUEST['j-search']) ? $data['j_deadline'] : ''; ?>">
              </div></div>
            </div>

            <div class="form-group" id="data_3">
              <label class="col-sm-3 control-label">Search Start Date:</label>
              <div class="col-sm-7">
                <div class="input-group date">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="" placeholder="select date"  name="j_start_date" value="<?php echo isset($_REQUEST['j-search']) ? $data['j_start_date'] : ''; ?>">
                </div></div>
              </div>

              <div class="form-group" id="data_4">
                <label class="col-sm-3 control-label">Search End Date:</label>
                <div class="col-sm-7">
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="" placeholder="select date"  name="j_end_date" value="<?php echo isset($_REQUEST['j-search']) ? $data['j_end_date'] : ''; ?>">
                  </div></div>
                </div>


                <div class="form-group"><label class="col-lg-3 control-label">Sort By:</label>
                  <div class="col-lg-7">
                   <select class="form-control" name="sort_by">
                    <option value="0" <?php if(isset($_REQUEST['j-search'])) echo $data['sort_by']=="0"?'selected':''?>>None</option>
                    <option value="1" <?php if(isset($_REQUEST['j-search'])) echo $data['sort_by']=="1"?'selected':''?>>Date</option>
                    <option value="10" <?php if(isset($_REQUEST['j-search'])) echo $data['sort_by']=="10"?'selected':''?>>Payment Deadline</option>
                  </select>  
                </div>
              </div>
              <div class="form-group"><label class="col-lg-3 control-label">Created By:</label>
                <div class="col-lg-7">
                  <select class="form-control" name="j_creator_email">
                    <option value="">Select Creator</option>
                    <?php while($arrJobEmail = $resJobEmail->fetch_assoc()){ ?>
                    <option value="<?php echo $arrJobEmail['email']; ?>" <?php if(isset($_REQUEST['j-search'])) echo $data['j_creator_email']==$arrJobEmail['email']?'selected':''?>><?php echo $arrJobEmail['email']; ?></option>
                    <?php } ?>
                  </select>  
                </div>
              </div>                            

              <div class="form-group">
                <div class="col-lg-offset-3 col-lg-9">
                  <button class="btn btn-sm btn-primary sign-btn-box mrg-right-15" type="submit" name="j-search">Search</button>                                        
                </div>
              </div>


            </div>
          </div>
        </form>
        <div class="ibox-content"> 
         <div id="editable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">

          <div class="row">
           <div class="col-sm-12">
            <!-- <div class="table-responsive"> -->
            <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example activity-table-list " >                                             
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Date</th>
                  <th>Start Date</th>
                  <th>Level</th>
                  <th>Subject</th>
                  <th>Area</th>
                  <th>State</th>
                  <th>Status</th>
                  <th>Payment Status</th>
                  <th>Applied</th>
                  <th>Deadline</th>
                  <th>Edit</th>                                             
                </tr>
              </thead>
              <tbody>
                <?php while($arrJob = $resJob->fetch_assoc()){
                  $defaultLangJob = $instJob->GetDefaultLanguageJob($arrJob['j_id']);
                  $arrJobLvl = $instJob->GetDefaultLanguageJobLevel($arrJob['j_jl_id']);
                  $arrSt = $instApp->GetState($arrJob['j_state_id']);?>
                  
                  <tr class="gradeX">                       
                    <td><?=$arrJob['j_id'];?></td>
                    <td><?=$arrJob['j_create_date'];?></td>
                    <td><?=$arrJob['j_start_date'];?></td>
                    <td><?=$arrJobLvl['jlt_title'];?></td>
                    <td><?=$defaultLangJob['jt_subject']?></td>
                    <td><?=$arrJob['j_area'];?></td>
                    <td><?=$arrSt['st_name'];?></td>
                    <td><?=$arrJob['j_status'];?></td>
                    <td><?=($arrJob['j_payment_status'] == 'pending') ? 'Unpaid' : $arrJob['j_payment_status'];?></td>
                    <td>
                      <?php 
                      $countApplied = $instJob->JobWiseAppliedJobs($arrJob['j_id'])->num_rows;
                      ?>
                      <input type="checkbox" value="" <?php echo ($countApplied > 0) ? 'checked' : ''; ?>></td>
                    <td><?=$arrJob['j_deadline'];?></td>
                    <td class="center">                        
                      <span class="btn-group">
                        <a href="job-add.php?j=<?php echo $arrJob['j_id'];?>" class="gray-text"><button class="btn-white btn edt-btn btn-xs" name="edit">Edit</button></a>
                        <a href="job-list.php?jd=<?php echo $arrJob['j_id'];?>" class="gray-text"><button class="btn-white btn edt-btn btn-xs">Delete</button></a>              
                      </span>
                    </td>

                  </tr>
                  <?php } ?>
                  
                </tbody>

              </table>
              <!-- </div> -->
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

</div>  
<!-- end of wrapper-content part -->    


<?php include_once('includes/footer.php'); ?>
<script src="js/plugins/jeditable/jquery.jeditable.js"></script>
<script src="js/plugins/dataTables/datatables.min.js"></script>
<script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
<!-- Date range picker -->
<script src="js/plugins/daterangepicker/daterangepicker.js"></script>


<!-- Custom and plugin javascript -->

<script src="js/plugins/pace/pace.min.js"></script>
<script src="js/plugins/cropper/cropper.min.js"></script>


<!-- Page-Level Scripts -->
<script>
  $(document).ready(function(){
    $('.dataTables-example').DataTable({
      dom: '<"html5buttons"B>lTfgitp',
      buttons: [
        { extend: 'copy'},
        {extend: 'csv'},
        {extend: 'excel', title: 'ExampleFile'},
        {extend: 'pdf', title: 'ExampleFile'},
        {extend: 'print', customize: function (win){
            $(win.document.body).addClass('white-bg');
            $(win.document.body).css('font-size', '10px');

            $(win.document.body).find('table')
            .addClass('compact')
            .css('font-size', 'inherit');
          }
        }
      ],
      "order": [[ <?php echo $sorting;?>, "<?php echo $ordering;?>" ]]
    });


  });

</script>
<script>
        $(document).ready(function(){

            var $image = $(".image-crop > img")
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

            $('#data_2 .input-group.date').datepicker({
                startView: 1,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy-mm-dd"
            });

            $('#data_3 .input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy-mm-dd"
            });

            $('#data_4 .input-group.date').datepicker({
                minViewMode: 1,
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                todayHighlight: true,
                format: "yyyy-mm-dd"
            });

            $('#data_5 .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy-mm-dd"
            });

             $('.clockpicker').clockpicker();

            $('input[name="daterange"]').daterangepicker();

            $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

            $('#reportrange').daterangepicker({
                format: 'YYYY-MM-DD',
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

</div> 

</div>

<!-- Mainly scripts -->


</body>
</html>
