<?php $this->load->view('/layouts/commanHeader'); ?>

        <h1 style="display: none;">Welcome</h1><br/><br/><br/><br/>
     <section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Pending SR/FSR Detail
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row m-t-20">
                            <div class="col-md-12">
                                    <form method="post" role="form" action="">
                                        
                                        <label>Company:</label>
                                        <input type="text" list="compList" autocomplete="off" placeholder="select company" id="cmp" name="cmp" value="<?php  echo $cmpName; ?>">
                                         <datalist id="compList">
                                        <?php
                                            foreach($company as $data){
                                                $name=$data['name'];
                                        ?>   
                                        <option value="<?php echo $name;?>"/>
                                        <?php    
                                            }
                                        ?>
                                    </datalist>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </form>

                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-exportable dataTable" data-page-length='100'>
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Date</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                         <tr>
                                            <th>S. No.</th>
                                            <th>Date</th>
                                            <th>Details</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                <?php 
                                    $no=0;
                                    if(!empty($pendingSr)){
                                        foreach ($pendingSr as $data) 
                                        {
                                            $no++; 
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php
                                            $dt=date_create($data['createdAt']);
                                            $dateForLink = date_format($dt,'Y-m-d');
                                            $date = date_format($dt,'d-M-Y');
                                            echo $date;
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('operator/OperatorController/pendingAllocationSrWithDate/'.$dateForLink); ?>"><button class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i></button></a>
                                        </td>
                                   </tr>  
                                <?php
                                            }
                                        }

                                         $no=0;
                                    if(!empty($officePendingSr)){
                                        foreach ($officePendingSr as $data) 
                                        {
                                            $no++; 
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php
                                            $dt=date_create($data['updatedAt']);
                                            $dateForLink = date_format($dt,'Y-m-d');
                                            $date = date_format($dt,'d-M-Y');
                                            echo $date;
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('operator/OperatorController/pendingAllocationSrWithDate/'.$dateForLink); ?>"><button class="btn btn-xs bg-primary margin"><i class="material-icons">visibility</i></button></a>
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

<script type="text/javascript">
    
$( "#cmp").click(function() {
  $( "#cmp" ).select();
});
</script>