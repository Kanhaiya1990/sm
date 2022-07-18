<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoriesController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('CategoriesModel');
        $this->load->library('session');
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');

        if(isset($this->session->userdata['codeKeyData'])) {
			$this->projectSessionName= $this->session->userdata['codeKeyData']['codeKeyValue'];
		}else{
			$this->load->view('LoginView');
		}
    }

    public function incomeCategory()
    {
        $data['income']=$this->CategoriesModel->getCategoryByType('categories_income_expenses','income');
        $this->load->view('admin/categoriesIncomeView',$data);
    }

    public function expensesCategory()
    {
        $data['expenses']=$this->CategoriesModel->getCategoryByType('categories_income_expenses','expenses');
        $this->load->view('admin/categoriesExpenseView',$data);
    }

    public function insertIncomeCategory(){
        $name=trim($this->input->post('categoryName'));
        $data=array('categoryName'=>$name,'type'=>'income');
        $this->CategoriesModel->insert('categories_income_expenses',$data);
        redirect('admin/CategoriesController/incomeCategory');
    }

    public function insertExpenseCategory(){
        $name=trim($this->input->post('categoryName'));
        $data=array('categoryName'=>$name,'type'=>'expenses');
        $this->CategoriesModel->insert('categories_income_expenses',$data);
        redirect('admin/CategoriesController/expensesCategory');
    }

    public function loadIncomeCategory(){
        $id=trim($this->input->post('id'));
        $category=$this->CategoriesModel->load('categories_income_expenses',$id);
        
        ?>
             <form method="post" role="form" action="<?php echo site_url('admin/CategoriesController/updateIncomeCategory'); ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                    <input type="hidden" id='limitId' autocomplete="off" name="id" list="ret" value="<?php echo $category[0]['id']; ?>" class="form-control date">

                                <div class="col-md-12 cust-tbl">
                                        <b>Category Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='categoryName' autocomplete="off" name="categoryName" value="<?php echo $category[0]['categoryName']; ?>" list="ret" class="form-control date" placeholder="Enter category Name" required>
                                            </div>
                                        </div>
                                </div> 
                                    
                                <div id="recStatus1"></div>
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                <button id="insRet" class="btn btnStyle m-t-15 waves-effect">
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
                      </form>
        <?php

    }

    public function loadExpensesCategory(){
        $id=trim($this->input->post('id'));
        $category=$this->CategoriesModel->load('categories_income_expenses',$id);

        ?>
             <form method="post" role="form" action="<?php echo site_url('admin/CategoriesController/updateExpenseCategory'); ?>"> 
                        <div class="body">
                            <div class="demo-masked-input">
                                <div class="row clearfix">
                                    <input type="hidden" id='limitId' autocomplete="off" name="id" list="ret" value="<?php echo $category[0]['id']; ?>" class="form-control date">

                                <div class="col-md-12 cust-tbl">
                                        <b>Category Name</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                 <i class="material-icons">check_circle</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" id='categoryName' autocomplete="off" name="categoryName" value="<?php echo $category[0]['categoryName']; ?>" list="ret" class="form-control date" placeholder="Enter category Name" required>
                                            </div>
                                        </div>
                                </div> 
                                    
                                <div id="recStatus1"></div>
                                     <div class="col-md-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                <button id="insRet" class="btn btnStyle m-t-15 waves-effect">
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
                      </form>
        <?php
    }

    public function updateIncomeCategory(){
        $id=trim($this->input->post('id'));
        $name=trim($this->input->post('categoryName'));
        $data=array('categoryName'=>$name);
        $this->CategoriesModel->update('categories_income_expenses',$data,$id);
        redirect('admin/CategoriesController/incomeCategory');
    }

    public function updateExpenseCategory(){
        $id=trim($this->input->post('id'));
        $name=trim($this->input->post('categoryName'));
        $data=array('categoryName'=>$name);
        $this->CategoriesModel->update('categories_income_expenses',$data,$id);
        redirect('admin/CategoriesController/expensesCategory');
    }

    public function delete()
    {
        $id =$this->input->post('id');
        $data=$this->CategoriesModel->delete('categories_income_expenses',$id);  
        if ($data==1)
        {
            echo "Your record has been deleted!";                
        }
        else
        {
            echo "Deleted Fail..";
        }
    } 
    
}
