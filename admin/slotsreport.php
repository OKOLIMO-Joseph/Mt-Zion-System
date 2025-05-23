<?php include('header.php'); ?>
<?php include('session.php'); ?>

<body>
  <?php include('navbar.php'); ?>
  <div class="container-fluid">
    <div class="row-fluid">
      <?php include('sidebar.php'); ?>

      <div class="span9" id="">
        <div class="row-fluid">
          <!-- block -->

          <div class="empty">
            <div class="alert alert-info alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <i class="icon-info-sign"></i> <strong>Note!:</strong> Below is a list of available or scheduled slots
            </div>
          </div>

          <?php
          $result = mysqli_query($conn1, 'SELECT COUNT(*) AS total_slots FROM slotsreserved WHERE eventDate >= now()');
          $row = mysqli_fetch_assoc($result);
          $totalSlots = $row['total_slots'];
          ?>

          <div id="block_bg" class="block">
            <div class="navbar navbar-inner block-header">
              <div class="muted pull-left"><i class="icon-calendar"></i> Slots List</div>
              <div class="muted pull-right">
                Total Slots Available: <span class="badge badge-info"><?php echo $totalSlots; ?></span>
              </div>
            </div>
            <div class="block-content collapse in">
              <div class="span20" id="printableArea">
                <div class="empty">
                  <div class="alert alert-success alert-dismissable">
                    Slots Overview
                  </div>
                </div>

                <table cellpadding="0" cellspacing="0" border="1" class="table" style="width:100%" id="example">
                  <thead>
                    <tr>
                      <th>Event Name</th>
                      <th>Description</th>
                      <th>Total Slots</th>
                      <th>Event Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = mysqli_query($conn1, "SELECT * FROM slotsreserved WHERE eventDate >= now() ORDER BY eventDate ASC") or die(mysqli_error($conn1));
                    while ($row = mysqli_fetch_array($query)) {
                    ?>
                      <tr>
                        <td><?php echo $row['eventName']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td style="text-align: center;"><?php echo $row['slots']; ?></td>
                        <td><?php echo date('Y-m-d', strtotime($row['eventDate'])); ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <a class="btn btn-primary btn-lg" style="background-color: blue;" href="javascript:void(0);" onclick="printPageArea('printableArea')">
                Print report
              </a>
            </div>
          </div>
          <!-- /block -->
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.php'); ?>
  </div>
  <?php include('script.php'); ?>
</body>

</html>
