<?php $this->load->view('/layouts/commanHeader'); ?>

<h1 style="display: none;">Welcome</h1><br/><br/><br/>
<section class="col-md-12 box" style="height: auto;overflow-y: scroll;">
    <div class="container-fluid">
            <!-- <div class="block-header">
                <h2>
                    Cash
                </h2>
                
            </div> -->
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                          <h2>Disapproved FSR </h2>
                      </div>

                      <div class="body">
                        <div class="table-responsive">
                            <div class="body">
                                <div class="demo-masked-input">
                                        <div class="row clearfix">
                                            <div class="col-md-12">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-4">
                                                    <p>
                                                      <b>Salesman </b>
                                                  </p>
                                                  <div class="form-line">
                                                    <input class="form-control" type="hidden" id="bId" name="bId" value="<?php
                                    if(isset($billID))
                                      {
                                        echo $billID;
                                      }
                                    ?>">
                                     <input class="form-control" type="hidden" id="allocatedID" name="allocatedID" value="<?php
                                    if(isset($allocationId))
                                      {
                                        echo $allocationId;
                                      }
                                    ?>">

                                                    <input autocomplete="off" type="text" id="eName" list="empList" name="eName" class="form-control" value="<?php if(isset($bills[0]['salesman'])){
                                                        echo $bills[0]['salesman'];
                                                    } ?>">
                                                    <datalist id="empList">
                                                        <?php
                                                        foreach($emp as $data){
                                                        $name=$data['name'];
                                                        ?>   
                                                        <option value="<?php echo $name;?>"/>
                                                            <?php    
                                                        }
                                                        ?>
                                                    </datalist>
                                                </div> 
                                                <button  id="empAdd"class="btn btn-primary m-t-15 waves-effect">
                                                    <i class="material-icons">add</i> 
                                                    <span class="icon-name">
                                                        Add to List
                                                    </span>
                                                </button> 
                                           
                                        </div>  
                                        <div class="col-md-4">
                                            <label>Selected Emp</label>
                                            <ul class="list-group" id="list" multiple="multiple"></ul>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <center>
                                        <button id="insert_usr" class="btn btn-primary m-t-15 waves-effect">
                                            <i class="material-icons">save</i> 
                                            <span class="icon-name">Save</span>
                                        </button> 
                                    <center>
                                    </div>
                                    <div id="res"></div>
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
<script type="text/javascript">
    function checkCash(pendCash){
        var cash=parseFloat(document.getElementById('cashAmt').value);
        var msg=document.getElementById('sr_qty');
            // alert(cash+' '+pendCash);
            var pendCash=parseFloat(pendCash);
            if(cash>pendCash){
                msg.innerHTML="Sorry!.. Cash amount is greater than pending amount.";
            }else{
                msg.innerHTML="";
            }
        }
    </script>
    <script>
        (function(){

          var todo = document.querySelector( '#list' ),
          add = document.querySelector( '#empAdd' ),
          eName = document.querySelector( '#eName' );

          add.addEventListener('click', function( ev ) {
            var text = eName.value;
            if ( text !== '' ) {
              todo.innerHTML += '<li class="list-group-item list-group-item-action">' + text + '<button  style="float: right;" onclick="Delete(this);"><i class="fa fa-close"></i></button> </li>';
              eName.value = '';
          }

          ev.preventDefault();
      }, false);

      })();
      function Delete(currentEl){
          currentEl.parentNode.parentNode.removeChild(currentEl.parentNode);
      }
  </script>
<script type="text/javascript">
    jQuery("#insert_usr").on("click",function(){
        var emp = new Array();
        var id = $('#bId').val(); 
        var allocatedID=$('#allocatedID').val();
        $("#list li").each(function()
        {
            emp.push($(this).text());
        });
       
        if(emp.length>0){
            $.ajax({
                type: "POST",
                url:"<?php echo site_url('manager/SrCheckController/insertUfsr');?>",
                data:{"empName":emp,"id":id,"allocationId":allocatedID},
                success: function (data) {
                   // parent.$.fn.colorbox.close();
                   // window.parent.location.reload(true);
                document.getElementById('res').innerHTML=data;
                }  
            });
        }else{
            alert('Please select Employee.');
        }
    });
</script>


