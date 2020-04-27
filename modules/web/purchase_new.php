<?php defined('_AZ') or die('Restricted access'); 
	include dirname(__FILE__) .'/include/header.php';
	include dirname(__FILE__) .'/include/side_bar.php';
	include dirname(__FILE__) .'/include/navbar.php';
?>

    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <button type="button" id="addRow" class="btn btn-primary " onclick="addRow()">
                    <i class="fa fa-plus"></i> Add
                </button>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form method="get" class="form-horizontal">
                <div class="form-group">

                    <div class="col-sm-10 col-sm-offset-0">
                        <input type="text" class="form-control" placeholder="Scan Barcode">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
    
            </form>
        </div>
    </div>

    <!--THE CONTAINER WHERE WE'll ADD THE DYNAMIC TABLE-->
   
    <table id="purchase_Table" class="table tabl-striped">
        <form class="form-horizontal">
        <tr>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Purchase Price</th>
            <th>Sell Price</th>
            <th>Unite</th>
            <th>Category</th>
            <th>Action</th>

        </tr>
        <tr>
            <td><input type="text" name="code" class="form-control" placeholder="Product Code"></td>
            <td> <input type="text" name="pdt_name" class="form-control" placeholder="Product Name"> </td>
            <td> <input type="text" name="purchase_price" class="form-control" placeholder="Purchase Price"></td>
            <td> <input type="text" name="sell_price" class="form-control" placeholder="Sell Price"></td>
            <td> <input type="text" name="unite" class="form-control" placeholder="Unite"></td>
            <td> <input type="text" name="category" class="form-control" placeholder="Category"></td>
            <td> <button class="btn btn-danger fa fa-trash" onclick="removeRow(this)"></button> </td>
        </tr>
        </form>
    </table>

    <p><input type="button" id="bt" value="Sumbit Data" class="btn btn-success pull-right" onclick="sumbit()" /></p>
    <script>
    // ARRAY FOR HEADER.
  /*  var arrHead = new Array();
    arrHead = ['Product Code', 'Product Name', 'Purchase Price','Sell Price','Unite','Category','Action']; */  
    function addRow() {
        var empTab = document.getElementById('purchase_Table');

        var rowCnt = empTab.rows.length;        // GET TABLE ROW COUNT.
        var tr = empTab.insertRow(rowCnt);      // TABLE ROW.
        tr = empTab.insertRow(rowCnt);

        for (var c = 0; c < 7; c++) {
            var td = document.createElement('td');          // TABLE DEFINITION.
            td = tr.insertCell(c);

            if (c == 6) {           // FIRST COLUMN.
                // ADD A BUTTON.
                var button = document.createElement('button');

                // SET INPUT ATTRIBUTE.
                button.setAttribute('type', 'button');
               
                button.setAttribute('class','btn btn-danger fa fa-trash');
                
                

                // ADD THE BUTTON's 'onclick' EVENT.
                button.setAttribute('onclick', 'removeRow(this)');
             

                td.appendChild(button);
            }
            else {
                // CREATE AND ADD TEXTBOX IN EACH CELL.
                var ele = document.createElement('input');
                ele.setAttribute('type', 'text');
                ele.setAttribute('value', '');
                ele.setAttribute('class', 'form-control');

                td.appendChild(ele);
            }
        }
    }

    // DELETE TABLE ROW.
    function removeRow(oButton) {
        var empTab = document.getElementById('purchase_Table');
        empTab.deleteRow(oButton.parentNode.parentNode.rowIndex);       // BUTTON -> TD -> TR.
    }

    // EXTRACT AND SUBMIT TABLE DATA.
    function sumbit() {
        var myTab = document.getElementById('purchase_Table');
        var values = new Array();

        // LOOP THROUGH EACH ROW OF THE TABLE.
        for (row = 1; row < myTab.rows.length - 1; row++) {
            for (c = 0; c < myTab.rows[row].cells.length-1; c++) {   // EACH CELL IN A ROW.

                var element = myTab.rows.item(row).cells[c];
                if (element.childNodes[1].getAttribute('type') == 'text') {
                    values.push("'" + element.childNodes[1].value + "'");
                }
            }
        }
        console.log(values);
    }

</script>

<?php include dirname(__FILE__) .'/include/footer.php'; ?>