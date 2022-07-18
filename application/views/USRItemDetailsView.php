<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                   USR Item Details
                </h2>
                
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                 USR Item Details
                            </h2><br />
                            <h2 style="color: red;">
                                <?php foreach ($billsdetails as $data) 
                                {
                                    echo $data['name'];
                                }?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                                <br /> <br />
                                Employee Name   :  <br /> <br />
                                     <input type="text" id="employee" autocomplete="off" list="employee" name="name" class="form-control" placeholder="Employee Name">   
                                    <datalist id="employee">
                                        <?php
                                            foreach($employee as $data){
                                                $name=$data['name'];
                                                echo $name;
                                        ?>   
                                        <option value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                               
                             </h2>
                        </div>
                            
                        <div class="body">
                            <div class="table-responsive">
                                 <form method="post" role="form" action="<?php echo site_url('SrController/updateUSRItem');?>"> 
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>product Name</th>
                                            <th>MRP</th>
                                            <th>SR Quentity</th>
                                            <th>Given Quentity</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>product Name</th>
                                            <th>MRP</th>
                                            <th>SR Quentity</th>
                                            <th>Given Quentity</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                          $no=0;
                                          foreach ($billsdetails as $data) 
                                            {
                                             $no++; 
                                          ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $data['productName']; ?></td>
                                        <td>
                                            <?php echo $data['mrp']; ?>
                                            <input type="hidden" name="mrp" value="<?php echo $data['mrp']; ?>" readonly>
                                        </td>
                                        <td><?php echo $data['returnedQty']; ?></td>
                                        <td>
                                            <input type="text" class="form-control" name="returnedQty" value="<?php echo $data['returnedQty']; ?>">
                                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                        </td>
                                   </tr>  
                                     <?php
                                        }
                                      ?>   
                                    </tbody>
                                </table>
                                 <div class="col-md-12">
                                        <center>
                                            <div class="row clearfix">
                                                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                    <button type="submite" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">save</i> 
                                                        <span class="icon-name">
                                                        Save
                                                        </span>
                                                    </button>  
                                                </div>
                                            </div>
                                        </center>
                                    </div>  
                                <?php form_close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->  
        </div>
    </section>
    <?php $this->load->view('/layouts/footerDataTable'); ?>