<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductController extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('ProductModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
    }

    public function index(){
      $data['company']=$this->ProductModel->getdata('company');
    	$data['prod']=$this->ProductModel->getActiveProducts('products');
    	
        $data['employee']=$this->ProductModel->getdata('employee');
    	$this->load->view('ProductsStockView',$data);
    }

    public function Add(){
    	// $data['product']=$this->DeliverySlipModel->getData('products');
    	$this->load->view('AddProductView');
    }

    public function load($id) 
    {
        $data['product']=$this->ProductModel->load('products', $id);
        $this->load->view('AddProductView',$data);
    }

    public function loadProductDetails() 
    {
        $companyData=$this->ProductModel->getdata('company');
        // $data['prods']=$this->ProductModel->getdata('products');
        $id=trim($this->input->post('id'));
        $product=$this->ProductModel->load('products',$id);
        $prodId=$product[0]['id'];
        $name=$product[0]['name'];
        $productCode=$product[0]['productCode'];
        $company=$product[0]['company'];
        $mrp=$product[0]['mrp'];
        $unitOne=$product[0]['unitOne'];
        $unitTwo=$product[0]['unitTwo'];
        $unitThree=$product[0]['unitThree'];

        $unitFilter=$product[0]['unitFilter'];
        
        ?>
        <div class="modal-header">
            <h4 class="modal-title">Update Product</h4>
        </div>
         <input autocomplete="off" id="prodIdU" value="<?php echo $prodId; ?>" type="hidden" name="prodId">
          <div class="modal-body">
                      <div class="row clearfix">
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="col-md-12 cust-tbl">
                                  <div class="col-md-3">
                                      <b>Company</b>
                                      <div class="input-group">
                                          <span class="input-group-addon">
                                               <i class="material-icons">check_circle</i>
                                          </span>
                                          <div class="form-line">
                                            <select id="productCompanyU"  name="productCompany" class="form-control">
                                              <option value="<?php echo $company; ?>"><?php echo $company; ?></option>
                                              <!-- <option value="">Select Company</option>
                                              <?php if(!empty($companyData)){
                                                  foreach($companyData as $data){
                                              ?>
                                                <option value="<?php echo $data['name']; ?>"><?php echo $data['name']; ?></option>
                                              <?php } 
                                                }
                                              ?> -->
                                            </select>
                                          </div>
                                      </div>
                                  </div> 

                                  <div class="col-md-3">
                                      <b>Product Code</b>
                                      <div class="input-group">
                                          <span class="input-group-addon">
                                               <i class="material-icons">check_circle</i>
                                          </span>
                                          <div class="form-line">
                                              <input autocomplete="off" id="productCodeU" readonly type="text" name="productCode" class="form-control" placeholder="Enter product code" required value="<?php echo $productCode; ?>">
                                          </div>
                                      </div>
                                  </div> 

                                  <div class="col-md-3">
                                        <b>Product Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input autocomplete="off" id="productNameU" readonly value="<?php echo $name; ?>" type="text" list="prodList" name="productName" class="form-control date" placeholder="Enter product name" required>
                                                
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3">
                                        <b>Product MRP</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input onkeypress="return isNumber(event)" value="<?php echo $mrp; ?>" autocomplete="off" id="productMrpU" type="text" name="productMrp" class="form-control date" placeholder="MRP (per Pcs)" required>
                                            </div>
                                        </div>
                                    </div> 

                                  </div>
                                  <div class="col-md-12 cust-tbl">
                                    <div class="col-md-3">
                                        <!-- <b>No. of Pcs in a Box</b> -->
                                        <div class="input-group">
                                            <?php 
                                            $val="";
                                                if($unitFilter==='u1'){ 
                                                    $val="u1";  
                                                }else{
                                                    $val="u2";
                                                } 

                                             ?> 
                                            <input type="radio" name="unitFilterUpd" id="radio_3" <?php if($unitFilter==='u1'){  echo "checked";  } ?> value="u1" class="with-gap radio-col-red" />
                                            <label for="radio_3">2 Units</label>
                                            <br>
                                            <input type="radio" name="unitFilterUpd" id="radio_4" <?php if($unitFilter==='u2'){  echo "checked";  } ?> value="u2" class="with-gap radio-col-red" />
                                            <label for="radio_4">3 Units</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <b>Unit 1 (Pcs)</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input onkeypress="return isNumber(event)" readonly value="<?php echo $unitOne; ?>" autocomplete="off" id="productUnitOneU" type="text" name="productUnitOne" class="form-control date" placeholder="No. of Pcs in a Box" required>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-3" id="updDrop">
                                      <b>Unit 2 (Box)</b>
                                      <div class="input-group">
                                          <span class="input-group-addon">
                                               <i class="material-icons">check_circle</i>
                                          </span>
                                          <div class="form-line">
                                            <input onkeypress="return isNumber(event)" readonly value="<?php echo $unitTwo; ?>" autocomplete="off" id="productUnitTwoU" type="text" name="productUnitTwo" class="form-control date" placeholder="No. of box in a case" required>
                                            <!-- <select id="productUnitTwoU" name="productUnitTwoU" class="form-control">
                                                <option value="">Select Unit</option>
                                                <option value="box">Box</option>
                                                <option value="poly">Poly</option>
                                                <option value="packet">Packet</option>
                                            </select> -->
                                          </div>
                                      </div>
                                  </div> 

                                  <div class="col-md-3">
                                        <b id="txtunitU2">Unit 2 (Case)</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input onkeypress="return isNumber(event)" readonly value="<?php echo $unitThree; ?>" readonly autocomplete="off" id="productUnitThreeU" type="text" name="productUnitThree" class="form-control" placeholder="No. of cases" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               

                                    <div id="recStatus"></div>
                                  
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-md-4">
                                                <button id="updProd" class="btn btnStyle btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">Save</span>
                                                </button>
                                               
                                                <button data-dismiss="modal" type="button" class="btn btn-danger m-t-15 waves-effect">
                                                    <i class="material-icons">cancel</i> 
                                                    <span class="icon-name"> Cancel</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>                             
                                </div>
                            </div>
                        </div>
          </div>
<?php
    }

    public function insert()
    {
        $productName=trim($this->input->post('productName'));
        $productCode=trim($this->input->post('productCode'));
        $productCompany=trim($this->input->post('productCompany'));
        $productMrp=trim($this->input->post('productMrp'));
        $productUnitOne=trim($this->input->post('productUnitOne'));
        $productUnitTwo=trim($this->input->post('productUnitTwo'));
        $productUnitThree=trim($this->input->post('productUnitThree'));
        $filter=trim($this->input->post('filter'));

        $prodDetails=$this->ProductModel->getProductDetailsByName('products',$productName,$productCode,$productMrp); 
        if(!empty($prodDetails)){
            echo "Product already present.";
            exit;
        }

        $insertData=array(
            'name'=>$productName,
            'productCode'=>$productCode,
            'company'=>$productCompany,
            'mrp'=>$productMrp,
            'unitFilter'=>$filter,
            'unitOne'=>$productUnitOne,
            'unitTwo'=>$productUnitTwo,
            'unitThree'=>$productUnitThree,
            'isActive'=>'1'
        );
        
        $this->ProductModel->insert('products',$insertData); 
        if($this->db->affected_rows()>0){   
            echo "Product Inserted.";
        }   
        else{
            echo "Product not Inserted";
        }
        
    }

    public function insertProdQuantity(){
        $prodQty = $this->input->post('prodQty');
        $prodId=trim($this->input->post('prodId'));

        $prodDetails=$this->ProductModel->load('products',$prodId); 

        $insertData=array(
            'productId'=>$prodDetails[0]['id'],
            'productName'=>$prodDetails[0]['name'],
            'productCode'=>$prodDetails[0]['productCode'],
            'quantity'=>$prodQty,
            'quantityUnit'=>'case',
            'cases'=>$prodQty,
            'addInCompanySoftware'=>$prodQty,
            'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
            'createdAt'=>date('Y-m-d H:i:sa')
        );

        $this->ProductModel->insert('deliveryslip_pending_for_billing',$insertData); 
        if($this->db->affected_rows()>0){   
            echo "Product Quantity Inserted.";
        }   
        else{
            echo "Product Quantity not Inserted";
        }
        // print_r($insertData);
    }
   
    public function update() {
        $id = $this->input->post('prodId');
        // $productName=trim($this->input->post('productName'));
        // $productCode=trim($this->input->post('productCode'));
        $productCompany=trim($this->input->post('productCompany'));
        $productMrp=trim($this->input->post('productMrp'));
        $productUnitOne=trim($this->input->post('productUnitOne'));
        $productUnitTwo=trim($this->input->post('productUnitTwo'));
        $productUnitThree=trim($this->input->post('productUnitThree'));
        $filter=trim($this->input->post('filter'));

        // $prodDetails=$this->ProductModel->getProductDetailsByName('products',$productName); 
        // if(!empty($prodDetails)){
        //     echo "Product already present.";
        //     exit;
        // }

        $updateData=array(
            // 'name'=>$productName,
            // 'productCode'=>$productCode,
            // 'company'=>$productCompany,
            'mrp'=>$productMrp,
            // 'unitFilter'=>$filter,
            // 'unitOne'=>$productUnitOne,
            // 'unitTwo'=>$productUnitTwo,
            // 'unitThree'=>$productUnitThree,
            // 'isActive'=>'1'
        );
        
        $this->ProductModel->update('products',$updateData,$id); 
        if($this->db->affected_rows()>0){   
            echo "Product Updated.";
        }   
        else{
            echo "Product not Updated";
        }
    }
    
    public function updateProdDetails(){
        $id=trim($this->input->post('prodId'));
        $name=trim($this->input->post('prodNames'));
        $boxQty=trim($this->input->post('noBoxs'));
        $price=trim($this->input->post('prices'));
        
        $updData=array('name'=>$name,'boxQty'=>$boxQty,'price'=>$price);
        $this->ProductModel->update('products',$updData,$id);
        if($this->db->affected_rows()>0){
            return redirect('DeliverySlipController/Products');
        }else{
            return redirect('DeliverySlipController/Products');
        }
    }
    
    public function delete()
    {
        $id =$this->input->post('id');
        $up=array('isActive'=>2);
        $this->ProductModel->update('products',$up,$id);  
        if ($this->db->affected_rows()>0)
        {
            echo "Your record has been deleted!";                
        }
        else
        {
            echo "Deleted Fail..";
        }
    }
    
    public function deactivateProduct($id)
    {
        $up=array('isActive'=>0);
        $this->ProductModel->update('products',$up,$id);  
        if ($this->db->affected_rows()>0)
        {
          return redirect("DeliverySlipController/Products");                   
        }
        else
        {
            echo "Deleted Fail..";
        }
    }
    
    public function activateProduct($id)
    {
        $up=array('isActive'=>1);
        $this->ProductModel->update('products',$up,$id);  
        if ($this->db->affected_rows()>0)
        {
          return redirect("DeliverySlipController/Products");                   
        }
        else
        {
            echo "Deleted Fail..";
        }
    }
    
    public function addQty(){
        $id=trim($this->input->post('id'));
        $name=trim($this->input->post('name'));
        ?>
          <div class="modal-header">
          
            <h4 class="modal-title">Add Quantity for Product : <?php echo $name; ?></h4>
          </div>
          <div class="modal-body">
                         <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                  <form method="post" role="form" action="<?php echo site_url('ProductController/addQuantityForProduct'); ?>">    
                                    <div class="col-md-12">
                                    <input type="hidden" name="pid" class="form-control date" value="<?php echo $id; ?>">
                                    <div class="col-md-4">
                                        <b>Select Option</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                               <select name="qtyOption" id="qtyOption" class="form-control">
                                                   <option>Select Option</option>
                                                   <option>Blocked </option>
                                                   <option>Billed </option>
                                                   <option>Billed & Blocked</option>
                                               </select>
                                            </div>
                                        </div>
                                    </div> 
                                    
                          
                                    
                                  <div class="col-md-4">
                                        <b>Quantity</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input autocomplete="off" id="quantity" type="text" name="quantity" class="form-control date" placeholder="Enter Quantity (In Cases)" required>
                                                
                                            </div>
                                        </div>
                                    </div> 
                                    
                                    <div class="col-md-4">
                                        <b>Select Unit</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                               <select name="qtyUnit" id="qtyUnit" class="form-control">
                                                   <option>Select Unit</option>
                                                   <option>Case </option>
                                                   <option>Pcs </option>
                                               </select>
                                            </div>
                                        </div>
                                    </div> 
                                    
                                    <div id="recStatus"></div>
                                  </div>
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                <button onClick="validateR();" type="submit" class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">save</i> 
                                                    <span class="icon-name">Save</span>
                                                </button>
                                               
                                                    <button data-dismiss="modal" type="button" class="btn btn-primary m-t-15 waves-effect">
                                                        <i class="material-icons">cancel</i> 
                                                        <span class="icon-name"> Cancel</span>
                                                    </button>
                                               
                                            </div>

                                        </div>
                                    </div>    
                                    </form>
                                </div>

                            </div>
                        </div>
          </div>
        
        
        <?php
    }
    
    public function addQuantityForProduct(){
        $prodId=trim($this->input->post('pid'));
        $qtyOption=trim($this->input->post('qtyOption'));
        $qty=trim($this->input->post('quantity'));
        $qtyUnit=trim($this->input->post('qtyUnit'));
        
        $data['prod']=$this->ProductModel->load('products',$prodId);
        $availableQty=$data['prod'][0]['availQty'];
        $blockedQty=$data['prod'][0]['blockQty'];
        $qtyPerBox=$data['prod'][0]['boxQty'];
        
        $qtyTobeAdd=0;
        if($qtyUnit=="Case"){
            $qtyTobeAdd=$qty*$qtyPerBox;
        }else{
            $qtyTobeAdd=$qty;
        }   
       
        if($qtyOption=="Billed"){
            $availableQty=$availableQty+$qtyTobeAdd;
             
            $updateData=array('availQty'=>$availableQty);
            $this->ProductModel->update('products',$updateData,$prodId);
            if($this->db->affected_rows()>0){
                return redirect('DeliverySlipController/Products');
            }else{
                return redirect('DeliverySlipController/Products');
            }
            
        }else if($qtyOption=="Blocked"){
            $blockedQty=$blockedQty+$qtyTobeAdd;
             
            $updateData=array('blockQty'=>$blockedQty);
            $this->ProductModel->update('products',$updateData,$prodId);
            if($this->db->affected_rows()>0){
                return redirect('DeliverySlipController/Products');
            }else{
                return redirect('DeliverySlipController/Products');
            }
        }else if($qtyOption=="Billed & Blocked"){
            $availableQty=$availableQty+$qtyTobeAdd;
            $blockedQty=$blockedQty+$qtyTobeAdd;
            
            $updateData=array('blockQty'=>$blockedQty,'availQty'=>$availableQty);
            $this->ProductModel->update('products',$updateData,$prodId);
            if($this->db->affected_rows()>0){
                return redirect('DeliverySlipController/Products');
            }else{
                return redirect('DeliverySlipController/Products');
            }
        }
    }

    public function changeQuantityForProduct(){
        $quantity=$this->input->post('addProdQty');
        $status=trim($this->input->post('addReduce'));
        $unitCategory=$this->input->post('unit_category');
        $productId=$this->input->post('addProdQtyId');
        $productDetail=$this->ProductModel->load('products',$productId);

        $pcs=$productDetail[0]['unitOne'];
        $box=$productDetail[0]['unitTwo'];
        $case=$productDetail[0]['unitThree'];

        $productName=$productDetail[0]['name'];
        $productCode=$productDetail[0]['productCode'];

        $addInCompanySoftware=0;
        $reduceInCompanySoftware=0;
        if($unitCategory==="add"){
            $addInCompanySoftware=$quantity;
        }else{
            $reduceInCompanySoftware=$quantity;
        }

        $totalPcs=0;
        $totalBox=0;
        $totalCase=0;
        
        if($unitCategory==="box"){
            $totalPcs=$pcs*$quantity*$case;
        }else if($unitCategory==="case"){
            $totalPcs=$pcs*$box*$quantity;
        }else{
            $totalPcs=$quantity;
        }

        if(trim($status)==="replace"){
            $calculateQtyTotal=$this->ProductModel->getTotalQtySum('deliveryslip_pending_for_billing',$productId);
            $addQtyTotal=0;
            if(!empty($calculateQtyTotal)){
                $totalPcs=$totalPcs-($calculateQtyTotal[0]['totatQuantity']);
            }
        }

        if(trim($status)==="add"){
            $totalPcs=($totalPcs);
        }else if(trim($status)==="reduce"){
            $totalPcs=(-$totalPcs);
        }else if(trim($status)==="replace"){
            $totalPcs=($totalPcs);
        }

        $insertData=array(
            'operationStatus'=>$status,
            'quantityInPcs'=>$totalPcs,
            'productId'=>$productId,
            'productName'=>$productName,
            'productCode'=>$productCode,
            'quantity'=>$quantity,
            'quantityUnit'=>$unitCategory,
            'addInCompanySoftware'=>$addInCompanySoftware,
            'reduceInCompanySoftware'=>$reduceInCompanySoftware,
            'createdBy'=>$this->session->userdata[$this->projectSessionName]['id'],
            'createdAt'=>date('Y-m-d H:i:sa')
        );
        $this->ProductModel->insert('deliveryslip_pending_for_billing',$insertData);

        if($this->db->affected_rows()>0){
            return redirect('DeliverySlipController/Products');
        }else{
            return redirect('DeliverySlipController/Products');
        }
    }
    
}

?>