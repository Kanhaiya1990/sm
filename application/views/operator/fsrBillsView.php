<?php $this->load->view('/layouts/commanHeader'); ?>

<script>
function goBack() {
  window.history.back();
}
</script>
        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               <button onclick="goBack();" class="btn btn-xs bg-primary margin"><i class="material-icons">keyboard_return</i></button></a> FSR Bills Report
                            </h2>
                        </div>
                        <div class="body">
                             
                            <div class="table-responsive">
                                <table style="font-size:12px;" class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No</th>
                                            <th>Bill Date</th>
                                            <th>Bill No.</th>
                                            <th>Retailer </th>
                                            <th>Retailer Code</th>
                                            <th>Salesman</th>
                                            <th>Employee</th>
                                            <th>Route </th>
                                            <th>Bill Amount </th>
                                            <th>SRAmt</th>
                                            <th>FSR Date</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S. No</th>
                                             <th>Bill Date</th>
                                             <th>Bill No.</th>
                                            <th>Retailer </th>
                                            <th>Retailer Code</th>
                                            <th>Salesman</th>
                                            <th>Employee</th>
                                            <th>Route </th>
                                            <th>Bill Amount </th>
                                            <th>SRAmt</th>
                                            <th>FSR Date</th>
                                            <th>View</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="tbl_data">
                                    <?php
                                        $no=0;
                                        if(!empty($fsr)){
                                            foreach ($fsr as $data) 
                                            {
                                                $srData=$this->OperatorModel->getSrDetailData('allocation_sr_details',$data['id']);
                                                // print_r($srData);exit;
                                                $dateForFsr="";
                                                $empName="";
                                                if(!empty($srData)){
                                                    if($srData[0]['allocationId']>0){
                                                        $empData=$this->OperatorModel->getEmpData('allocation_sr_details',$srData[0]['allocationId']);
                                                        // print_r($empData);exit;
                                                        $empName=$empData[0]['ename1'].', '.$empData[0]['ename2'].', '.$empData[0]['ename3'].', '.$empData[0]['ename4'];
                                                    }else if($srData[0]['allocationId']==0){
                                                        $empData=$this->OperatorModel->load('employee',$srData[0]['empId']);
                                                        $empName=$empData[0]['name'];
                                                    }
                                                    $srdt=date_create($srData[0]['createdAt']);
                                                    $srdt= date_format($srdt,'d-M-Y');
                                                    $dateForFsr=$srdt;
                                                }
                                                $no++;
                                                $dt=date_create($data['date']);
                                                $dt= date_format($dt,'d-M-Y');
                                    ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                           
                                            <td><?php echo $dt; ?></td>
                                             <td><?php echo $data['billNo']; ?></td>
                                            <td><?php echo $data['retailerName']; ?></td>
                                             <td><?php echo $data['retailerCode']; ?></td>
                                            <td><?php echo $data['salesman']; ?></td>
                                            <td><?php echo rtrim($empName,', '); ?></td>
                                            <td><?php echo $data['routeName']; ?></td>
                                            <td><?php echo $data['netAmount']; ?></td>
                                            <td><?php echo $data['SRAmt']; ?></td>
                                            <td><?php echo $dateForFsr; ?></td>
                                            <td><a href="<?php echo site_url('AdHocController/billDetailsInfo/'.$data['id']); ?>" class="btn btn-xs  btn-primary" data-toggle="tooltip" data-placement="bottom" title="View Bill"><i class="material-icons">article</i></a>
                                             </td>
                                       </tr>  
                                    <?php          
                                            }
                                        }
                                    ?>
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