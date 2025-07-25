<div class="row-fluid">
  <!-- block -->
  <div class="block">
    <div class="navbar navbar-inner block-header">
      <div class="muted pull-left"><i class="icon-plus-sign icon-large"> Register New member</i></div>
    </div>
    <div class="block-content collapse in">
      <div class="span12">

        <!--------------------form------------------->
        <form method="post">
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <input class="input focused" name="fname" id="focusedInput" type="text" placeholder="First Name" required>
              </p>
            </div>
          </div>
          </p>
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <input class="input focused" name="sname" id="focusedInput" type="text" placeholder="Surname" required>
              </p>
            </div>
          </div>
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <input class="input focused" name="lname" id="focusedInput" type="text" placeholder="Last name" required>
              </p>
            </div>
          </div>
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <select class="input focused" name="gender" id="focusedInput" required="required" type="text">
                  <option value="Select Gender">Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>

                </select>
              </p>
            </div>
          </div>
          </p>
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <input class="input focused" name="birthday" id="focusedInput" type="date" placeholder="Birthday" required>
              </p>
            </div>
          </div>
          </p>
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <input class="input focused" name="residence" id="focusedInput" type="text" placeholder="Residence" required>
              </p>
            </div>
          </div>
          </p>
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <input class="input focused" name="pob" id="focusedInput" type="text" placeholder="place of birth" required>
              </p>
            </div>
          </div>
          </p>

          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <select class="input focused" name="ministry" id="focusedInput" required="required" type="text">
                  <option value="">Select Ministry</option>
                  <option value="None">None</option>
                  <option value="Coristers">Coristers</option>
                  <option value="Deacon">Deacon</option>
                  <option value="Deaconess">Deaconess</option>
                  <option value="Communication">Communication</option>
                  <option value="Education">Education</option>
                  <option value="Family">Family</option>
                  <option value="Sabbath School">Sabbath School</option>
                  <option value="Health">Health</option>
                  <option value="Women">Women</option>
                  <option value="Stewardship">Stewardship</option>
                  <option value="Youth">Youth</option>
                  <option value="Publishing">Publishing</option>
                  <option value="Children">Children</option>
                </select>
              </p>
            </div>
          </div>
          </p>
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <input class="input focused" name="mobile" id="focusedInput" type="text" placeholder="mobile number" required>
              </p>
            </div>
          </div>
          </p>
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <input class="input focused" name="email" id="focusedInput" type="email" placeholder="Email">
              </p>
            </div>
          </div>
          </p>

          </p>
          <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <input class="input focused" name="password" id="focusedInput" type="password" placeholder="password " required>
              </p>
            </div>
          </div>
          </p>
      </div>
      <div class="control-group">
            <p>
            <div class="controls">
              <p>
                <select class="input focused" name="family" id="focusedInput" required="required" type="text">
                  <option value="">Select Family</option>
                  <option value="Bethel">Bethel</option>
                  <option value="Nazareth">Nazareth</option>
                  <option value="Bethlethem">Bethlethem</option>
                  <option value="Jerusalem">Jerusalem</option>
                </select>
              </p>
            </div>
          </div>
          </p>


      <div class="control-group">
        <div class="controls">
          <button name="save" class="btn btn-info" id="save" data-placement="right" title="Click to Save"><i class="icon-plus-sign icon-large"> Save</i></button>
          <script type="text/javascript">
            $(document).ready(function() {
              $('#save').tooltip('show');
              $('#save').tooltip('hide');
            });
          </script>
        </div>
      </div>
      </form>

    </div>
  </div>
</div>
<!-- /block -->

<?php
if (isset($_POST['save'])) {
  $fname = $_POST['fname'];
  $sname = $_POST['sname'];
  $lname = $_POST['lname'];
  $Gender = $_POST['gender'];
  $birthday = $_POST['birthday'];
  $residence = $_POST['residence'];
  $pob = $_POST['pob'];
  $ministry = $_POST['ministry'];
  $mobile = $_POST['mobile'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $family = $_POST['family'];


  $query = @mysqli_query($conn, "select * from members where  mobile = '$mobile'  ") or die(mysqli_error());
  $count = mysqli_num_rows($query);

  $date_now = new DateTime();
  $date2    = new DateTime($birthday);

  if ($date_now <= $date2) { ?>
    <script>
      alert('This member date of birth is no valid');
    </script>
  <?php

    # code...
  } elseif ($count > 0) { ?>
    <script>
      alert('This member Already Exists');
    </script>
  <?php
  } else {
    mysqli_query($conn, "insert into members (fname,sname,lname,Gender,birthday,residence,pob,ministry,mobile,email,thumbnail,password,id,family) 
values('$fname','$sname','$lname','$Gender','$birthday','$residence','$pob','$ministry','$mobile','$email','uploads/none.png','$password','$mobile','$family')") or die(mysqli_error());

    mysqli_query($conn, "insert into activity_log (date,username,action) values(NOW(),'$admin_username','Added member $mobile')") or die(mysqli_error());
  ?>
    <script>
      window.location = "add_members.php";
      $.jGrowl("member Successfully added", {
        header: 'member add'
      });
    </script>
<?php
  }
}
?>