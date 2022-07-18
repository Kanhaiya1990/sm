<?php $this->load->view('/layouts/commanHeader'); ?>

<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Allocation By Manager Master</h2>
            </div>
              <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Allocation By Manager
                            </h2>
                            <h2>
                                <p align="right">
                                  <a href="<?php echo site_url('EmployeeController/Add');?>">
                                    <button type="submit" class="btn bg-primary margin"><i class="material-icons">person_add</i>  Add  </button></a> 
                                </p> 
                            </h2>
                         <!--    <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul> -->
                        </div>
                        <div class="body">
                              <div class="row">
                              	<div class="col-md-2">
                              		Allocation : <label>060717-1</label><br />
                              		Reference:<label> Gobal Market</label>
                              		<p><a href="#" class="btn btn-primary btn-sm btn-block">Cancel Allocation</a></p>
                              	</div>
                                <div class="col-sm-2">
                                    <select class="form-control show-tick">
                                        <option>Tag Employee</option>
                                        <option value="">Ketchup</option>
                                        <option value="">Relish</option>
                                    </select>

                                </div><br />
                                <div class="col-md-4">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td style="color: black;"><b>Particulars </b></td>
                                                    <td style="color: black;"><b>No. of Bills</b></td>
                                                    <td style="color: black;"><b>Amount (Rs)</b></td>
                                                    <td class="text-xs-center" style="color: black;"><b>(%)</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Bills Allocted</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-xs-center"></td>
                                                </tr>
                                                <tr>
                                                    <td>SR/FSR</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-xs-center"></td>
                                                </tr>
                                                <tr>
                                                    <td>Resend</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-xs-center"></td>
                                                    </tr>
                                                <tr>
                                                    <td>Credit</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-xs-center"></td>
                                                </tr>
                                                <tr>
                                                    <td>Partial Payment </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-xs-center"></td>
                                                </tr>
                                                <tr>
                                                    <td>Cash</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-xs-center"></td>
                                                </tr>
                                                <tr>
                                                    <td>Cheque</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-xs-center"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <p class="gray-head" style="background-color: gray;border: 1px; text-align: center;color: black"><b>Reconcile</b></p>
                                    <p>
                                        <a href="#" class="btn btn-sm btn-info">CrdtBills</a>
                                        <a href="#" class="btn btn-sm btn-success pull-right">CasCh</a>
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered"   >
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="4">Current Supply</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>From</td>
                                                    <td class="text-xs-right">
                                                        <input id="from" type="text" class="td-input"/>
                                                    </td>
                                                    <td>To</td>
                                                    <td class="text-xs-right">
                                                        <input id="to" type="text" class="td-input"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-xs-right">
                                                        <input id="Text9" type="text" /></td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-xs-right">
                                                        <input id="Text10" type="text" />
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-xs-right">
                                                        <input id="Text11" type="text" />
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-xs-right">
                                                        <input id="Text12" type="text" />
                                                    </td>
                                                   <td colspan="4" class="text-xs-right">
                                                        <a href="#" class="btn btn-success btn-sm">Add Current Bills</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="2">Past Bills</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2"><input id="Text15" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><input id="Text16" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><input id="Text17" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td><input id="Text18" type="text" /></td>
                                                    <td>
                                                        <a href="#" class="btn btn-success btn-sm">Add Past Bills</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="2">Bounced cheque</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2"><input id="Text15" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><input id="Text16" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><input id="Text17" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td><input id="Text18" type="text" /></td>
                                                    <td>
                                                        <a href="#" class="btn btn-success btn-sm">Add Bounced cheque</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs-center" colspan="2">Temporary Bills</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2"><input id="Text15" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><input id="Text16" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><input id="Text17" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td><input id="Text18" type="text" /></td>
                                                    <td>
                                                        <a href="#" class="btn btn-success btn-sm">Add Temp Bills</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row m-t-20">
                                    <div class="col-md-10">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <tr class="head">
                                                    <td colspan="11" style="background-color: whitesmoke;"><b>Current Supply Bills</b></td>
                                                </tr>
                                                <tr class="gray">
                                                    <th>S No.</th>
                                                    <th>Bill No.</th>
                                                    <th>Bill Date</th>
                                                    <th>Retailer Name</th>
                                                    <th>Bill Amt</th>
                                                    <th>Sale Return</th>
                                                    <th>Past Coll.</th>
                                                    <th>Pending Amt</th>
                                                    <th>Staus</th>
                                                    <th>Today's Coll.</th>
                                                    <th>Remark</th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>NS1812345324</td>
                                                    <td>2-Aug-17</td>
                                                    <td>Baba Sales</td>
                                                    <td>10,345</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>10,345</td>
                                                    <td>10,345</td>
                                                    <td>-</td>
                                                    <td>x</td>
                                                </tr>
                                                <tr class="odd">
                                                    <td>2</td>
                                                    <td>NS1812345325</td>
                                                    <td>22-Aug-17</td>
                                                    <td>Krishna Sales</td>
                                                    <td>2,345</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>2,345</td>
                                                    <td>2,345</td>
                                                    <td>-</td>
                                                    <td>x</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>NS1812345326</td>
                                                    <td>8-Aug-17</td>
                                                    <td>Radhakrishna Store</td>
                                                    <td>2,000</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>2,000</td>
                                                    <td>-</td>
                                                    <td>2,000</td>
                                                    <td>x</td>
                                                </tr>
                                            </table>
                                        </div><br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <!--Past Bills-->
                                            <tr class="head">
                                                <td colspan="11"  style="background-color: whitesmoke;"><b>Past Bills</b></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>S No.</th>
                                                <th>Bill No.</th>
                                                <th>Bill Date</th>
                                                <th>Retailer Name</th>
                                                <th>Bill Amt</th>
                                                <th>Sale Return</th>
                                                <th>Past Collection</th>
                                                <th>Pending Amt</th>
                                                <th>Status</th>
                                                <th>Today's Collection</th>
                                                <th>Remark</th>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>NS1812345324</td>
                                                <td>2-Aug-17</td>
                                                <td>Baba Sales</td>
                                                <td>10,345</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>10,345</td>
                                                <td>status</td>
                                                <td>345</td>
                                                <td>x</td>
                                            </tr>
                                            <tr class="odd">
                                                <td>2</td>
                                                <td>NS1812345354</td>
                                                <td>22-Aug-17</td>
                                                <td>Krishna Sales</td>
                                                <td>2,345</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>2,345</td>
                                                <td>status</td>
                                                <td>-</td>
                                                <td>x</td>
                                            </tr>
                                            <tr>
                                            <td>3</td>
                                                <td>NS1812345455</td>
                                                <td>12-Aug-17</td>
                                                <td>Sai Sales</td>
                                                <td>13,345</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>13,345</td>
                                                <td>status</td>
                                                <td>4,345</td>
                                                <td>x</td>
                                            </tr>
                                            </table>
                                        </div><br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <tr class="head">
                                                <td colspan="11"  style="background-color: whitesmoke;"><b>Bounced Cheques</b></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>S No.</th>
                                                <th>Cheque No.</th>
                                                <th>Cheque Date</th>
                                                <th>Retailer Name</th>
                                                <th>Principal Amt</th>
                                                <th>Penalty</th>
                                                <th>Past Collection</th>
                                                <th>Pending Amt</th>
                                                <th>Status</th>
                                                <th>Today's Collection</th>
                                                <th>Remark</th>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>123456</td>
                                                <td>2-Jul-17</td>
                                                <td>Baba Sales</td>
                                                <td>12,243 </td>
                                                <td>250</td>
                                                <td>2,213 </td>
                                                <td>10,280 </td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>x</td>
                                            </tr>
                                            <tr class="odd">
                                                <td>2</td>
                                                <td>3344567</td>
                                                <td>22-Aug-17</td>
                                                <td>Krishna Sales</td>
                                                <td>2,341</td>
                                                <td>250</td>
                                                <td>-</td>
                                                <td>2,549</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>x</td>
                                            </tr>
                                            </table>
                                        </div><br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                            <!--Delivery Challan -->
                                            <tr class="head">
                                            <td colspan="11"  style="background-color: whitesmoke;"><b>Temporary Bills</b></td>
                                            </tr>
                                            <tr class="gray">
                                                <th>S No.</th>
                                                <th>Bill No.</th>
                                                <th>Bill Date</th>
                                                <th>Retailer Name</th>
                                                <th>Bill Amt</th>
                                                <th>Sale Return</th>
                                                <th>Past Collection</th>
                                                <th>Pending Amt</th>
                                                <th>Status</th>
                                                <th>Today's Collection</th>
                                                <th>Remark</th>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>NS1812345324</td>
                                                <td>2-Aug-17</td>
                                                <td>Baba Sales</td>
                                                <td>10,000</td>
                                                <td>200</td>
                                                <td>2000</td>
                                                <td>8,200</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>x</td>
                                            </tr>
                                            <tr class="odd">
                                                <td>2</td>
                                                <td>NS1812345324</td>
                                                <td>22-Aug-17</td>
                                                <td>Krishna Sales</td>
                                                <td>5,000</td>
                                                <td>500</td>
                                                <td>1000</td>
                                                <td>4,500</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>x</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>NS1812345455</td>
                                                <td>12-Aug-17</td>
                                                <td>Sai Sales</td>
                                                <td>15,345</td>
                                                <td>-</td>
                                                <td>5,000</td>
                                                <td>10,345</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>x</td>
                                            </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                     
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples --> 
        </div>
</section>
<?php $this->load->view('/layouts/footerDataTable'); ?>
