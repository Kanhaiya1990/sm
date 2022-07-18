<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Debit Confirm</h2>
                    </div>

                    <div class="body">
                        <div class="demo-masked-input">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <b>SR Debit</b>
                                        <input type="text" name="park" id="park" autocomplete="off" class="form-control" value="<?php if(isset($grandTotal)){ echo $grandTotal; } ?>" disabled>
                                    </div> 
                                    <?php foreach($allocatedEmp as $ename){?>
                                        <div class="col-md-3">
                                          <b><?php echo $ename;?></b>
                                          <input type="text" name="park" id="park" autocomplete="off" class="form-control" value="<?php if(isset($total)){
                                              echo $total;}?>">
                                          </div> 
                                      <?php } ?>
                                      <div class="col-md-3">
                                        <b>Add Employee</b>
                                        <select id="pname" name="empName" class="form-control">
                                            <option>---Select Employee---</option>
                                            <?php foreach ($emp as $req_item): ?>
                                              <option value="<?php echo $req_item['id'] ?>"><?php echo $req_item['name'] ?></option>
                                          <?php endforeach ?>    
                                      </select> 
                                  </div> 
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
