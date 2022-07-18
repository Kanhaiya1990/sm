<?php $this->load->view('/layouts/commanHeader'); ?>

    <h1 style="display: none;">Welcome</h1><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                   SR Check Master
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               SR Check Master
                            </h2>
                        </div>
                        <div class="body">
                            <div id="res"></div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example DataTable display nowrap" id="example" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Item</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>GK SR</th>
                                            <th>Edit</th>
                                            <th>Approved/Disapproved</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Bill No.</th>
                                            <th>Retailer</th>
                                            <th>Item</th>
                                            <th>MRP</th>
                                            <th>Billed Qty</th>
                                            <th>GK SR</th>
                                            <th>Edit</th>
                                            <th>Approved/Disapproved</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                   <?php
                                        $no=0;
                                        
                                        foreach ($billDetail as $data) 
                                          { 
                                             $no++; 
                                    ?>
                                            <tr>
                                                <td><?php echo $no;?></td>
                                               <!-- <td id="billId" style="display: none;"><?php echo $data['id'];?></td> -->
                                                <td id="billNo">
                                                    <?php echo $data['billNo'];?>
                                                </td>
                                               <td><?php 
                                                $retailerName=substr($data['retailerName'], 0, 30);
                                                echo $retailerName;?></td>
                                               
                                                <td><?php echo $data['productName'];?></td>
                                                <td align="right"><?php echo $data['mrp'];?></td>
                                                <td align="right"><?php echo $data['qty'];?></td>
                                               
                                                <td align="right"><?php echo $data['fsReturnQty'];?></td>
                                                
                                                <td>
                                            <button style="font-size : 12px;" type="" class="btn-primary waves-effect">
                                                    <i style="font-size : 12px;" class="material-icons">save</i> 
                                                    <span class="icon-name">
                                                    Ok
                                                    </span>
                                                </button> 
                                            <a class="iframe" href="<?php echo base_url().'index.php/manager/SrCheckController/SrCheckEdit/'.$data['id'];?>">
                                                <button style="font-size : 12px;" class="btn-primary waves-effect" data-type="basic"><i style="font-size : 12px;" class="material-icons">edit</i><span>Edit</span></button>
                                            </a> 
                                        </td>
                                         <td>
                                            <button style="font-size : 12px;" type="" class="btn-primary waves-effect">
                                                    <i style="font-size : 12px;" class="material-icons">done</i> 
                                                    <span class="icon-name">
                                                    Yes
                                                    </span>
                                                </button> 
                                            <button style="font-size : 12px;" type="" class="btn-primary waves-effect">
                                                    <i style="font-size : 12px;" class="material-icons">cancel</i> 
                                                    <span class="icon-name">
                                                    No
                                                    </span>
                                                </button> 
                                         </td>
                                                
                                           </tr>
                                     <?php
                                            
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
<?php $this->load->view('/layouts/footerDataTable'); ?>

    
