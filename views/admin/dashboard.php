<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <?php include __DIR__ . "/../layouts/company_name_header.php"; ?>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1" style="height:70px;"><i class="fas fa-user-friends"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><? echo constant('TR_TOTAL_SHAREHOLDERS'); ?></span>
              <span class="info-box-number">
                <? echo number_format($DashboardInfo[0]['TotalShareHolders']) . ' ' .  constant('TR_PERSONS'); ?>
              </span>
              <span class="info-box-number">
                <? echo number_format($DashboardInfo[0]['TotalShares']) . ' ' . 'Shares'; ?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1" style="height:70px;"><i class="fas fa-user-check"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Approved for Online</span>
              <span class="info-box-number">
                <? echo number_format($DashboardInfo[0]['TotalApprovedForOnline']??'') . ' ' .  constant('TR_PERSONS'); ?>
              </span>
              <span class="info-box-number">
                <?= $DashboardInfo[0]['SharesApprovedforOnline'] ? number_format($DashboardInfo[0]['SharesApprovedforOnline']):'' . ' ' . 'Shares'; ?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1" style="height:70px;"><i class="fas fa-id-card"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Registered</span>
              <span class="info-box-number">
                <? echo number_format($DashboardInfo[0]['TotalDocumentRegistred']) . ' ' .  constant('TR_PERSONS'); ?>
              </span>
              <span class="info-box-number">
                <?= $DashboardInfo[0]['TotalDocumentRegistredShares']? number_format($DashboardInfo[0]['TotalDocumentRegistredShares']):'' . ' ' . 'Shares'; ?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1" style="height:70px;"><i class="fas fa-user-times"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Still To Approve</span>
              <span class="info-box-number">2,000</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>