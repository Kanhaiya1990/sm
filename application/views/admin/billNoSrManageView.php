<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
           
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             Manage Bill Serial No
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                              <p id="res"></p>
                                <table class="table" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>Serial No Start with</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach($companySerial as $cs){ ?>
                                        <tr id="sr_rep">
                                            <td><?php echo $cs['compName']; ?></td>
                                            <td>
                                              <input type="text" id="srNo<?php echo $cs["cId"]?>" class="form-control" value="<?php echo $cs['serialStartWith']; ?>" style="width:100px">
                                            </td>
                                             <td>
                                                 <!-- <a id="sr_pen_id" href="javascript:void();"> -->
                                                    <button onclick="changeSrNo(this,'<?php echo $cs["id"]?>','<?php echo $cs['cId']; ?>');" class="btn btn-primary waves-effect btn-sm">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name"> Save</span>
                                                    </button>
                                                <!-- </a>                                    -->
                                            </td>
                                        </tr>
                                      <?php } ?>  
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>

<?php $this->load->view('/layouts/footerDataTable'); ?>

 <script type="text/javascript">
    function changeSrNo(e,srId,compId){
      var srValue=document.getElementById('srNo'+compId).value;
        $.ajax({
            type: "POST",
            url:"<?php echo site_url('admin/BillTransactionController/updateBillsSerial');?>",
            data:{"srId" : srId,"compId" : compId,'serialValue':srValue},
            success: function (data) {
                alert('Serial No Updated');
                location.reload(); 
            }  
        });
    }
 </script>