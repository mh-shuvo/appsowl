<?php
	class ASPos
	{
		private $db = null;
		
		private $users;
		
		public function __construct(ASDatabase $db, ASUser $users)
		{
			$this->db = $db;
			$this->users = $users;
		}
		
		public function getFinancialStatementData($data = null,$userId = null,$storeId = null)
		{
			
			$startDate = $data['from_data'];
			$endDate = $data['to_data'];
			
			$CapitalAmount = $this->getCapitalAmount($data,false);
			$GetClosingStock = $this->getStockAmount($data,false);
			$whereBeteween = " AND date(`accounts_transactions`.`date`) BETWEEN '$startDate' AND '$endDate'";
			
			$whereStore = '';
			if(isset($data['store_id']) && $data['store_id'] != '0'){
				$store =  $data['store_id'];
				$whereStore = " AND `accounts_transactions`.`store_id` = '$store'";
			}
			
			$selectRawPosTnx = ", (COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'credit' AND `payment_method_value` = 'cash' THEN transaction_amount END), 0) - COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'debit' AND `payment_method_value` = 'cash' THEN transaction_amount END), 0)) AS `pos_txn_cash` ";
			$selectRawPosTnx .= ", (COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'credit' AND `payment_method_value` != 'cash' THEN transaction_amount END), 0) - COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'debit' AND `payment_method_value` != 'cash' THEN transaction_amount END), 0)) AS `pos_txn_bank` ";
			$selectRawPosTnx .= ", (COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'credit' AND `transaction_type` = 'sales' THEN transaction_amount END), 0)) AS `pos_txn_sales` ";
			$selectRawPosTnx .= ", (COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'debit' AND `transaction_type` = 'return' AND `is_return` = 'true' THEN transaction_amount END), 0)) AS `pos_txn_sales_return` ";
			$selectRawPosTnx .= ", (COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'debit' AND `transaction_type` = 'purchase' THEN transaction_amount END), 0)) AS `pos_txn_purchase` ";
			$selectRawPosTnx .= ", (COALESCE(SUM(CASE WHEN `transaction_flow_type` = 'credit' AND `transaction_type` = 'return' THEN transaction_amount END), 0)) AS `pos_txn_purchase_return` ";
			$GetPosTxn = $this->db->table('pos_transactions')->col('transaction_status')->where('transaction_status',"paid")->where('is_delete',"false")->get(1,$selectRawPosTnx);
			$totalPosSalesDue = $GetClosingStock['total_sales_total'] -  $GetPosTxn['pos_txn_sales'];
			$totalPosPurchaseReturnDue = $GetClosingStock['total_purchase_return_total'] -  $GetPosTxn['pos_txn_purchase_return'];
			$totalPosDebtor = $totalPosSalesDue + $totalPosPurchaseReturnDue;
			$totalPosPurchaseDue = $GetClosingStock['total_purchase_total'] -  $GetPosTxn['pos_txn_purchase'];
			$totalPosSalesReturnDue = $GetClosingStock['total_sales_return_total'] -  $GetPosTxn['pos_txn_sales_return'];
			$totalPosCreditor = $totalPosPurchaseDue + $totalPosSalesReturnDue;
			
			$selectRaw = ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'advance' THEN amount END), 0) - COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`cr_account_status` = 'advance' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` AND `accounts_transactions`.`account_type` = 'account_expense' $whereStore $whereBeteween) AS `expense_advance` ";
			$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`cr_account_status` = 'advance' THEN amount END), 0) - COALESCE(SUM(CASE WHEN `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`dr_account_status` = 'advance' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` AND `accounts_transactions`.`account_type` = 'account_income' $whereStore $whereBeteween) AS `income_advance` ";
			$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`cr_account_status` = 'due' THEN amount END), 0) - COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'due' AND `accounts_transactions`.`cr_account_status` != 'due' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` AND `accounts_transactions`.`account_type` = 'account_expense' $whereStore $whereBeteween) AS `expense_due` ";
			$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` = 'due' THEN amount END), 0) - COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` != 'due' AND `accounts_transactions`.`cr_account_status` = 'due' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` AND `accounts_transactions`.`account_type` = 'account_income' $whereStore $whereBeteween) AS `income_due` ";
			$selectRaw .= ", (SELECT COALESCE(SUM((CASE WHEN `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`dr_account` = 'account_cash' THEN amount END)),0) - COALESCE(SUM((CASE WHEN `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`cr_account` = 'account_cash' THEN amount END)),0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' $whereStore $whereBeteween) AS `cash` ";
			$selectRaw .= ", (SELECT COALESCE(SUM((CASE WHEN `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`dr_account` = 'account_bank' THEN amount END)),0) - COALESCE(SUM((CASE WHEN `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`cr_account` = 'account_bank' THEN amount END)),0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' $whereStore $whereBeteween) AS `bank` ";
			$selectRaw .= ", (SELECT COALESCE(SUM((CASE WHEN `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` THEN amount END)),0) - COALESCE(SUM((CASE WHEN `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` THEN amount END)),0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_category_name` = 'account_fixed_assets' $whereStore $whereBeteween) AS `fixed_assets` ";
			$selectRaw .= ", (SELECT COALESCE(SUM((CASE WHEN `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` THEN amount END)),0) - COALESCE(SUM((CASE WHEN `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` THEN amount END)),0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_category_name` = 'account_long_term_liabilities' $whereStore $whereBeteween) AS `long_term_liabilities` ";
			
			$GetCharts = $this->db->table('accounts_chart')->col(['id','chart_name','chart_name_value','chart_category_name'])->where('chart_status',"active")->where('is_delete',"false")->get(null,$selectRaw);
			
			$getChartData = [];
			$getChartDatadetails = [];
			
			$getChartData['account_total'][0]['id'] = 'account_total_assets';
			$getChartData['account_total'][0]['name'] = 'account_total_assets';
			$getChartData['account_total'][0]['value'] = 0;
			
			$getChartData['account_total'][1]['id'] = 'account_total_liability';
			$getChartData['account_total'][1]['name'] = 'account_total_liability';
			$getChartData['account_total'][1]['value'] = 0;
			
			for ($x = 0; $x < count($GetCharts); $x++) {
				
				if( $GetCharts[$x]['chart_category_name'] == 'account_current_assets'){
					$getChartData['account_current_assets'][$x]['id'] = $GetCharts[$x]['chart_name_value'];
					$getChartData['account_current_assets'][$x]['name'] = $GetCharts[$x]['chart_name'];
					$getChartData['account_current_assets'][$x]['value'] = 0;
					if($GetCharts[$x]['chart_name_value'] == 'account_cash'){
						$getChartData['account_current_assets'][$x]['value'] = $GetCharts[$x]['cash'] + $GetPosTxn['pos_txn_cash'];
						}elseif($GetCharts[$x]['chart_name_value'] == 'account_bank'){
						$getChartData['account_current_assets'][$x]['value'] = $GetCharts[$x]['bank'] + $GetPosTxn['pos_txn_bank'];
					}
					
					if($GetCharts[$x]['chart_name_value'] == 'account_debitors'){
						$getChartData['account_current_assets'][$x]['id'] = $GetCharts[$x]['chart_name_value'];
						$getChartData['account_current_assets'][$x]['value'] = $totalPosDebtor;
					}
					
					$getChartData['account_total'][0]['value'] += $getChartData['account_current_assets'][$x]['value'];
					}else{
					$getChartData['account_current_assets'][$x]['id'] = 'advance_'.$GetCharts[$x]['chart_name_value'];
					$getChartData['account_current_assets'][$x]['name'] = $GetCharts[$x]['chart_name'];
					$getChartData['account_current_assets'][$x]['value'] = 0;
					if(abs($GetCharts[$x]['expense_advance']) > 0){
						$getChartData['account_current_assets'][$x]['value'] = $GetCharts[$x]['expense_advance'];
						}elseif(abs($GetCharts[$x]['income_due']) > 0){
						$getChartData['account_current_assets'][$x]['id'] = 'due_'.$GetCharts[$x]['chart_name_value'];
						$getChartData['account_current_assets'][$x]['value'] = $GetCharts[$x]['income_due'];
					}
					
					if($GetCharts[$x]['chart_name_value'] == 'account_closing_stock'){
						$getChartData['account_current_assets'][$x]['id'] = $GetCharts[$x]['chart_name_value'];
						$getChartData['account_current_assets'][$x]['value'] = $GetClosingStock["net_stock_amount"];
					}
					$getChartData['account_total'][0]['value'] += $getChartData['account_current_assets'][$x]['value'];
				}
				
				if( $GetCharts[$x]['chart_category_name'] == 'account_current_liabilities'){
					$getChartData['account_current_liabilities'][$x]['id'] = $GetCharts[$x]['chart_name_value'];
					$getChartData['account_current_liabilities'][$x]['name'] = $GetCharts[$x]['chart_name'];
					$getChartData['account_current_liabilities'][$x]['value'] = 0;
					if($GetCharts[$x]['chart_name_value'] == 'account_vat'){
						$getChartData['account_current_liabilities'][$x]['id'] = $GetCharts[$x]['chart_name_value'];
						$getChartData['account_current_liabilities'][$x]['value'] = $GetClosingStock["total_sales_vat"] - $GetClosingStock["total_sales_return_vat"];
					}
					
					if($GetCharts[$x]['chart_name_value'] == 'account_creditors'){
						$getChartData['account_current_liabilities'][$x]['id'] = $GetCharts[$x]['chart_name_value'];
						$getChartData['account_current_liabilities'][$x]['value'] = $totalPosCreditor;
					}
					
					$getChartData['account_total'][1]['value'] += $getChartData['account_current_liabilities'][$x]['value'];
					}else{
					$getChartData['account_current_liabilities'][$x]['id'] = 'due_'.$GetCharts[$x]['chart_name_value'];
					$getChartData['account_current_liabilities'][$x]['name'] = $GetCharts[$x]['chart_name'];
					$getChartData['account_current_liabilities'][$x]['value'] = 0;
					if(abs($GetCharts[$x]['expense_due']) > 0){
						$getChartData['account_current_liabilities'][$x]['value'] = $GetCharts[$x]['expense_due'];
						}elseif(abs($GetCharts[$x]['income_advance']) > 0){
						$getChartData['account_current_liabilities'][$x]['id'] = 'advance_'.$GetCharts[$x]['chart_name_value'];
						$getChartData['account_current_liabilities'][$x]['value'] = $GetCharts[$x]['income_advance'];
					}
					
					$getChartData['account_total'][1]['value'] += $getChartData['account_current_liabilities'][$x]['value'];
				}
				
				if($GetCharts[$x]['chart_category_name'] == 'account_fixed_assets'){
					$getChartData['account_fixed_assets'][$x]['id'] = $GetCharts[$x]['chart_name_value'];
					$getChartData['account_fixed_assets'][$x]['name'] = $GetCharts[$x]['chart_name'];
					$getChartData['account_fixed_assets'][$x]['value'] = $GetCharts[$x]['fixed_assets'];
					$getChartData['account_total'][0]['value'] += $getChartData['account_fixed_assets'][$x]['value'];
				}
				
				if($GetCharts[$x]['chart_category_name'] == 'account_long_term_liabilities'){
					$getChartData['account_long_term_liabilities'][$x]['id'] = $GetCharts[$x]['chart_name_value'];
					$getChartData['account_long_term_liabilities'][$x]['name'] = $GetCharts[$x]['chart_name'];
					$getChartData['account_long_term_liabilities'][$x]['value'] = $GetCharts[$x]['long_term_liabilities'];
					if($GetCharts[$x]['chart_name_value'] == 'account_owners_equity'){
						$getChartData['account_long_term_liabilities'][$x]['value'] = $CapitalAmount['net_capital'];
					}
					$getChartData['account_total'][1]['value'] += $getChartData['account_long_term_liabilities'][$x]['value'];
				}
				
			}
			
			respond(
			$getChartData
			);
		}
		
		public function getOwnerEqutityData($data = null,$userId = null,$storeId = null)
		{
			
			$startDate = $data['from_data'];
			$endDate = $data['to_data'];
			
			$CapitalAmount = $this->getCapitalAmount($data,false);
			
			respond(
			$CapitalAmount
			);
		}
		
		public function getIncomeStatementData($data = null,$userId = null,$storeId = null)
		{
			
			$startDate = $data['from_data'];
			$endDate = $data['to_data'];
			$getChartData = [];
			$getChartDatadetails = [];
			
			$getChartData['account_cost_of_sold_goods_total'][0]['id'] = 'account_cost_of_sold_goods_net_total';
			$getChartData['account_cost_of_sold_goods_total'][0]['name'] = 'account_cost_of_sold_goods_net_total';
			$getChartData['account_cost_of_sold_goods_total'][0]['value'] = 0;
			
			$getChartData['account_cost_of_sold_goods_total'][1]['id'] = 'account_cost_of_sold_goods_total';
			$getChartData['account_cost_of_sold_goods_total'][1]['name'] = 'account_cost_of_sold_goods_total';
			$getChartData['account_cost_of_sold_goods_total'][1]['value'] = 0;
			
			$getChartData['account_cost_of_sold_goods_total'][2]['id'] = 'account_cost_of_sold_goods_closing_total';
			$getChartData['account_cost_of_sold_goods_total'][2]['name'] = 'account_cost_of_sold_goods_closing_total';
			$getChartData['account_cost_of_sold_goods_total'][2]['value'] = 0;
			
			$getChartData['account_income_of_sold_goods_total'][0]['id'] = 'account_income_of_sold_goods_net_total';
			$getChartData['account_income_of_sold_goods_total'][0]['name'] = 'account_income_of_sold_goods_net_total';
			$getChartData['account_income_of_sold_goods_total'][0]['value'] = 0;
			
			$getChartData['account_income_of_sold_goods_total'][1]['id'] = 'account_income_of_sold_goods_total';
			$getChartData['account_income_of_sold_goods_total'][1]['name'] = 'account_income_of_sold_goods_total';
			$getChartData['account_income_of_sold_goods_total'][1]['value'] = 0;
			
			$getChartData['account_gross_profit_total'][0]['id'] = 'account_gross_profit_total';
			$getChartData['account_gross_profit_total'][0]['name'] = 'account_gross_profit_total';
			$getChartData['account_gross_profit_total'][0]['value'] = 0;
			
			$getChartData['account_operating_cost_total'][0]['id'] = 'account_operating_cost_total';
			$getChartData['account_operating_cost_total'][0]['name'] = 'account_operating_cost_total';
			$getChartData['account_operating_cost_total'][0]['value'] = 0;
			
			$getChartData['account_administrative_cost_total'][0]['id'] = 'account_administrative_cost_total';
			$getChartData['account_administrative_cost_total'][0]['name'] = 'account_administrative_cost_total';
			$getChartData['account_administrative_cost_total'][0]['value'] = 0;
			
			$getChartData['account_non_operating_cost_total'][0]['id'] = 'account_non_operating_cost_total';
			$getChartData['account_non_operating_cost_total'][0]['name'] = 'account_non_operating_cost_total';
			$getChartData['account_non_operating_cost_total'][0]['value'] = 0;
			
			$getChartData['account_non_operating_income_total'][0]['id'] = 'account_non_operating_income_total';
			$getChartData['account_non_operating_income_total'][0]['name'] = 'account_non_operating_income_total';
			$getChartData['account_non_operating_income_total'][0]['value'] = 0;
			
			$getChartData['account_net_income_total'][0]['id'] = 'account_net_income_total';
			$getChartData['account_net_income_total'][0]['name'] = 'account_net_income_total';
			$getChartData['account_net_income_total'][0]['value'] = 0;
			
			//------Stock Management Start-------//
			
			$stockDate = new DateTime($startDate);
			$stockDate->modify('-1 day');
			$stockDate = $stockDate->format('Y-m-d');
			$GetOpeningStock = $this->getStockAmount(["from_data" => '0000-00-00',"to_data" => $stockDate,"store_id" => $data['store_id']],false);
			
			$GetClosingStock = $this->getStockAmount($data,false);
			
			//------Stock Management End-------//
			$whereBeteween = " AND date(`accounts_transactions`.`date`) BETWEEN '$startDate' AND '$endDate'";
			
			$whereStore = '';
			if(isset($data['store_id']) && $data['store_id'] != '0'){
				$store =  $data['store_id'];
				$whereStore = " AND `accounts_transactions`.`store_id` = '$store'";
			}
			
			$selectRaw = ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` != 'due' AND `accounts_transactions`.`dr_account_status` != 'advance' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` AND `accounts_transactions`.`account_type` = 'account_expense' $whereStore $whereBeteween) AS `expense_amount` ";
			$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`cr_account_status` != 'advance' AND `accounts_transactions`.`cr_account_status` != 'due' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` AND `accounts_transactions`.`account_type` = 'account_income' $whereStore $whereBeteween) AS `income_amount` ";
			
			$GetCharts = $this->db->table('accounts_chart')->where('chart_status',"active")->where('is_delete',"false")->get(null,$selectRaw);
			
			foreach($GetCharts as $GetChart){
				//Income
				if($GetChart['chart_category_name'] == 'account_income_of_sold_goods'){
					$getChartDatadetails['id'] = $GetChart['chart_name_value'];
					$getChartDatadetails['name'] = $GetChart['chart_name'];
					$getChartDatadetails['value'] = $GetChart['income_amount'];
					
					if($GetChart['chart_name_value'] == 'account_total_sale'){
						$getChartDatadetails['value'] = $GetClosingStock['total_sales'];
					}
					
					if($GetChart['chart_name_value'] == 'account_sale_return'){
						$getChartDatadetails['value'] = $GetClosingStock["total_sales_return"];
					}
					
					if($GetChart['chart_name_value'] == 'account_sale_discount'){
						$getChartDatadetails['value'] = $GetClosingStock["total_sales_discount"];
					}
					
					if($GetChart['chart_name_value'] == 'account_sale_return_discount'){
						$getChartDatadetails['value'] = $GetClosingStock["total_sales_return_discount"];
					}
					
					$getChartData['account_income_of_sold_goods'][] =  $getChartDatadetails;
				}
				
				if($GetChart['chart_category_name'] == 'account_non_operating_income'){
					$getChartDatadetails['id'] = $GetChart['chart_name_value'];
					$getChartDatadetails['name'] = $GetChart['chart_name'];
					$getChartDatadetails['value'] = $GetChart['income_amount'];
					$getChartData['account_non_operating_income'][] =  $getChartDatadetails;
					$getChartData['account_non_operating_income_total'][0]['value'] += $getChartDatadetails['value'];
				}
				
				//Expense	
				if($GetChart['chart_category_name'] == 'account_cost_of_sold_goods'){
					$getChartDatadetails['id'] = $GetChart['chart_name_value'];
					$getChartDatadetails['name'] = $GetChart['chart_name'];
					$getChartDatadetails['value'] = $GetChart['expense_amount'];
					
					if($GetChart['chart_name_value'] == 'account_total_purchase'){
						$getChartDatadetails['value'] = $GetClosingStock["total_purchase"];
					}
					
					if($GetChart['chart_name_value'] == 'account_purchase_return'){
						$getChartDatadetails['value'] = $GetClosingStock["total_purchase_return"];
					}
					
					if($GetChart['chart_name_value'] == 'account_purchase_discount'){
						$getChartDatadetails['value'] = $GetClosingStock["total_purchase_discount"];
					}
					
					if($GetChart['chart_name_value'] == 'account_carriage_inward'){
						$getChartDatadetails['value'] = $GetClosingStock["total_purchase_shipping_charge"];
					}
					
					if($GetChart['chart_name_value'] == 'account_opening_stock'){
						$getChartDatadetails['value'] = $GetOpeningStock["net_stock_amount"];
					}
					
					if($GetChart['chart_name_value'] == 'account_closing_stock'){
						$getChartDatadetails['value'] = $GetClosingStock["net_stock_amount"] + $GetOpeningStock["net_stock_amount"];
					}
					
					$getChartData['account_cost_of_sold_goods'][] =  $getChartDatadetails;
					
				}
				
				if($GetChart['chart_category_name'] == 'account_operating_cost'){
					$getChartDatadetails['id'] = $GetChart['chart_name_value'];
					$getChartDatadetails['name'] = $GetChart['chart_name'];
					$getChartDatadetails['value'] = $GetChart['expense_amount'];
					$getChartData['account_operating_cost'][] =  $getChartDatadetails;
					$getChartData['account_operating_cost_total'][0]['value'] += $getChartDatadetails['value'];
				}
				
				if($GetChart['chart_category_name'] == 'account_administrative_cost'){
					$getChartDatadetails['id'] = $GetChart['chart_name_value'];
					$getChartDatadetails['name'] = $GetChart['chart_name'];
					$getChartDatadetails['value'] = $GetChart['expense_amount'];
					$getChartData['account_administrative_cost'][] =  $getChartDatadetails;
					$getChartData['account_administrative_cost_total'][0]['value'] += $getChartDatadetails['value'];
				}
				
				if($GetChart['chart_category_name'] == 'account_non_operating_cost'){
					$getChartDatadetails['id'] = $GetChart['chart_name_value'];
					$getChartDatadetails['name'] = $GetChart['chart_name'];
					$getChartDatadetails['value'] = $GetChart['expense_amount'];
					$getChartData['account_non_operating_cost'][] =  $getChartDatadetails;
					$getChartData['account_non_operating_cost_total'][0]['value'] += $getChartDatadetails['value'];
				}
				
				if($GetChart['chart_category_name'] == 'account_net_income'){
					$getChartDatadetails['id'] = $GetChart['chart_name_value'];
					$getChartDatadetails['name'] = $GetChart['chart_name'];
					$getChartDatadetails['value'] = $GetChart['expense_amount'];
					$getChartData['account_net_income'][] =  $getChartDatadetails;
				}
			}
			
			$getChartData['account_income_of_sold_goods_total'][0]['value'] = $GetClosingStock["net_sales_amount"];
			$getChartData['account_income_of_sold_goods_total'][1]['value'] = $GetClosingStock["net_sales_amount"];
			$getChartData['account_cost_of_sold_goods_total'][2]['value'] = $GetClosingStock["net_purchase_amount"] - $GetClosingStock["net_stock_amount"];
			$getChartData['account_cost_of_sold_goods_total'][1]['value'] = $GetClosingStock["net_purchase_amount"] + $GetOpeningStock["net_stock_amount"];
			$getChartData['account_cost_of_sold_goods_total'][0]['value'] = $GetClosingStock["net_purchase_amount"] + $GetOpeningStock["net_stock_amount"] - $GetClosingStock["total_purchase_shipping_charge"];
			$getChartData['account_gross_profit_total'][0]['value'] = $GetClosingStock["net_profit_amount"];
			$getChartData['account_net_income_total'][0]['value'] = $GetClosingStock["net_income"]; 
			
			respond([
			"status" => "success",
			"chart" => $getChartData,
			]);
		}
		
		public function getCapitalAmount($data,$jsonEncode = true){
			
			$startDate = $data['from_data'];
			$endDate = $data['to_data'];
			$whereBeteween = " AND date(`date`) BETWEEN '$startDate' AND '$endDate' ";
			
			$whereStore = '';
			if(isset($data['store_id']) && $data['store_id'] != '0'){
				$store =  $data['store_id'];
				$whereStore = " AND `store_id` = '$store'";
			}
			
			$selectRaw = ", COALESCE(SUM(CASE WHEN `account_type` = 'account_capital_cash' AND `cr_account` = 'account_capital' AND `dr_account` = 'account_cash' THEN amount END),0) as `capital_cash`";
			$selectRaw .= ", COALESCE(SUM(CASE WHEN `account_type` = 'account_capital_bank' AND `cr_account` = 'account_capital'  AND `dr_account` = 'account_bank' THEN amount END),0) as `capital_bank`";
			$selectRaw .= ", COALESCE(SUM(CASE WHEN `account_type` = 'account_capital_assets' AND `cr_account` = 'account_capital' THEN amount END),0) as `capital_assets`";
			// $selectRaw .= ", COALESCE(SUM(CASE WHEN `account_type` = 'account_capital_loans' AND `cr_account` = 'account_loan' THEN amount END),0) as `capital_loan`";
			$selectRaw .= ", COALESCE(SUM(CASE WHEN `account_type` = 'account_capital_lease' AND `cr_account` = 'account_capital' THEN amount END),0) as `capital_lease`";
			$selectRaw .= ", COALESCE(SUM(CASE WHEN `account_type` = 'account_withdraw' AND `dr_account` = 'account_capital' THEN amount END),0) as `capital_withdraw`";
			$whereRaw = " WHERE `account_type` IN ('account_capital_cash','account_capital_bank','account_capital_assets','account_capital_loans','account_capital_lease','account_withdraw')";
			
			$GetCapital = $this->db->table('accounts_transactions')->col('status')->where('status',"active")->where('is_delete',"false")->between('date',$startDate,$endDate,true);
			if(isset($data['store_id']) && $data['store_id'] != '0'){
				$GetCapital = $GetCapital->where('store_id',$data['store_id']);
			}
			$GetCapital = $GetCapital->get(1,$selectRaw,$whereRaw);
			
			$GetClosingStock = $this->getStockAmount($data,false);
			$GetCapital['net_income'] = $GetClosingStock['net_income'];
			
			$GetCapital['total_capital'] = $GetCapital['capital_cash']
			+ $GetCapital['capital_bank']
			+ $GetCapital['capital_assets']
			// + $GetCapital['capital_loan']
			+ $GetCapital['capital_lease']
			;
			
			$GetCapital['total_capital_with_income'] = $GetCapital['total_capital'] + $GetCapital['net_income'];
			
			$GetCapital['net_capital'] = $GetCapital['total_capital_with_income'] - $GetCapital['capital_withdraw'];
			
			if($jsonEncode){
				return respond([
				$GetCapital
				]);
				}else{
				return $GetCapital;
			}
		}
		
		
		public function getStockAmount($data,$jsonEncode = true){
			
			$startDate = $data['from_data'];
			$endDate = $data['to_data'];
			$whereBeteween = " AND date(`accounts_transactions`.`date`) BETWEEN '$startDate' AND '$endDate' ";
			$whereBeteweenStock = " AND date(`pos_stock`.`created_at`) BETWEEN '$startDate' AND '$endDate' ";
			
			$whereStore = '';
			$whereStoreStock = '';
			if(isset($data['store_id']) && $data['store_id'] != '0'){
				$store =  $data['store_id'];
				$whereStore = " AND `accounts_transactions`.`store_id` = '$store'";
				$whereStoreStock = " AND `pos_stock`.`store_id` = '$store'";
			}
			
			$GetStockSales = $this->db->table('pos_sales')->sum('sales_total')->sum('sales_vat')->sum('sales_subtotal')->sum('sales_discount')->sum('shipping_charge')->where('sales_status','!=',"cancel")->where('is_delete',"false")->between('created_at',$startDate,$endDate,true);
			if(isset($data['store_id']) && $data['store_id'] != '0'){
				$GetStockSales = $GetStockSales->where('store_id',$data['store_id']);
			}
			$GetStockSales = $GetStockSales->get(1);
			
			$GetStockSalesReturn = $this->db->table('pos_return')->sum('return_total')->sum('return_vat')->sum('return_discount')->sum('return_sales_purchase_total')->where('return_type',"sales")->where('return_status','!=',"cancel")->where('is_delete',"false")->between('created_at',$startDate,$endDate,true);
			if(isset($data['store_id']) && $data['store_id'] != '0'){
				$GetStockSalesReturn = $GetStockSalesReturn->where('store_id',$data['store_id']);
			}
			$GetStockSalesReturn = $GetStockSalesReturn->get(1);
			
			$GetStockPurchase = $this->db->table('pos_purchase')->sum('purchase_total')->sum('purchase_discount')->sum('purchase_shipping_charge')->sum('purchase_subtotal')->where('purchase_status','!=',"cancel")->where('is_delete',"false")->between('purchase_date',$startDate,$endDate,true);
			if(isset($data['store_id']) && $data['store_id'] != '0'){
				$GetStockPurchase = $GetStockPurchase->where('store_id',$data['store_id']);
			}
			$GetStockPurchase = $GetStockPurchase->get(1);
			
			$GetStockPurchaseReturn = $this->db->table('pos_return')->sum('return_total')->sum('return_vat')->sum('return_discount')->sum('return_subtotal')->where('return_type',"purchase")->where('return_status','!=',"cancel")->where('is_delete',"false")->between('created_at',$startDate,$endDate,true);
			if(isset($data['store_id']) && $data['store_id'] != '0'){
				$GetStockPurchaseReturn = $GetStockPurchaseReturn->where('store_id',$data['store_id']);
			}
			$GetStockPurchaseReturn = $GetStockPurchaseReturn->get(1);
			
			$selectRaw = ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`dr_account_status` != 'due' AND `accounts_transactions`.`dr_account_status` != 'advance' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` AND `accounts_transactions`.`account_type` = 'account_expense' $whereStore $whereBeteween) AS `expense_amount` ";
			$selectRaw .= ", (SELECT COALESCE(SUM(CASE WHEN `accounts_transactions`.`cr_account_status` != 'advance' AND `accounts_transactions`.`cr_account_status` != 'due' THEN amount END), 0) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` AND `accounts_transactions`.`account_type` = 'account_income' $whereStore $whereBeteween) AS `income_amount` ";
			
			$GetStockCharts = $this->db->table('accounts_chart')->where('chart_status',"active")->where('is_delete',"false")->get(null,$selectRaw);
			
			$totalAccountPurchaseExpense = 0;	
			$totalAccountSalesIncome = 0;	
			$totalAccountNonOperatingIncome = 0;	
			$totalAccountOperatingExpense = 0;	
			$totalAccountAdministrativeExpense = 0;	
			$totalAccountNonOperatingExpense = 0;	
			
			foreach($GetStockCharts as $GetStockChart){
				if($GetStockChart['chart_category_name'] == 'account_cost_of_sold_goods'){
					$totalAccountPurchaseExpense += $GetStockChart['expense_amount'];
				}
				
				if($GetStockChart['chart_category_name'] == 'account_income_of_sold_goods'){
					$totalAccountSalesIncome += $GetStockChart['income_amount'];
				}
				
				if($GetStockChart['chart_category_name'] == 'account_non_operating_income'){
					$totalAccountNonOperatingIncome += $GetStockChart['income_amount'];
				}
				
				if($GetStockChart['chart_category_name'] == 'account_operating_cost'){
					$totalAccountOperatingExpense += $GetStockChart['expense_amount'];
				}
				
				if($GetStockChart['chart_category_name'] == 'account_administrative_cost'){
					$totalAccountAdministrativeExpense += $GetStockChart['expense_amount'];
				}
				
				if($GetStockChart['chart_category_name'] == 'account_non_operating_cost'){
					$totalAccountNonOperatingExpense += $GetStockChart['expense_amount'];
				}
			}
			
			$selectStock = ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_type` = 'out' THEN product_quantity END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `out_quantity`  ";
			$selectStock .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_type` = 'in' THEN product_quantity END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `in_quantity`";
			$selectStock .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_category` = 'purchase' AND `pos_stock`.`stock_type` = 'in' THEN product_quantity END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `purchase_quantity` ";
			$selectStock .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_category` = 'purchase' AND `pos_stock`.`stock_type` = 'in' THEN product_subtotal END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `purchase_total` ";
			$selectStock .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_category` = 'sales' AND `pos_stock`.`stock_type` = 'out' THEN product_quantity END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `sales_quantity` ";
			$selectStock .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_category` = 'sales' AND `pos_stock`.`stock_type` = 'out' THEN product_subtotal END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `sales_total` ";
			$selectStock .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_category` = 'return' AND `pos_stock`.`stock_type` = 'out'  THEN product_quantity END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `purchase_return_quantity` ";
			$selectStock .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_category` = 'return' AND `pos_stock`.`stock_type` = 'out'  THEN product_subtotal END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `purchase_return_total` ";
			$selectStock .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_category` = 'return' AND `pos_stock`.`stock_type` = 'in'  THEN product_quantity END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `sales_return_quantity` ";
			$selectStock .= ", (SELECT COALESCE(SUM(CASE WHEN `pos_stock`.`stock_category` = 'return' AND `pos_stock`.`stock_type` = 'in'  THEN product_subtotal END), 0) FROM `pos_stock` WHERE `pos_stock`.`sub_product_id` = `pos_variations`.`sub_product_id` $whereStoreStock $whereBeteweenStock) AS `sales_return_total` ";
			
			$GetProductStocks = $this->db->table('pos_variations')->where('is_delete',"false");
			
			$GetProductStocks = $GetProductStocks->get(null,$selectStock);
			$totalStockQuantity = 0;
			$totalStockAmount = 0;
			$totalSales = 0;
			$totalSalesReturn = 0;
			$totalSalesAmount = 0;
			$totalPurchase = 0;
			$totalPurchaseAmount = 0;
			$totalPurchaseReturnAmount = 0;
			$totalSalesPurchaseAmount = 0;
			$subTotalStockAmount = 0;
			$subTotalSalesAmount = 0;
			for ($x = 0; $x < count($GetProductStocks); $x++) {
				if($GetProductStocks[$x]['purchase_quantity'] > 0){
					$GetProductStocks[$x]['purchase_avg_price'] = $GetProductStocks[$x]['purchase_total'] / $GetProductStocks[$x]['purchase_quantity'];
					$subTotalStockAmount += $GetProductStocks[$x]['purchase_total'] - $GetProductStocks[$x]['purchase_return_total'];
					}else{
					$GetProductStocks[$x]['purchase_avg_price'] = $GetProductStocks[$x]['purchase_price'];
				}
				
				if($GetProductStocks[$x]['sales_quantity'] > 0){
					$GetProductStocks[$x]['sales_avg_price'] = $GetProductStocks[$x]['sales_total'] / $GetProductStocks[$x]['sales_quantity'];
					$subTotalSalesAmount += $GetProductStocks[$x]['sales_total'] - $GetProductStocks[$x]['sales_return_quantity'];
					}else{
					$GetProductStocks[$x]['sales_avg_price'] = $GetProductStocks[$x]['sell_price'];
				}
				
				$GetProductStocks[$x]['current_stock'] = $GetProductStocks[$x]['in_quantity'] - $GetProductStocks[$x]['out_quantity'];
				$GetProductStocks[$x]['current_stock_total'] = $GetProductStocks[$x]['purchase_avg_price'] * $GetProductStocks[$x]['current_stock'];
				$totalStockQuantity += $GetProductStocks[$x]['current_stock'];
				$totalStockAmount += $GetProductStocks[$x]['current_stock_total'];
				
				$GetProductStocks[$x]['current_sales'] = $GetProductStocks[$x]['sales_avg_price'] * $GetProductStocks[$x]['sales_quantity'];
				$GetProductStocks[$x]['current_sales_return'] = $GetProductStocks[$x]['sales_avg_price'] * $GetProductStocks[$x]['sales_return_quantity'];
				$GetProductStocks[$x]['current_sales_total'] = $GetProductStocks[$x]['current_sales'] - $GetProductStocks[$x]['current_sales_return'];
				$totalSales += $GetProductStocks[$x]['current_sales'];
				$totalSalesReturn += $GetProductStocks[$x]['current_sales_return'];
				$totalSalesAmount += $GetProductStocks[$x]['current_sales_total'];
				
				$GetProductStocks[$x]['current_purchase'] = $GetProductStocks[$x]['purchase_avg_price'] * $GetProductStocks[$x]['purchase_quantity'];
				$GetProductStocks[$x]['current_purchase_return'] = $GetProductStocks[$x]['purchase_avg_price'] * $GetProductStocks[$x]['purchase_return_quantity'];
				$GetProductStocks[$x]['current_purchase_total'] = $GetProductStocks[$x]['current_purchase'] - $GetProductStocks[$x]['current_purchase_return'];
				$totalPurchase += $GetProductStocks[$x]['current_purchase'];
				$totalPurchaseReturnAmount += $GetProductStocks[$x]['current_purchase_return'];
				$totalPurchaseAmount += $GetProductStocks[$x]['current_purchase_total'];
				
				$GetProductStocks[$x]['current_sales_purchase'] = ($GetProductStocks[$x]['purchase_avg_price'] * $GetProductStocks[$x]['sales_quantity']) - ($GetProductStocks[$x]['purchase_avg_price'] * $GetProductStocks[$x]['sales_return_quantity']);
				$GetProductStocks[$x]['current_profit'] = $GetProductStocks[$x]['current_sales_total'] - $GetProductStocks[$x]['current_sales_purchase'];
				
				$totalSalesPurchaseAmount += $GetProductStocks[$x]['current_sales_purchase'];
				
			}
			
			$GetProductStocks['total_purchase_total'] = $GetStockPurchase["purchase_total"];
			$GetProductStocks['total_sales_total'] = $GetStockSales["sales_total"];
			$GetProductStocks['total_purchase_return_total'] = $GetStockPurchaseReturn["return_total"];
			$GetProductStocks['total_sales_return_total'] = $GetStockSalesReturn["return_total"];
			$GetProductStocks['total_sales_shipping_charge'] = $GetStockSales["shipping_charge"];
			$GetProductStocks['total_sales_discount'] = $GetStockSales["sales_discount"];
			$GetProductStocks['total_sales_vat'] = $GetStockSales["sales_vat"];
			$GetProductStocks['total_sales_return_vat'] = $GetStockSalesReturn["return_vat"];
			$GetProductStocks['total_sales'] = $totalSales;
			$GetProductStocks['total_sales_amount'] = $totalSalesAmount;
			$GetProductStocks['total_sales_return'] = $totalSalesReturn;
			$GetProductStocks['total_stock_quantity'] = $totalStockQuantity;
			$GetProductStocks['total_stock_amount'] = $totalStockAmount;
			$GetProductStocks['total_purchase'] = $totalPurchase;
			$GetProductStocks['total_purchase_return'] = $totalPurchaseReturnAmount;
			$GetProductStocks['total_purchase_amount'] = $totalPurchaseAmount;
			$GetProductStocks['total_sales_purchase_amount'] = $totalSalesPurchaseAmount;
			
			$GetProductStocks['total_purchase_expense'] = $totalAccountPurchaseExpense;
			$GetProductStocks['total_sales_income'] = $totalAccountSalesIncome;
			$GetProductStocks['total_non_operating_income'] = $totalAccountNonOperatingIncome;
			$GetProductStocks['total_operating_expense'] = $totalAccountOperatingExpense;
			$GetProductStocks['total_administrative_expense'] = $totalAccountAdministrativeExpense;
			$GetProductStocks['total_non_operating_expense'] = $totalAccountNonOperatingExpense;
			
			if($subTotalSalesAmount > 0){
				$salesDicountPercentage = ($GetProductStocks["total_sales_discount"] * 100) / $subTotalSalesAmount;
				$salesChargesPercentage = ($GetProductStocks["total_sales_shipping_charge"] * 100) / $subTotalSalesAmount;
				}else{
				$salesDicountPercentage = 0;
				$salesChargesPercentage = 0;
			}
			
			if($totalSalesReturn > 0){
				$salesReturnDicountPercentage = ($GetStockSalesReturn["return_discount"] * 100) / $totalSalesReturn;
				}else{
				$salesReturnDicountPercentage = 0;
			}
			
			
			$salesDiscount = $GetProductStocks['total_sales_amount'] * ($salesDicountPercentage / 100);
			$salesCharges = $GetProductStocks['total_sales_amount'] * ($salesChargesPercentage / 100);
			$GetProductStocks['total_sales_return_discount'] = $GetProductStocks['total_sales_return'] * ($salesReturnDicountPercentage / 100);
			
			
			
			
			
			$GetProductStocks['total_purchase_discount'] = $GetStockPurchase["purchase_discount"];
			$GetProductStocks['total_purchase_shipping_charge'] = $GetStockPurchase["purchase_shipping_charge"];
			
			if($subTotalStockAmount > 0){
				$GetProductStocks['total_purchase_discount_percentage'] = ($GetProductStocks["total_purchase_discount"] * 100) / $subTotalStockAmount;
				$GetProductStocks['total_purchase_charge_percentage'] = ($GetProductStocks["total_purchase_shipping_charge"] * 100) / $subTotalStockAmount;
				$GetProductStocks['total_purchase_expense_percentage'] = ($totalAccountPurchaseExpense * 100) / $subTotalStockAmount;
				}else{
				$GetProductStocks['total_purchase_discount_percentage'] = 0;
				$GetProductStocks['total_purchase_charge_percentage'] = 0;
				$GetProductStocks['total_purchase_expense_percentage'] = 0;
			}
			
			$salesPurchaseDiscount = $GetProductStocks['total_sales_purchase_amount'] * ($GetProductStocks['total_purchase_discount_percentage'] / 100);
			$salesPurchaseCharges = $GetProductStocks['total_sales_purchase_amount'] * ($GetProductStocks['total_purchase_charge_percentage'] / 100);
			$salesPurchaseAccountCharges = $GetProductStocks['total_sales_purchase_amount'] * ($GetProductStocks['total_purchase_expense_percentage'] / 100);
			
			$purchaseDiscount = $GetProductStocks['total_purchase_amount'] * ($GetProductStocks['total_purchase_discount_percentage'] / 100);
			$purchaseCharges = $GetProductStocks['total_purchase_amount'] * ($GetProductStocks['total_purchase_charge_percentage'] / 100);
			$purchaseAccountCharges = $GetProductStocks['total_purchase_amount'] * ($GetProductStocks['total_purchase_expense_percentage'] / 100);
			
			
			$stockDiscount = $GetProductStocks['total_stock_amount'] * ($GetProductStocks['total_purchase_discount_percentage'] / 100);
			$stockCharges = $GetProductStocks['total_stock_amount'] * ($GetProductStocks['total_purchase_charge_percentage'] / 100);
			$stockAccountCharges = $GetProductStocks['total_stock_amount'] * ($GetProductStocks['total_purchase_expense_percentage'] / 100);
			
			$GetProductStocks['net_sales_amount'] = $GetProductStocks['total_sales_amount']  - $salesDiscount + $salesCharges + $GetProductStocks['total_sales_income'] - $GetProductStocks['total_sales_return_discount'];
			$GetProductStocks['net_purchase_amount'] = $GetProductStocks['total_purchase_amount']  - $purchaseDiscount + $purchaseCharges + $purchaseAccountCharges;
			$GetProductStocks['net_sales_purchase_amount'] = $GetProductStocks['total_sales_purchase_amount']  - $salesPurchaseDiscount + $salesPurchaseCharges + $salesPurchaseAccountCharges;
			$GetProductStocks['net_stock_amount'] = $GetProductStocks['total_stock_amount']  - $stockDiscount + $stockCharges + $stockAccountCharges;
			$GetProductStocks['net_profit_amount'] = $GetProductStocks['net_sales_amount'] - $GetProductStocks['net_sales_purchase_amount'];
			
			$GetProductStocks['net_income'] = $GetProductStocks['net_profit_amount']
			+ $GetProductStocks['total_non_operating_income']
			- $GetProductStocks['total_operating_expense']
			- $GetProductStocks['total_administrative_expense']
			- $GetProductStocks['total_non_operating_expense']
			;
			if($jsonEncode){
				return respond([
				$GetProductStocks
				]);
				}else{
				return $GetProductStocks;
			}
		}
		
		public function getCheckaccountDueAdvance($data,$jsonEncode = true){
			
			$whereStore = '';
			if(isset($data['business_location']) && $data['business_location'] != '0'){
				$store =  $data['business_location'];
				$whereStore = " AND `accounts_transactions`.`store_id` = '$store'";
			}
			
			$whereContact = '';
			if(isset($data['user_code']) && $data['user_code'] != '0'){
				$accountfor =  $data['user_code'];
				$whereContact = " AND `accounts_transactions`.`payer_name` = '$accountfor'";
			}
			if($data['account_type'] == 'account_expense'){
				$amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`dr_account_status` != 'due' AND `accounts_transactions`.`cr_account_status` != 'advance' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account`";
				$paid_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
				$due_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`cr_account_status` = 'due' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
				$due_paid_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`dr_account_status` = 'due' AND `accounts_transactions`.`cr_account_status` != 'due' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
				$advance_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`dr_account_status` = 'advance' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
				$advance_paid_amount = " `accounts_transactions`.`account_type` = 'account_expense' AND `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_transactions`.`cr_account_status` = 'advance' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`dr_account` ";
				
				$selectRaw = ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $amount $whereStore $whereContact ) as `amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $paid_amount $whereStore $whereContact ) as `paid_amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $due_amount $whereStore $whereContact ) as `due_amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $due_paid_amount $whereStore $whereContact ) as `due_paid_amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $advance_amount $whereStore $whereContact ) as `advance_amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $advance_paid_amount $whereStore $whereContact ) as `advance_paid_amount`";
				
				}elseif($data['account_type'] == 'account_income'){
				$amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`dr_account_status` != 'advance' AND `accounts_transactions`.`cr_account_status` != 'due' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account`";
				$paid_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`dr_account_status` = 'paid' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
				$due_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`dr_account_status` = 'due' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
				$due_paid_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`dr_account_status` != 'due' AND `accounts_transactions`.`cr_account_status` = 'due' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
				$advance_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`cr_account_status` = 'advance' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
				$advance_paid_amount = " `accounts_transactions`.`account_type` = 'account_income' AND `accounts_transactions`.`cr_account_status` = 'paid' AND `accounts_transactions`.`dr_account_status` = 'advance' AND `accounts_chart`.`chart_name_value` = `accounts_transactions`.`cr_account` ";
				
				
				$selectRaw = ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $amount $whereStore $whereContact ) as `amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $paid_amount $whereStore $whereContact ) as `paid_amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $due_amount $whereStore $whereContact ) as `due_amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $due_paid_amount $whereStore $whereContact ) as `due_paid_amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $advance_amount $whereStore $whereContact ) as `advance_amount`";
				$selectRaw .= ", (SELECT SUM(`accounts_transactions`.`amount`) FROM `accounts_transactions` WHERE `accounts_transactions`.`is_delete` = 'false' AND $advance_paid_amount $whereStore $whereContact ) as `advance_paid_amount`";
				
			}
			
			$accountTransaction = $this->db->select("SELECT `accounts_chart`.`chart_name_value` $selectRaw FROM `accounts_chart` WHERE `accounts_chart`.`is_delete` = 'false' AND `chart_name_value` = :id ", array( 
			'id' => $data['chart_category']
			));
			
			if($accountTransaction && $accountTransaction[0]['amount']){
				$accountTransaction[0]['amount'] = $accountTransaction[0]['amount'];
				$accountTransaction[0]['due_amount'] = $accountTransaction[0]['due_amount'] - $accountTransaction[0]['due_paid_amount'];
				$accountTransaction[0]['advance_amount'] = $accountTransaction[0]['advance_amount'] - $accountTransaction[0]['advance_paid_amount'];
				$accountTransaction[0]['paid_amount'] = $accountTransaction[0]['paid_amount'] - $accountTransaction[0]['advance_amount'];
				$check_due = 'false';
				$check_advance = 'false';
				$accountTransaction[0]['is_due'] = 'false';
				$accountTransaction[0]['is_advance'] = 'false';
				if($accountTransaction[0]['due_amount'] > 0){
					$check_due = 'true';
					$accountTransaction[0]['is_due'] = 'true';
				}
				
				if($accountTransaction[0]['advance_amount'] > 0){
					$check_advance = 'true';
					$accountTransaction[0]['is_advance'] = 'true';
				}
				if($jsonEncode){
					respond([
					"status" => "found",
					"amount" => $accountTransaction[0]['amount'],
					"due_amount" => $accountTransaction[0]['due_amount'],
					"advance_amount" => $accountTransaction[0]['advance_amount'],
					"paid_amount" => $accountTransaction[0]['paid_amount'],
					"is_due" => $check_due,
					"is_advance" => $check_advance,
					]);
					}else{
					return $accountTransaction;
				}
				
				}else{
				respond(array(
				"status" => "not_found"
				));
			}
		}
		
		public function GetInvoiceAdvanceData($data,$userId,$storeId) {
			if(empty($data['customer_code'])){
				if($data['sales_total_amount'] <= $data['payment_amount']){
					$data['customer_code'] = "CUS00000000";
					}else{
					respond(array(
					'status' => 'error',
					'errors' => array(
					'customer_code' => trans('need_customer_id_for_due_sales'),
					'payment_amount' => trans('due_sales_required_customar_id'),
					)), 422);
				}
				}else{
				if(!$this->db->select("SELECT `contact_id` FROM `pos_contact` WHERE `contact_id`= :id ", array( 'id' => $data['customer_code']))){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'customer_code' => trans('worng_customer_id_input')
					)
					), 422);
				}
			}	
			
			if(!isset($data['sales_note'])){
				$data['sales_note'] = null;
			}
			if(isset($data['sub_product_id'])){
				if(count($data['sub_product_id']) == 0){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'sales_barcode' => trans('add_some_sales_product_first')
					)), 422);
				}
				}else{
				respond(array(
				'status' => 'error',
				'errors' => array(
				'sales_barcode' => trans('add_some_sales_product_first')
				)), 422);
			}
			$stock_status = "inactive";
			if($data['sales_status'] == 'complete'){
				$stock_status = "active";
			}
			if($this->db->select("SELECT `sales_id` FROM `pos_sales` WHERE `sales_id`= :id ", array( 'id' => $data['sales_id']))){
				$this->db->update("pos_sales" ,  array( 
				"store_id" => $storeId,
				"user_id" => $userId,
				"customer_id" => $data['customer_code'],
				"sales_subtotal" => $data['sales_sub_total'],
				"sales_total" => $data['sales_total_amount'],
				"sales_vat" => $data['sales_vat'],
				"sales_discount" => $data['sales_discount'],
				"sales_note" => $data['sales_note'],
				"shipping_charge" => $data['additional_shipping_charge'],
				"sales_status" => $data['sales_status'],
				"update_at"=> date("Y-m-d H:i:s")
				), "`sales_id` = :id ", array("id"=> $data['sales_id']));
				}else{
				$this->db->insert("pos_sales",  array(
				"store_id" => $storeId,
				"user_id" => $userId,
				"customer_id" => $data['customer_code'],
				"sales_type" => "invoice",
				"sales_id" => $data['sales_id'],
				"sales_subtotal" => $data['sales_sub_total'],
				"sales_total" => $data['sales_total_amount'],
				"sales_vat" => $data['sales_vat'],
				"sales_discount" => $data['sales_discount'],
				"sales_note" => $data['sales_note'],
				"sales_payment_status" => "due",
				"shipping_charge" => $data['additional_shipping_charge'],
				"sales_status" => $data['sales_status'],
				"created_at"=> date("Y-m-d H:i:s"),
				"update_at"=> date("Y-m-d H:i:s")
				));
			}
			for ($x = 0; $x < count($data['sub_product_id']); $x++) {
				$sub_product_id = $data['sub_product_id'][$x];
				$product_id = $data['product_id'][$x];
				$product_stock_id = $data['product_stock_id'][$x];
				$product_vat = $data['product_vat'][$x];
				$total_product_vat = $data['total_product_vat'][$x];
				$product_price = $data['product_price'][$x];
				$product_quantity = $data['product_quantity'][$x];
				$product_subtotal = $data['product_subtotal'][$x];
				if($this->db->select("SELECT `stock_id` FROM `pos_stock` WHERE `stock_id`= :id ", array('id' => $product_stock_id))){
					$this->db->update("pos_stock" ,  array( 
					"store_id" => $storeId,
					"product_id" => $product_id,
					"sales_id" => $data['sales_id'],
					"sub_product_id" => $sub_product_id,
					"customer_id" => $data['customer_code'],
					"product_quantity" => $product_quantity,
					"product_price" => $product_price,
					"product_subtotal" => $product_subtotal,
					"product_vat" => $product_vat,
					"product_vat_total" => $total_product_vat,
					"user_id" => $userId,
					"stock_status" => $stock_status,
					"update_at"=> date("Y-m-d H:i:s")
					), "`stock_id`= :id", array('id' => $product_stock_id));
					}else{
					$this->db->insert("pos_stock",  array(
					"store_id" => $storeId,
					"stock_id" => $product_stock_id,
					"product_id" => $product_id,
					"sales_id" => $data['sales_id'],
					"sub_product_id" => $sub_product_id,
					"customer_id" => $data['customer_code'],
					"product_quantity" => $product_quantity,
					"product_price" => $product_price,
					"product_subtotal" => $product_subtotal,
					"product_vat" => $product_vat,
					"product_vat_total" => $total_product_vat,
					"stock_category" => 'sales',
					"stock_type" => 'out',
					"user_id" => $userId,
					"stock_status" => $stock_status,
					"created_at"=> date("Y-m-d H:i:s"),
					"update_at"=> date("Y-m-d H:i:s")
					));
				}
				
				if (isset($data['product_serial_id'][$product_id])) {
					
					for ($serials=0; $serials < count($data['product_serial_id'][$product_id]) ; $serials++) { 
						
						$this->db->update("pos_product_serial" ,  array( 
						"sales_id" => $data['sales_id'],
						"sell_stock_id" => $product_stock_id,
						"customer_id" => $data['customer_code'],
						"product_serial_status" => 'sell',
						"product_serial_stock_type" => 'out',
						"sold_at" => date("Y-m-d H:i:s")
						), "`product_serial_no`= :id AND `sub_product_id`= :pid", array('id' => $data['product_serial_id'][$product_id][$serials], 'pid' => $sub_product_id));
					}
				}
			}
			if(!empty($data['payment_amount'])){
				$totalPaidAmount = $this->GetSalesByCustomerOrder(false,$data['sales_id']);
				$TotalAmountOfPaid = $data['payment_amount'] + $totalPaidAmount['total_paid'];
				if($TotalAmountOfPaid > $data['sales_total_amount']){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'payment_amount' => trans('over_payment')
					)
					), 422);
				}
				$this->db->insert("pos_transactions",  array(
				"store_id" => $storeId,
				"user_id" => $userId,
				"sales_id" => $data['sales_id'],
				"transaction_type" => 'sales',
				"transaction_amount" => $data['payment_amount'],
				"transaction_flow_type" => 'credit',
				"payment_method_value" => $data['payment_method'],
				"transaction_no" => $data['payment_transaction_id'],
				"transaction_note" => $data['payment_note'],
				"transaction_status" => 'paid',
				"payment_for" => $data['customer_code'],
				"paid_date"=> date("Y-m-d H:i:s"),
				"created_at"=> date("Y-m-d H:i:s"),
				"update_at"=> date("Y-m-d H:i:s")
				));
				if($data['sales_total_amount'] <= $TotalAmountOfPaid){	
					$this->db->update("pos_sales" ,  array( 
					"sales_payment_status" => 'paid'
					), "`sales_id` = :id ", array("id"=> $data['sales_id']));
					}else{
					$this->db->update("pos_sales" ,  array( 
					"sales_payment_status" => 'due'
					), "`sales_id` = :id ", array("id"=> $data['sales_id']));
				}
			}
			respond(array(
			"status" => "success",
			"message" => trans('invoice_sales_complete'),
			"sales_id" => $data['sales_id']
			));
		}
		
		public function LogoutSubmit($userId){
			$registerOpen = app('admin')->getwhereandid('pos_register_report','user_id',$userId,'register_status','open');
			
			if ($registerOpen){
				respond(array(
				"status" => "open"
				));
				}else{
				respond(array(
				"status" => "close"
				));
			}
		}
		
		public function RegisterSubmit($data,$userId,$storeId)
		{
			if(isset($data['register_id'])){
				$this->db->update("pos_register_report" ,  array(
				"register_close_balance" => $data['closing_amount'],
				"register_close" => $data['closing_time'],
				"register_status" => 'close',
				"closing_note" => $data['register_close_note']
				),
				"`register_id` = :id ",
				array("id"=> $data['register_id'])
				);
				respond(array(
				"status" => "success",
				"url" => "pos/home"
				));
				}else{
				$register_id = 'REG'.gettoken(8);
				$this->db->insert("pos_register_report",array(
				"register_id" => $register_id,
				"user_id" => $userId,
				"store_id" => $data['store_id'],
				"register_open" => date("Y-m-d H:i:s"),
				"register_open_balance" => $data['amount'],
				"register_status" => 'open',
				"opening_note" => $data['note']
				));
				respond(array(
				"status" => "success",
				"url" => "pos/pos-sales"
				));
			}
		}
		
		public function UpdateStoreid($data,$userId,$storeId)
		{
			app('root')->update("as_user_details" ,  array(
			"store_id" => $data['store_id']
			),
			"`user_id` = :id ",
			array("id"=> $userId)
			);
			app('pos')->RegisterSubmit($data,$userId,$storeId);
		}
		
		public function AddSalesReturn($data,$userId,$storeid){
			
			
			if($this->db->select("SELECT `return_id` FROM `pos_return` WHERE `return_id`= :id AND `is_delete`= 'false' ", array( 'id' => $data['return_id']))){
				$this->db->update("pos_return" ,  array( 
				"user_id" => $userId,
				"return_subtotal" => $data['sales_return_subtotal'],
				"return_discount" => $data['sales_return_discount'],
				"return_total" => $data['sales_return_total'],
				"return_vat" => $data['sales_return_vat'],
				"return_note" => $data['return_note'],
				"update_at"=> date("Y-m-d H:i:s"),
				"document" => $data['document']
				), "`return_id` = :id ", array("id"=> $data['return_id']));
				}else{
				$this->db->insert("pos_return",  array(
				"return_id" => $data['return_id'],
				"sales_id" => $data['sales_id'],
				"customer_id" => $data['customer_id'],
				"store_id" => $storeid,
				"user_id" => $userId,
				"return_subtotal" => $data['sales_return_subtotal'],
				"return_discount" => $data['sales_return_discount'],
				"return_total" => $data['sales_return_total'],
				"return_vat" => $data['sales_return_vat'],
				"return_note" => $data['return_note'],
				"created_at"=> date("Y-m-d H:i:s"),
				"document" => $data['document'],
				"return_type" => 'sales',
				"return_status" => 'returned'
				));
			}
			
			for ($x = 0; $x < count($data['sub_product_id']); $x++) {
				$product_stock_id = 'ST'.gettoken(8);
				$product_id = $data['product_id'][$x];
				$sub_product_id = $data['sub_product_id'][$x];
				$already_returned = (float) $data['already_returned'][$x];
				$total_return_product_quantity = $data['return_product_quantity'][$x];
				$return_product_quantity = (float) $total_return_product_quantity + $already_returned;
				$product_price = $data['product_price'][$x];
				$return_product_sub_total = $data['return_product_sub_total'][$x];
				if($return_product_quantity > 0 ){
					if($this->db->select("SELECT `return_id`,sub_product_id FROM `pos_stock` WHERE `return_id`= :id AND `sub_product_id`= :spid AND `is_delete`= 'false' ", array( 'id' => $data['return_id'], 'spid' => $sub_product_id))){
						$this->db->update("pos_stock" ,  array( 
						"product_quantity" => $return_product_quantity,
						"product_price" => $product_price,
						"product_subtotal" => $return_product_sub_total,
						"stock_status" => 'active',
						"user_id" => $userId,
						"update_at"=> date("Y-m-d H:i:s")
						), "`return_id`= :id AND `sub_product_id`= :spid ", array('id' => $data['return_id'], 'spid' => $sub_product_id));
						}else{
						$this->db->insert("pos_stock",  array(
						"stock_id" => $product_stock_id,
						"product_id" => $product_id,
						"store_id" => $storeid,
						"sub_product_id" => $sub_product_id,
						"customer_id" => $data['customer_id'],
						"sales_id" => $data['sales_id'],
						"return_id" => $data['return_id'],
						"product_quantity" => $return_product_quantity,
						"product_price" => $product_price,
						"product_subtotal" => $return_product_sub_total,
						"stock_category" => 'return',
						"stock_type" => 'in',
						"stock_status" => 'active',
						"stock_date"=> date("Y-m-d"),
						"user_id" => $userId,
						"created_at"=> date("Y-m-d H:i:s")
						));
					}
					if(isset($data['serial_number'][$sub_product_id])){
						for ($serials=0; $serials < count($data['serial_number'][$sub_product_id]) ; $serials++) { 
							$this->db->update("pos_product_serial" ,  array( 
							"return_id" => $data['return_id'],
							"product_serial_status" => 'received',
							"product_serial_stock_type" => 'in',
							"product_serial_category" => 'sell_return',
							"sold_at" => date("Y-m-d H:i:s")
							), "`product_serial_no`= :id AND `sub_product_id`= :pid", array('id' => $data['serial_number'][$sub_product_id][$serials], 'pid' => $sub_product_id));
						}
					}
				}
			}
			
			if(app('admin')->checkAddon('multiple_payment_method')){ 
				if(isset($data['transaction_id'])){ 
					
					for ($x = 0; $x < count($data['transaction_id']); $x++) {
						$posTransactionData['transaction_type'] = 'return';
						$posTransactionData['transaction_amount'] = $data['transaction_amount'][$x];
						$posTransactionData['transaction_flow_type'] = 'debit';
						$posTransactionData['payment_method_value'] = $data['transaction_method'][$x];
						$posTransactionData['transaction_no'] = $data['transaction_no'][$x];
						$posTransactionData['transaction_note'] = $data['transaction_note'][$x];
						$posTransactionData['sales_id'] = $data['sales_id'];
						$posTransactionData['transaction_status'] = 'paid';
						$posTransactionData['is_return'] = 'true';
						$posTransactionData['payment_for'] = $data['customer_id'];
						
						$transactionCheck = $this->db->table('pos_transactions')->where("transaction_id",$data['transaction_id'][$x])->where("transaction_type",'return')->get(1);
						if($transactionCheck){
							$this->db->update("pos_transactions" ,  $posTransactionData , "`transaction_id` = :id AND `transaction_type` = :type ", array("id"=> $data['transaction_id'][$x], "type"=> "return"));
							}else{
							$posTransactionData['user_id'] = $userId;
							$posTransactionData['transaction_id'] = $data['transaction_id'][$x];
							$posTransactionData['return_id'] = $data['return_id'];
							$posTransactionData['created_at'] = date("Y-m-d H:i:s");
							$this->db->insert("pos_transactions", $posTransactionData);
						}
					}
				}
				}else{
				
				$data['transaction_id'] = 'TXN'.gettoken(8);
				
				if($this->db->table('pos_transactions')->where('return_id',$data['return_id'])->where('transaction_type','return')->where('is_delete','false')->get()){
					$this->db->update("pos_transactions",  array(
					"store_id" => $storeid,
					"user_id" => $userId,
					"sales_id" => $data['sales_id'],
					"transaction_amount" => $data['sales_return_total'],
					"payment_for" => $data['customer_id'],
					"paid_date"=> date("Y-m-d H:i:s"),
					"update_at"=> date("Y-m-d H:i:s")
					), "`return_id`= :id AND `transaction_type`= 'return' AND `is_delete`= 'false'", array('id' => $data['return_id']));
					}else{
					$this->db->insert("pos_transactions",  array(
					"store_id" => $storeid,
					"user_id" => $userId,
					"sales_id" => $data['sales_id'],
					"transaction_id" => $data['transaction_id'],
					"return_id" => $data['return_id'],
					"transaction_type" => 'return',
					"transaction_amount" => $data['sales_return_total'],
					"transaction_flow_type" => 'debit',
					"transaction_note" => "Sales Return Paid",
					"is_return" => 'true',
					"transaction_status" => 'paid',
					"payment_for" => $data['customer_id'],
					"paid_date"=> date("Y-m-d H:i:s"),
					"created_at"=> date("Y-m-d H:i:s"),
					"update_at"=> date("Y-m-d H:i:s")
					));
				}
			}
			
			respond(array(
			"status" => "success",
			"massage" => trans('sales_return_complete'),
			"payment" => "false"
			));
			
		}
		
		public function GetDeleteSalesRow($data){
			$this->db->update("pos_stock" , array('stock_status' => 'cancel','is_delete' => 'true'), "`stock_id`= :id", array('id' => $data['stock_id']));
			return true;
		}
		public function GetPosPermission()
		{ 
			$result = $this->db->select("SELECT * FROM `pos_user_permission`");
			return $result;
		}
		
		public function getPosImages($route){
			$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../');
			$filesystem = new League\Flysystem\Filesystem($adapter);
			$manager = Intervention\Image\ImageManagerStatic::configure(array('driver' => 'imagick'));
			$imagesLocation ="images/stores/".$_SERVER['SERVER_NAME'].'/';
			$wmimagesLocation ="assets/img/sold-out.png";
			$noImagesLocation ="assets/img/no-image.png";
			if(isset($route['id'])){
				if($filesystem->has($imagesLocation.$route['id'])){
					$imageLocation = $filesystem->read($imagesLocation.$route['id']);
					$image = $manager->make($imageLocation);
					$image->resize(250, 250);
					}else{
					$imageLocation = $filesystem->read($noImagesLocation);
					$image = $manager->make($imageLocation);
					$image->resize(250, 250);
				}
				}else{
				$imageLocation = $filesystem->read($noImagesLocation);
				$image = $manager->make($imageLocation);
				$image->resize(250, 250);
			}
			
			if(isset($_GET['wm'])){
				$wmimageLocation = $filesystem->read($wmimagesLocation);
				$watermark = $manager->make($wmimageLocation);
				$watermark->resize(250, 250);
				$image->insert($watermark, 'center');
				}else{
				$testBgWidth = strlen($_GET['st']) * 30;
				// $testBgWidth = 90;
				
				$img = $manager->canvas($testBgWidth, 48, '#000000');
				$img->text($_GET['st'], $testBgWidth, 23, function($font) {
					$font->file(dirname(__FILE__).'/../assets/css/summernote/font/arialbd.ttf');
					$font->size(50);
					$font->color('#ffffff');
					// $font->color(array(255, 255, 255, 0.5));
					$font->align('right');
					$font->valign('center');
					// $font->background('#ff0000');
				});
				$image->insert($img, 'bottom-right', 5, 5);
			}
			echo $image->response();
			$image->destroy();
		}
		
		public function InsertPurchaseProductAdvanced($data,$userId){
			
			// $loadPlugins = app('admin')->loadAddon('multiple_store_warehouse','add_purchase_store_validation_check'); 
			// if($loadPlugins['load_status']){ 
			// require dirname(__FILE__) .'/../'.$loadPlugins['location'];
			// }
			
			
			if(app('admin')->checkAddon('multiple_store_warehouse')){
				
				if(empty($data['store_id'])){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'store_id' => trans('store_id_required')
					)
					), 422);
				}
			}
			
			$AddProductValidation = false;
			if(!isset($data['sub_product_id'])){
				$AddProductValidation = true;
				}else{
				$totalProduct = 0;
				for ($x = 0; $x < count($data['sub_product_id']); $x++) {
					if($data['purchase_product_quantity'][$x] == 0){
						respond(array(
						'status' => 'error',
						'errors' => array(
						'purchase_quantity_'.$data['sub_product_id'][$x] => ' '
						)
						), 422);
					}
					$totalProduct += $data['purchase_product_quantity'][$x];
					
				}
				if($totalProduct == 0) $AddProductValidation = true;
			}
			
			if($AddProductValidation){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'purchase_barcode' => trans('add_some_purchase_product_first')
				)
				), 422);
			}
			
			if(empty($data['purchase_supplier'])){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'purchase_supplier' => trans('supplier_id_required')
				)
				), 422);
			}
			
			if(isset($data['submit']) & $data['submit'] == 'from_save'){
				if($this->db->select("SELECT `purchase_id` FROM `pos_purchase` WHERE `is_delete`= 'false' AND `purchase_id`= :id ", array( 'id' => $data['purchase_id']))){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'purchase_id' => trans('purchase_id_already_exits')
					)
					), 422);
				}
			}
			
			//-------Validation--------//
			$posStockPurchaseData = array();
			$posPurchaseData = array(
			"purchase_id" => $data['purchase_id'],
			"purchase_reference_no" => $data['purchase_reference_no'],
			"purchase_status" => $data['purchase_status'],
			"purchase_date" => $data['purchase_date'],
			"purchase_subtotal" => $data['purchase_subtotal'],
			"supplier_id" => $data['purchase_supplier'],
			"purchase_discount" => $data['purchase_discount'],
			"purchase_shipping_charge" => $data['purchase_shipping_charge'],
			"purchase_total" => $data['purchase_paid_amount'],
			"purchase_shipping_note" => $data['purchase_shipping_note'],
			"purchase_note" => $data['purchase_note'],
			"user_id" => $userId,
			"created_at"=> date("Y-m-d H:i:s"),
			"update_at"=> date("Y-m-d H:i:s")
			);
			
			if(app('admin')->checkAddon('multiple_store_warehouse')){
				if(isset($data['store_id'])){
					$posPurchaseData['store_id']= $data['store_id'];
					$posStockPurchaseData['store_id']=$data['store_id'];
				}
			} 
			if($this->db->select("SELECT `purchase_id` FROM `pos_purchase` WHERE `is_delete`= 'false' AND `purchase_id`= :id ", array( 'id' => $data['purchase_id']))){
				$this->db->update("pos_purchase" , $posPurchaseData , "`purchase_id` = :id ", array("id"=> $data['purchase_id']));
				}else{
				$this->db->insert("pos_purchase",$posPurchaseData);
			}
			
			for ($x = 0; $x < count($data['sub_product_id']); $x++) {
				$product_id = $data['product_id'][$x];
				$sub_product_id = $data['sub_product_id'][$x];
				$product_stock_id = $data['product_stock_id'][$x];
				$purchase_product_quantity = $data['purchase_product_quantity'][$x];
				$purchase_product_price = $data['purchase_product_price'][$x];
				$purchase_product_discount = $data['purchase_product_discount'][$x];
				$purchase_product_sub_total = $data['purchase_product_sub_total'][$x];
				$purchase_product_profit_percent = $data['purchase_product_profit_percent'][$x];
				$purchase_product_sales_price = $data['purchase_product_sales_price'][$x];
				
				$loadPlugins = app('admin')->loadAddon('expired_manufacturing_date','add_purchase_manufacturing_expired_date_insert'); 
				if($loadPlugins['load_status']){ 
					$posStockPurchaseData['expire_date'] =  $data['purchase_product_exp_date'][$x] ;
					$posStockPurchaseData['manufac_date'] = $data['purchase_product_mgf_date'][$x];
				}
				$posStockPurchaseData['stock_id'] = $product_stock_id ;
				$posStockPurchaseData['product_id'] = $product_id;
				$posStockPurchaseData['sub_product_id'] = $sub_product_id;
				$posStockPurchaseData['supplier_id'] = $data['purchase_supplier'];
				$posStockPurchaseData['purchase_id'] = $data['purchase_id'];
				$posStockPurchaseData['product_discount'] =  $purchase_product_discount;
				$posStockPurchaseData['stock_category'] = 'purchase';
				$posStockPurchaseData['stock_type'] = 'in';
				$posStockPurchaseData['product_quantity'] = $purchase_product_quantity;
				$posStockPurchaseData['product_price'] = $purchase_product_price;
				$posStockPurchaseData['product_subtotal'] = $purchase_product_sub_total;
				$posStockPurchaseData['stock_date'] = $data['purchase_date'];
				$posStockPurchaseData['user_id'] = $userId;
				$posStockPurchaseData['created_at'] = date("Y-m-d H:i:s");
				$posStockPurchaseData['update_at'] = date("Y-m-d H:i:s");
				
				
				if($this->db->select("SELECT `stock_id` FROM `pos_stock` WHERE `is_delete`= 'false' AND `stock_id`= :id ", array('id' => $product_stock_id))){
					$this->db->update("pos_stock" , $posStockPurchaseData , "`stock_id`= :id", array('id' => $product_stock_id));
					}else{
					$this->db->insert("pos_stock", $posStockPurchaseData );
				}
				
				if(app('admin')->checkAddon('serial_product')){
					if (isset($data['product_serial_id'][$product_id])) {
						
						for ($serials=0; $serials < count($data['product_serial_id'][$product_id]) ; $serials++) { 
							$this->db->insert("pos_product_serial",  array(
							"product_id" => $product_id,
							"sub_product_id" => $sub_product_id,
							"purchase_id" => $data['purchase_id'],
							"supplier_id" => $data['purchase_supplier'],
							"stock_id" => $product_stock_id,
							"product_serial_no" => $data['product_serial_id'][$product_id][$serials],
							"product_serial_category" => 'purchase',
							"product_serial_status" => 'received',
							"product_serial_stock_type" => 'in',
							"created_at" => date("Y-m-d H:i:s")
							));
						}
					}
				}
				
				$posVariationPurchaseData = array( 
				"purchase_price" => $purchase_product_price,
				"profit_percent" => $purchase_product_profit_percent,
				"sell_price" => $purchase_product_sales_price,
				"update_at" => date("Y-m-d H:i:s"),
				);
				
				$this->db->update("pos_variations" , $posVariationPurchaseData , "`sub_product_id` = :id ", array("id"=> $sub_product_id));
			}
			
			$posTransactionData = array(
			"paid_date"=> date("Y-m-d H:i:s"),
			"update_at"=> date("Y-m-d H:i:s")
			);
			
			$loadPlugins = app('admin')->loadAddon('multiple_store_warehouse','AddPurchaseTransactionStoreID'); 
			if($loadPlugins['load_status']){ 
				// require dirname(__FILE__) .'/../'.$loadPlugins['location'];
				$posTransactionData['store_id'] = $data['store_id'];
			}
			
			
			if(app('admin')->checkAddon('multiple_store_warehouse')){ 
				if(isset($data['transaction_id'])){ 
					// require dirname(__FILE__) .'/../'.$loadPlugins['location'];
					for ($x = 0; $x < count($data['transaction_id']); $x++) {
						$posTransactionData['transaction_type'] = 'purchase';
						$posTransactionData['transaction_amount'] = $data['transaction_amount'][$x];
						$posTransactionData['transaction_flow_type'] = 'debit';
						$posTransactionData['payment_method_value'] = $data['transaction_method'][$x];
						$posTransactionData['transaction_no'] = $data['transaction_no'][$x];
						$posTransactionData['transaction_note'] = $data['transaction_note'][$x];
						$posTransactionData['transaction_status'] = 'paid';
						$posTransactionData['payment_for'] = $data['purchase_supplier'];
						
						$transactionCheck = $this->db->table('pos_transactions')->where("transaction_id",$data['transaction_id'][$x])->where("transaction_type",'purchase')->get(1);
						if($transactionCheck){
							$this->db->update("pos_transactions" ,  $posTransactionData , "`transaction_id` = :id AND `transaction_type` = :type ", array("id"=> $data['transaction_id'][$x], "type"=> "purchase"));
							}else{
							$posTransactionData['user_id'] = $userId;
							$posTransactionData['transaction_id'] = $data['transaction_id'][$x];
							$posTransactionData['purchase_id'] = $data['purchase_id'];
							$posTransactionData['created_at'] = date("Y-m-d H:i:s");
							$this->db->insert("pos_transactions", $posTransactionData);
						}
						
					}
				}
				}else{
				$posTransactionData['transaction_type'] = 'purchase';
				$posTransactionData['transaction_amount'] = $data['purchase_paid_amount'];
				$posTransactionData['transaction_flow_type'] = 'debit';
				$posTransactionData['payment_method_value'] = 'cash';
				$posTransactionData['transaction_status'] = 'paid';
				$posTransactionData['payment_for'] = $data['purchase_supplier'];
				$transactionCheck = $this->db->table('pos_transactions')->where("purchase_id",$data['purchase_id'])->where("transaction_type",'purchase')->get(1);
				if($transactionCheck){
					$this->db->update("pos_transactions" ,  $posTransactionData , "`purchase_id` = :id AND `transaction_type` = :type ", array("id"=> $data['purchase_id'], "type"=> "purchase"));
					}else{
					$posTransactionData['transaction_id'] = 'TXN'.gettoken(8);
					$posTransactionData['user_id'] = $userId;
					$posTransactionData['purchase_id'] = $data['purchase_id'];
					$posTransactionData['created_at'] = date("Y-m-d H:i:s");
					$this->db->insert("pos_transactions", $posTransactionData);
				}
			}
			
			$totalPaidAmount =  $this->db->table('pos_transactions')->sum('transaction_amount')->where("purchase_id",$data['purchase_id'])->where("transaction_type",'purchase')->get(1);
			$posPurchaseStatusData = array();
			if($totalPaidAmount['transaction_amount'] >= $data['purchase_paid_amount'] ){
				$posPurchaseStatusData['purchase_payment_status'] = 'paid';
				}else{
				$posPurchaseStatusData['purchase_payment_status'] = 'due';
			}
			
			$this->db->update("pos_purchase" , $posPurchaseStatusData , "`purchase_id` = :id ", array("id"=> $data['purchase_id']));
			
			if(isset($data['purchase_document_name'])){
				$this->db->update("pos_purchase" ,  array( 
				"purchase_document" => $data['purchase_document_name']
				), "`purchase_id` = :id ", array("id"=> $data['purchase_id']));
			}
			
			respond(array(
			"status" => "success",
			"message" => trans('product_save_successfully')
			));
		}
		
		public function GetPurchaseBarcodeTypeSearch($data) {
			
			$sql = $this->db->select("SELECT `product_id`,`product_name`,`product_serial`,`product_type`,`is_delete` FROM `pos_product` WHERE `is_delete` = 'false' AND (`product_name` LIKE :id OR `product_id` LIKE :id )", array( 'id' => "%".$data['purchase_barcode']."%"));
			
			$checkV = false;
			foreach ($sql  as $sqls) {
				$i = 0;
				$getvariables = $this->db->select("SELECT `variation_name`,`product_id`,`sub_product_id`,`is_delete` FROM `pos_variations` WHERE `is_delete`= 'false' AND `product_id` = :id", array( 'id' => $sqls['product_id']));
				foreach($getvariables as $getvariable){
					$checkV = true;
					$myObj[$i] = new stdClass;
					if($sqls['product_type'] == "variable"){
						$myObj[$i]->name = $sqls['product_name'].' - '.$getvariable['variation_name'].' - '.$getvariable['sub_product_id'];
						$myObj[$i]->product_id = $getvariable['sub_product_id'];
						$myObj[$i]->product_serial = $sqls['product_serial'];
						}else{
						$myObj[$i]->name = $sqls['product_name'].' - '.$sqls['product_id'];
						$myObj[$i]->product_id = $sqls['product_id'];
						$myObj[$i]->product_serial = $sqls['product_serial'];
					}
					$i++;
				}
			}
			
			if ($checkV) {
				respond(array(
				"status" => "success",
				"source_data" => $myObj
				));
				}else {
				respond(array(
				"status" => "success",
				"source_data" => 'false'
				));
			}
		}
		
		public function PurchaseStatusChange($data){
			$this->db->update("pos_purchase" ,  array(
			"purchase_status" => $data['change_status'],
			"update_at"=> date("Y-m-d H:i:s")
			),
			"`purchase_id` = :id ",
			array("id"=> $data['id'])
			);
			respond(array(
			"st" => 'success',
			));
		}
		
		public function GetUnitNameView($product_unit) {
			$Getunits = $this->db->select("SELECT `unit_id`,`unit_name` FROM `pos_unit` WHERE `is_delete`= 'false' AND `unit_id`= :id ", array( 'id' => $product_unit));
			respond(array(
			"status" => "success",
			"product_unit_name" => $Getunits[0]['unit_name']
			));
		}
		
		public function AddNewBrandSubmit($data,$userId){
			$exits = "update";
			if (isset($data['brand_id'])){
				$this->db->update("pos_brands" ,  array(
				"brand_name"=> $data['brand_name'],
				"update_at"=> date("Y-m-d H:i:s")
				),
				"`brand_id` = :id ",
				array("id"=> $data['brand_id'])
				);
				}else{
				$checkUnit = $this->db->select("SELECT * FROM `pos_brands` WHERE `is_delete`= 'false' AND `brand_name`= :id ", array( 'id' => $data['brand_name']));
				if($checkUnit){
					$this->db->update("pos_brands" ,  array(
					"brand_name"=> $data['brand_name'],
					"update_at"=> date("Y-m-d H:i:s")
					),
					"`brand_id` = :id ",
					array("id"=> $checkUnit[0]['brand_id']));
					$data['brand_id'] = $checkUnit[0]['brand_id'];
					}else{
					$this->db->insert("pos_brands",  array(
					"brand_name"=> $data['brand_name'],
					"user_id"=> $userId,
					"created_at"=> date("Y-m-d H:i:s")
					));
					$data['brand_id'] = $this->db->lastInsertId();
					$exits = "new";
				}
				
			}
			respond(array(
			"status" => "success",
			"message" => trans('brand_add_successfully'),
			"brand_name" => $data['brand_name'],
			"brand_id" => $data['brand_id'],
			"update_type" => $exits
			));
		}
		
		public function AddNewCategorySubmit($data,$userId){
			$exits = "update";
			if (isset($data['category_id'])){
				$this->db->update("pos_category" ,  array(
				"category_name"=> $data['category_name'],
				"updated_at"=> date("Y-m-d H:i:s")
				),
				"`category_id` = :id ",
				array("id"=> $data['category_id'])
				);
				}else{
				$checkCategory = $this->db->select("SELECT * FROM `pos_category` WHERE `is_delete`= 'false' AND `category_name`= :id ", array( 'id' => $data['category_name']));
				if($checkCategory){
					$this->db->update("pos_category" ,  array(
					"category_name"=> $data['category_name'],
					"updated_at"=> date("Y-m-d H:i:s")
					),
					"`category_id` = :id ",
					array("id"=> $checkCategory[0]['category_id'])
					);
					$data['category_id'] = $checkCategory[0]['category_id'];
					}else{
					$this->db->insert("pos_category",  array(
					"category_name"=> $data['category_name'],
					"user_id"=> $userId,
					"created_at"=> date("Y-m-d H:i:s")
					));
					$data['category_id'] = $this->db->lastInsertId();
					$exits = "new";
				}
				
			}
			respond(array(
			"status" => "success",
			"message" => "Category add successfully",
			"category_name" => $data['category_name'],
			"category_id" => $data['category_id'],
			"update_type" => $exits
			));
		}
		
		public function AddNewUnitSubmit($data,$userId){
			$exits = "update";
			if (isset($data['unit_id'])){
				$this->db->update("pos_unit" ,  array(
				"unit_name"=> $data['unit_name'],
				"updated_at"=> date("Y-m-d H:i:s")
				),
				"`unit_id` = :id ",
				array("id"=> $data['unit_id']));
				}else{
				$checkUnit = $this->db->select("SELECT * FROM `pos_unit` WHERE `is_delete`= 'false' AND `unit_name`= :id ", array( 'id' => $data['unit_name']));
				if($checkUnit){
					$this->db->update("pos_unit" ,  array(
					"unit_name"=> $data['unit_name'],
					"updated_at"=> date("Y-m-d H:i:s")
					),
					"`unit_id` = :id ",
					array("id"=> $checkUnit[0]['unit_id']));
					$data['unit_id'] = $checkUnit[0]['unit_id'];
					}else{
					$this->db->insert("pos_unit",  array(
					"unit_name"=> $data['unit_name'],
					"user_id"=> $userId,
					"created_at"=> date("Y-m-d H:i:s")
					));
					$data['unit_id'] = $this->db->lastInsertId();
					$exits = "new";
				}
				
			}
			respond(array(
			"status" => "success",
			"message" => "Unit add successfully",
			"unit_name" => $data['unit_name'],
			"unit_id" => $data['unit_id'],
			"update_type" => $exits
			));
		}
		
		public function InsertAndUpdateProduct($data,$userId,$storeid){
			$product_alert = null;
			$product_stock_disable = 'disable';
			$variation_category_id = null;
			
			if(isset($data['enable_stock'])){
				$product_alert = $data['product_alert'];
			}
			
			if(isset($data['product_stock_disable'])){
				$product_stock_disable = 'enable';
			}
			
			if($data['product_variation_type'] == "variable"){
				$variation_category_id = $data['select_variation'];
			}
			
			if(isset($data['new_product'])){
				if($this->db->select("SELECT `sub_product_id` FROM `pos_variations` WHERE `is_delete`= 'false' AND `sub_product_id`= :id ", array( 'id' => $data['product_id']))){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'product_id' => trans('product_id_already_exits')
					)
					), 422);
				}
			}
			
			//--------- Validation Section END ----------//
			
			$pos_product = array(
			"product_id" => $data['product_id'],
			"product_name" => $data['product_name'],
			"unit_id" => $data['product_unit'],
			"category_id" => $data['product_category'],
			"brand_id" => $data['product_brand'],
			"product_vat" => $data['product_vat'],
			"product_vat_type" => $data['product_vat_type'],
			"product_type" => $data['product_variation_type'],
			"variation_category_id" => $variation_category_id,
			"product_stock" => $product_stock_disable,
			"alert_quantity" => $product_alert, 
			"user_id" => $userId,
			"created_at"=> date("Y-m-d H:i:s")
			);
			
			if(app('admin')->checkAddon('serial_product')){
				$product_serial_status = 'disable';
				
				if(isset($data['product_serial_num'])){
					$product_serial_status = 'enable';
				}
				
				$pos_product['product_serial'] = $product_serial_status;
				
			}
			
			if($this->db->select("SELECT `product_id` FROM `pos_product` WHERE `is_delete`= 'false' AND `product_id`= :id ", array( 'id' => $data['product_id']))){
				$this->db->update("pos_product" , $pos_product, "`product_id` = :id ", array("id"=> $data['product_id']));
				}else{
				$this->db->insert("pos_product", $pos_product);
			}
			
			
			
			
			if($data['product_variation_type'] == "single"){
				
				$product_purchase_price = $data['single_product_purchase_price'];
				$product_profit_margine = $data['single_product_profit_margine'];
				$product_sales_price = $data['single_product_sales_price'];
				
				$posSingleVariationData = array(
				"sub_product_id" => $data['product_id'],
				"product_id" => $data['product_id'],
				"purchase_price" => $product_purchase_price,
				"profit_percent" => $product_profit_margine,
				"sell_price" => $product_sales_price,
				"store_id" => $storeid,
				"created_at"=> date("Y-m-d H:i:s")
				);
				
				if($this->db->select("SELECT `product_id` FROM `pos_variations` WHERE `is_delete`= 'false' AND `product_id`= :id ", array( 'id' => $data['product_id']))){
					$this->db->update("pos_variations" , $posSingleVariationData, "`sub_product_id`= :sid ", array('sid' => $data['product_id']));
					}else{
					$this->db->insert("pos_variations", $posSingleVariationData);
				}
				
				}elseif($data['product_variation_type'] == "variable"){
				$product_variation_value = count($data['product_variation_value']);
				
				for ($x = 0; $x < count($data['product_variation_value']); $x++) {
					
					$sub_product_id = $data['sub_product_id'][$x];
					$product_variation_value = $data['product_variation_value'][$x];
					$product_purchase_price = $data['product_purchase_price'][$x];
					$product_profit_margine = $data['product_profit_margine'][$x];
					$product_sales_price = $data['product_sales_price'][$x];
					
					$posMultiVariationData =  array(
					"variation_name" => $product_variation_value,
					"sub_product_id" => $sub_product_id,
					"product_id" => $data['product_id'],
					"variation_category_id" => $variation_category_id,
					"purchase_price" => $product_purchase_price,
					"profit_percent" => $product_profit_margine,
					"sell_price" => $product_sales_price,
					"store_id" => $storeid,
					"created_at"=> date("Y-m-d H:i:s")
					);
					
					if($this->db->select("SELECT `product_id`,`sub_product_id` FROM `pos_variations` WHERE `is_delete`= 'false' AND `product_id`= :id AND `sub_product_id`= :sid ", array( 'id' => $data['product_id'], 'sid' => $sub_product_id))){
						$this->db->update("pos_variations" , $posMultiVariationData, "`product_id`= :id AND `sub_product_id`= :sid ", array( 'id' => $data['product_id'], 'sid' => $sub_product_id));
						}else{
						$this->db->insert("pos_variations", $posMultiVariationData );
					}
				} 
			}
			
			if(isset($data['product_image_name'])){
				$this->db->update("pos_product" ,  array( 
				"product_image" => $data['product_image_name']
				), "`product_id` = :id ", array("id"=> $data['product_id']));
			}
			
			respond(array(
			"status" => "success",
			"message" => trans('product_saved')
			));
		}
		
		public function createDatesArray($days) {
			$output = array();
			$month = date("m");
			$day = date("d");
			$year = date("Y");
			for($i=0; $i<=$days; $i++){
				$output[] = strtotime(date('Y-m-d',mktime(0,0,0,$month,($day-$i),$year)));
			}
			return $output;
		}
		
		public function GetAllSaleByDays($day){
			$days = array();
			$sales_amounts = array();
			$sales_discount = array();
			$sales_vat = array();
			$total_sales_amounts = 0;
			$total_sales_discount = 0;
			$total_sales_vat = 0;
			if($day < 32){
				$dates = $this->createDatesArray($day);
				foreach($dates as $date) {
					$sales = $this->db->table('pos_sales')->sum('sales_total')->sum('sales_vat')->sum('sales_discount')->between('created_at',date("y-m-d",$date),date("y-m-d",$date),true)->where('sales_status','!=',"cancel")->where('is_delete',"false")->get(1);
					if($sales['sales_total']){
						$sales_amounts[] = $sales['sales_total'];
						$total_sales_amounts += $sales['sales_total'];
						}else{
						$sales_amounts[] = 0;
					}
					if($sales['sales_discount']){
						$sales_discount[] = $sales['sales_discount'];
						$total_sales_discount += $sales['sales_discount'];
						}else{
						$sales_discount[] = 0;
					}
					if($sales['sales_vat']){
						$sales_vat[] = $sales['sales_vat'];
						$total_sales_vat += $sales['sales_vat'];
						}else{
						$sales_vat[] = 0;
					}
					$days[] = date("M d",$date);
				}
				}else{	
				for($i=01; $i<=12; $i++){
					$startdate = "Y-".sprintf("%02d", $i)."-01 00:00:00";
					$enddate = "Y-".sprintf("%02d", $i)."-t 00:00:00";
					$sales = $this->db->table('pos_sales')->sum('sales_total')->sum('sales_vat')->sum('sales_discount')->between('created_at',date($startdate),date($enddate),true)->where('sales_status','!=',"cancel")->where('is_delete',"false")->get(1);
					
					if($sales['sales_total']){
						$sales_amounts[] = $sales['sales_total'];
						$total_sales_amounts += $sales['sales_total'];
						}else{
						$sales_amounts[] = 0;
					}
					if($sales['sales_discount']){
						$sales_discount[] = $sales['sales_discount'];
						$total_sales_discount += $sales['sales_discount'];
						}else{
						$sales_discount[] = 0;
					}
					if($sales['sales_vat']){
						$sales_vat[] = $sales['sales_vat'];
						$total_sales_vat += $sales['sales_vat'];
						}else{
						$sales_vat[] = 0;
					}
					$date = new DateTime(date($startdate));
					$days[] = $date->format('M');
				}
			}
			respond(array(
			"status" => "success",
			"sales_amounts" => $sales_amounts,
			"sales_discount" => $sales_discount,
			"sales_vat" => $sales_vat,
			"total_sales_amounts" => $total_sales_amounts,
			"total_sales_discount" => $total_sales_discount,
			"total_sales_vat" => $total_sales_vat,
			"days" => $days
			));
		}
		
		public function GetPurchaseByCustomerOrder($contactId = false,$orderId = false) {
			
			$GetPurchase = $this->db->table('pos_purchase')->sum('purchase_total')->sum('purchase_discount')->sum('purchase_shipping_charge')->sum('purchase_subtotal')->where('purchase_status','!=',"cancel")->where('is_delete',"false");
			
			if($contactId) $GetPurchase = $GetPurchase->where('supplier_id',$contactId);
			if($orderId) $GetPurchase = $GetPurchase->where('purchase_id',$orderId);
			
			$GetPurchase = $GetPurchase->get(1);
			
			$GetPurchasePaid = $this->db->table('pos_transactions')->sum('transaction_amount')->where('transaction_type',"purchase")->where('transaction_status',"paid")->where('is_delete',"false");
			
			if($contactId) $GetPurchasePaid = $GetPurchasePaid->where('payment_for',$contactId);
			if($orderId) $GetPurchasePaid = $GetPurchasePaid->where('purchase_id',$orderId);
			
			$GetPurchasePaid = $GetPurchasePaid->get(1);
			
			$GetPurchaseReturn = $this->db->table('pos_return')->sum('return_total')->sum('return_vat')->sum('return_subtotal')->where('return_type',"purchase")->where('return_status','!=',"cancel")->where('is_delete',"false");
			
			if($contactId) $GetPurchaseReturn = $GetPurchaseReturn->where('supplier_id',$contactId);
			if($orderId) $GetPurchaseReturn = $GetPurchaseReturn->where('purchase_id',$orderId);
			
			$GetPurchaseReturn = $GetPurchaseReturn->get(1);
			
			$GetPurchaseReturnPaid = $this->db->table('pos_transactions')->sum('transaction_amount')->where('transaction_type',"return")->where('transaction_status',"paid")->where('is_return',"false")->where('is_delete',"false");
			
			if($contactId) $GetPurchaseReturnPaid = $GetPurchaseReturnPaid->where('payment_for',$contactId);
			if($orderId) $GetPurchaseReturnPaid = $GetPurchaseReturnPaid->where('purchase_id',$orderId);
			
			$GetPurchaseReturnPaid = $GetPurchaseReturnPaid->get(1);
			
			$CurrentTotal = $GetPurchase["purchase_total"] - $GetPurchaseReturn["return_total"];
			$CurrentTotalPaid = $GetPurchasePaid["transaction_amount"] - $GetPurchaseReturnPaid["transaction_amount"];
			$CurrentDue = $CurrentTotal - $CurrentTotalPaid ;
			$ReturnDue = $GetPurchaseReturn["return_total"] - $GetPurchaseReturnPaid["transaction_amount"];
			$NetPurchaseAmount = $GetPurchase["purchase_total"] - $GetPurchaseReturn["return_total"];
			return array(
			"total_purchase" => $GetPurchase["purchase_total"],
			"total_purchase_with_return" => $CurrentTotal,
			"total_paid" => $GetPurchasePaid["transaction_amount"],
			"total_paid_with_return" => $CurrentTotalPaid,
			"total_return" => $GetPurchaseReturn["return_total"],
			"total_return_paid" => $GetPurchaseReturnPaid["transaction_amount"],
			"total_return_due" => $ReturnDue,
			"net_purchase" => $NetPurchaseAmount,
			"total_due" => $CurrentDue,
			"purchase" => $GetPurchase,
			"return" => $GetPurchaseReturn
			);
		}
		
		public function GetSalesByCustomerOrder($contactId = false,$orderId = false) {
			if($contactId){
				$GetSales = $this->db->table('pos_sales')->sum('sales_total')->sum('sales_vat')->sum('sales_subtotal')->sum('sales_discount')->sum('shipping_charge')->where('sales_status','!=',"cancel")->where('customer_id',$contactId)->where('is_delete',"false")->get(1);
				$GetSalesPaid = $this->db->table('pos_transactions')->sum('transaction_amount')->where('transaction_type',"sales")->where('transaction_status',"paid")->where('payment_for',$contactId)->where('is_delete',"false")->get(1);
				$GetSalesReturn = $this->db->table('pos_return')->sum('return_total')->sum('return_vat')->sum('return_subtotal')->where('return_type',"sales")->where('return_status','!=',"cancel")->where('customer_id',$contactId)->where('is_delete',"false")->get(1);
				$GetSalesReturnPaid = $this->db->table('pos_transactions')->sum('transaction_amount')->where('transaction_type',"return")->where('transaction_status',"paid")->where('is_return',"true")->where('payment_for',$contactId)->where('is_delete',"false")->get(1);
				}elseif($orderId){
				$GetSales = $this->db->table('pos_sales')->sum('sales_total')->sum('sales_vat')->sum('sales_subtotal')->sum('sales_discount')->sum('shipping_charge')->where('sales_status','!=',"cancel")->where('sales_id',$orderId)->where('is_delete',"false")->get(1);
				$GetSalesPaid = $this->db->table('pos_transactions')->sum('transaction_amount')->where('transaction_type',"sales")->where('transaction_status',"paid")->where('sales_id',$orderId)->where('is_delete',"false")->get(1);
				$GetSalesReturn = $this->db->table('pos_return')->sum('return_total')->sum('return_vat')->sum('return_subtotal')->where('return_type',"sales")->where('return_status','!=',"cancel")->where('sales_id',$orderId)->where('is_delete',"false")->get(1);
				$GetSalesReturnPaid = $this->db->table('pos_transactions')->sum('transaction_amount')->where('transaction_type',"return")->where('transaction_status',"paid")->where('is_return',"true")->where('sales_id',$orderId)->where('is_delete',"false")->get(1);
				}else{
				$GetSales = $this->db->table('pos_sales')->sum('sales_total')->sum('sales_vat')->sum('sales_subtotal')->sum('sales_discount')->sum('shipping_charge')->where('sales_status','!=',"cancel")->where('is_delete',"false")->get(1);
				$GetSalesPaid = $this->db->table('pos_transactions')->sum('transaction_amount')->where('transaction_type',"sales")->where('transaction_status',"paid")->where('is_delete',"false")->get(1);
				$GetSalesReturn = $this->db->table('pos_return')->sum('return_total')->sum('return_vat')->sum('return_subtotal')->where('return_type',"sales")->where('return_status','!=',"cancel")->where('is_delete',"false")->get(1);
				$GetSalesReturnPaid = $this->db->table('pos_transactions')->sum('transaction_amount')->where('transaction_type',"return")->where('transaction_status',"paid")->where('is_return',"true")->where('is_delete',"false")->get(1);
			}
			
			$CurrentTotal = $GetSales["sales_total"] - $GetSalesReturn["return_total"];
			$CurrentTotalPaid = $GetSalesPaid["transaction_amount"] - $GetSalesReturnPaid["transaction_amount"];
			$CurrentDue = $CurrentTotal - $CurrentTotalPaid ;
			$ReturnDue = $GetSalesReturn["return_total"] - $GetSalesReturnPaid["transaction_amount"];
			
			return array(
			"total_sales" => round($GetSales["sales_total"],2),
			"total_sales_with_return" => $CurrentTotal,
			"total_paid" => $GetSalesPaid["transaction_amount"],
			"total_paid_with_return" => $CurrentTotalPaid,
			"total_return" => $GetSalesReturn["return_total"],
			"total_return_paid" => $GetSalesReturnPaid["transaction_amount"],
			"total_return_due" => $ReturnDue,
			"total_due" => $CurrentDue,
			"sales" => $GetSales,
			"return" => $GetSalesReturn
			);
		}
		
		public function contact_submit($data,$userId,$storeid){
			$exits = "update";
			$contact_data = array(
			"name"=> $data['name'],
			"business_name"=> $data['business_name'],
			"website_name"=> $data['website_name'],
			"phone"=> $data['phone'],
			"email"=> $data['email'],
			"address"=> $data['address'],
			"user_id"=> $userId,
			"store_id"=> $storeid,
			"updated_at"=> date("Y-m-d H:i:s"),
			);
			
			if (!empty($data['contact_id'])) {
				$this->db->update("pos_contact" , $contact_data,
				"`contact_id` = :id ",
				array("id"=> $data['contact_id'])
				);
				}else{
				
				$checkContact = app('admin')->getwhereid("pos_contact","phone",$data['phone']);
				if($checkContact){
					
					$this->db->update("pos_contact", $contact_data,
					"`contact_id` = :id ",
					array("id"=> $checkContact['contact_id'])
					);
					$data['contact_id'] = $checkContact['contact_id'];
				}
				else{
					
					if($data['contact_type']=='customer'){
						$data['contact_id'] = 'CUS'.gettoken(8);     
					}
					else{
						$data['contact_id'] = 'SUP'.gettoken(8);
					}
					
					$contact_data['contact_id'] =  $data['contact_id'];
					$contact_data['contact_type'] = $data['contact_type'];
					$this->db->insert("pos_contact", $contact_data);
					$exits = "new";
				}
			}
			
			respond(array(
			"status"       => "success",
			"contact_id"   => $data['contact_id'],
			"name" 		   => $data['name'],
			"update_type"  => ucfirst($exits),
			"contact_type" => $data['contact_type'],
			));
			
		}
		public function GetAccountUserData($data,$userId,$storeid){
			$contact_data = array(
			"name"=> $data['name'],
			"business_name"=> $data['business_name'],
			"website_name"=> $data['website_name'],
			"phone"=> $data['phone'],
			"email"=> $data['email'],
			"address"=> $data['address'],
			"user_id"=> $userId,
			"store_id"=> $storeid,
			"updated_at"=> date("Y-m-d H:i:s"),
			);
			if (!empty($data['contact_id'])) {
			    
			    $contact_data['contact_type'] = 'account';
			   
				$this->db->update("pos_contact" , $contact_data,
				"`contact_id` = :id ",
				array("id"=> $data['contact_id'])
				);
				
			}else{
			    $checkContact = app('admin')->getwhereid("pos_contact","phone",$data['phone']);
			    if($checkContact){
					
					$this->db->update("pos_contact", $contact_data,
					"`contact_id` = :id ",
					array("id"=> $checkContact['contact_id'])
					);
					$data['contact_id'] = $checkContact['contact_id'];
				}
		        else{
		            	$data['contact_id'] = 'AU'.gettoken(8);     
            			$contact_data['contact_id'] =  $data['contact_id'];
            			$contact_data['contact_type'] = 'account';
            			$this->db->insert("pos_contact", $contact_data);
		        }
			}
			respond(array(
			"status"       => "success",
			"contact_id"   => $data['contact_id'],
			"name" 		   => $data['name'].' - '.$data['phone'],
			));
			
		}
		
		public function contact_delete($data){
			//Contact
			$this->db->update("pos_contact" ,  array(
			'is_delete'=> 'true',
			'updated_at'=> date("Y-m-d H:i:s")
			),
			"`contact_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			//Serial
			$this->db->update("pos_product_serial" ,  array(
			'is_delete'=> 'true',
			),
			"`customer_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			$this->db->update("pos_product_serial" ,  array(
			'is_delete'=> 'true',
			),
			"`supplier_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			//Stock
			$this->db->update("pos_stock" ,  array(
			'is_delete'=> 'true',
			),
			"`customer_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			$this->db->update("pos_stock" ,  array(
			'is_delete'=> 'true',
			),
			"`supplier_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			//Return
			$this->db->update("pos_return" ,  array(
			'is_delete'=> 'true',
			),
			"`customer_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			$this->db->update("pos_return" ,  array(
			'is_delete'=> 'true',
			),
			"`supplier_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			//sale
			$this->db->update("pos_sales" ,  array(
			'is_delete'=> 'true',
			'update_at'=> date("Y-m-d H:i:s")
			),
			"`customer_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			//Purchase
			$this->db->update("pos_purchase" ,  array(
			'is_delete'=> 'true',
			'update_at'=> date("Y-m-d H:i:s")
			),
			"`supplier_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			//Transaction
			$this->db->update("pos_transactions" ,  array(
			'is_delete'=> 'true',
			'update_at'=> date("Y-m-d H:i:s")
			),
			"`payment_for` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			respond(array(
			"status" => "success"
			));
		}
			public function DeleteAccountUser($data){
			//Contact
			$this->db->update("pos_contact" ,  array(
			'is_delete'=> 'true',
			'updated_at'=> date("Y-m-d H:i:s")
			),
			"`contact_id` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			//Contact
			$this->db->update("accounts_transactions" ,  array(
			'is_delete'=> 'true',
			'updated_at'=> date("Y-m-d H:i:s")
			),
			"`payer_name` = :id ",
			array("id"=> $data['contact_id'])
			);
			
			respond(array(
			"status" => "success"
			));
		}
		
		public function GetPosSetting()
		{ 
			$result = $this->db->select("SELECT * FROM `pos_setting` LIMIT 1");
			return count($result) > 0 ? $result[0] : null;
		}
		
		public function GetLastOrderReceipt($userId = null)
		{ 
			if($userId == null){
				$result = $this->db->select("SELECT * FROM `pos_sales` ORDER BY `id` DESC LIMIT 1 ");
				}else{
				$result = $this->db->select("SELECT * FROM `pos_sales` WHERE `user_id` = :id ORDER BY `id` DESC LIMIT 1 ", array( 'id' => $userId));
			}
			return count($result) > 0 ? $result[0] : null;
		}
		
		public function checkPermission($permissionSection,$permissionType){
			$result = $this->db->select("SELECT * FROM `pos_user_permission` WHERE `permission_section` = :section", array('section' => $permissionSection));
			
			if(count($result) > 0){
				if(in_array(ASSession::get('user_id'),explode(',',$result[0]['permission_'.$permissionType]))){
					return true;
					}else{
					return false;
				}
				}else{
				return false;
			}
		}
		
		public function DeleteSale($data)
		{	
			$status  = "success";
			
			$sales_payment_status = app('db')->table('pos_sales')->where('sales_id',$data['sales_id'])->get('sales_payment_status');
			$return_payment_status = app('db')->table('pos_return')->where('sales_id',$data['sales_id'])->get('return_payment_status');
			
			if($sales_payment_status && $sales_payment_status != 'paid'){
				$message = trans('you_have_sales_due');;
				$status = 'warning';
				}elseif($return_payment_status && $return_payment_status != 'paid'){
				$message = trans('you_have_sales_return_due');
				$status = 'warning';
				}else{
				$this->db->update("pos_sales" ,  array(
				'is_delete'=> 'true',
				'update_at'=> date("Y-m-d H:i:s")
				),
				"`sales_id` = :id ",
				array("id"=> $data['sales_id'])
				);
				
				$this->db->update("pos_product_serial" ,  array(
				'is_delete'=> 'true'
				),
				"`sales_id` = :id AND `product_serial_category` = :s_cat AND `product_serial_stock_type` = :st_type",
				array("id"=> $data['sales_id'],"s_cat" => "sell","st_type" => "out")
				);
				
				$this->db->update("pos_return" ,  array(
				'is_delete'=> 'true',
				'update_at'=> date("Y-m-d H:i:s")
				),
				"`sales_id` = :id AND `return_type` = :type",
				array("id"=> $data['sales_id'], "type" => "sales")
				);
				
				$this->db->update("pos_stock" ,  array(
				'is_delete'=> 'true',
				'update_at'=> date("Y-m-d H:i:s")
				),
				"`sales_id` = :id",
				array("id"=> $data['sales_id'])
				);
				
				$this->db->update("pos_transactions" ,  array(
				'is_delete'=> 'true',
				'update_at'=> date("Y-m-d H:i:s")
				),
				"`sales_id` = :id",
				array("id"=> $data['sales_id'])
				);
				
				$message = trans('purchase_deleted');
			}
			
			respond(array(
			"status" => $status,
			"message" 	=> $message
			));	
		}
		
		public function PosSettingSubmit($data){
			$possetting = $this->db->select("SELECT * FROM `pos_setting`");
			
			$posSettingData = array(
			'company_name'		=> $data['company_name'],
			'currency'			=> $data['currency'],
			'address'			=> $data['address'],
			'email'				=> $data['email'],
			'phone'				=> $data['phone'],
			'nbr_no'			=> $data['nbr_no'],
			'nbr_unit'			=> $data['nbr_unit'],
			'receipt_footer'	=> $data['receipt'],
			'vat'				=> $data['vat'],
			'vat_type'			=> $data['vat_type'],
			);
			
			if(count($possetting) > 0){
				$this->db->update("pos_setting" ,$posSettingData,	"`id` = :id ", array("id"      => 1));
				}else{
				$query = $this->db->insert("pos_setting",$posSettingData);
			}
			
			respond(array(
			"status" => "success"
			));
		}
		
		public function DeletePurchase($data)
		{
			$status="success";
			
			$purchase_payment_status = app('db')->table('pos_purchase')->where('purchase_id',$data['purchase_id'])->get('purchase_payment_status');
			$return_payment_status = app('db')->table('pos_return')->where('purchase_id',$data['purchase_id'])->get('return_payment_status');
			
			
			if($purchase_payment_status && $purchase_payment_status != 'paid'){
				$message = trans('you_have_purchase_due');
				$status = "error";
				}elseif($return_payment_status && $return_payment_status != 'paid'){
				$message = trans('you_have_purchase_return_due');
				$status = "error";
				}else{
				
				$this->db->update("pos_purchase" ,  array(
				'is_delete'=> 'true',
				'update_at'=> date("Y-m-d H:i:s")
				),
				"`purchase_id` = :id ",
				array("id"=> $data['purchase_id'])
				);
				
				$this->db->update("pos_product_serial" ,  array(
				'is_delete'=> 'true'
				),
				"`purchase_id` = :id AND `product_serial_category` = :s_cat AND `product_serial_stock_type` = :st_type",
				array("id"=> $data['purchase_id'],"s_cat" => "purchase","st_type" => "in")
				);
				
				$this->db->update("pos_return" ,  array(
				'is_delete'=> 'true',
				'update_at'=> date("Y-m-d H:i:s")
				),
				"`purchase_id` = :id AND `return_type` = :type",
				array("id"=> $data['purchase_id'], "type" => "purchase")
				);
				
				$this->db->update("pos_stock" ,  array(
				'is_delete'=> 'true',
				'update_at'=> date("Y-m-d H:i:s")
				),
				"`purchase_id` = :id",
				array("id"=> $data['purchase_id'])
				);
				
				$this->db->update("pos_transactions" ,  array(
				'is_delete'=> 'true',
				'update_at'=> date("Y-m-d H:i:s")
				),
				"`purchase_id` = :id",
				array("id"=> $data['purchase_id'])
				);
				
				$message = trans('purchase_deleted');
			}
			
			respond(array(
			"status" => $status,
			"message" 	=> $message
			));	
		}
		
		public function DeleteBrand($data){
			$sql = $this->db->delete("pos_brands", "brand_id = :el", array( "el" => $data['brand_id'] ));
			respond(array(
			"status" => "success"
			));
		}
		public function DeleteUnit($data){
			$sql = $this->db->delete("pos_unit", "unit_id = :el", array( "el" => $data['unit_id'] ));
			respond(array(
			"status" => "success"
			));
		}
		public function DeleteCategory($data){
			$sql = $this->db->delete("pos_category", "category_id = :el", array( "el" => $data['category_id'] ));
			respond(array(
			"status" => "success"
			));
		}
		public function VatPaidSubmit($data,$userId)
		{
			$vat_paid_data=array(
			"transaction_type" => 'vat',
			"transaction_flow_type" => 'credit',
			"payment_method_value" => $data['payment_method'],
			"transaction_amount" => $data['payment_amount'],
			"transaction_no" => $data['transaction_no'],
			"transaction_note" => $data['payment_note'],
			"transaction_status" => 'paid',
			"paid_date"=> date("Y-m-d H:i:s"),
			"created_at"=> date("Y-m-d H:i:s")
			);
			$this->db->insert("pos_transactions",$vat_paid_data);
			respond(array(
			"status" => "success",
			// "data"	=>	$data['payment_method']
			));
		}
		
		public function GetPosAdvanceData($data,$userId,$storeId) {
			
			$customerCheck = false;
			$dueCheck = true;
			if(empty($data['customer_code'])){
				
				if($data['due_amount'] == 0){
					$data['customer_code'] = "CUS00000000";
				}
				else{
					if(app('admin')->checkAddon('due_sale')){
						respond(array(
						'status' => 'error',
						'errors' => array(
						'customer_code' => trans('need_customer_id_for_due_sales'),
						'due_amount' => trans('due_sales_required_customar_id'),
						)), 422);
					}
				}
				}else{
				if(!$this->db->select("SELECT `contact_id` FROM `pos_contact` WHERE `contact_id`= :id AND `is_delete`= 'false'", array( 'id' => $data['customer_code']))){
					$customerCheck = true;
				}
				else{
					if(app('admin')->checkAddon('due_sale')){
						$dueCheck = false;
					}
				}
			}
			
			if($data['sales_receive_amount'] > 0){
				if($data['sales_receive_amount'] >= $data['sales_total']){
					$dueCheck = false;
				}
			}
			
			if(!isset($data['sales_note'])){
				$data['sales_note'] = null;
			}
			
			if($data['due_amount'] == 0){
				$sales_payment_status = "paid";
				}else{
				$sales_payment_status = "due";
			}
			
			$sales_status = "complete";
			
			$stock_status = "active";
			
			
			
			$AddProductSalesQuantity = false;
			if(!isset($data['sub_product_id'])){
				$AddProductSalesQuantity = true;
				}else{
				$totalProduct = 0;
				for ($x = 0; $x < count($data['sub_product_id']); $x++) {
					if($data['product_quantity'][$x] == 0){
						respond(array(
						'status' => 'error',
						'errors' => array(
						'product_quantity_'.$data['sub_product_id'][$x] => ' '
						)
						), 422);
					}
					$totalProduct += $data['product_quantity'][$x];
					
				}
				if($totalProduct == 0) $AddProductSalesQuantity = true;
			}
			
			
			
			if($customerCheck){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'customer_code' => trans('worng_customer_id_input')
				)
				), 422);
			}
			
			if($AddProductSalesQuantity){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'sales_barcode' => trans('add_some_sales_product_first')
				)), 422);
			}
			
			if($dueCheck){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'due_amount' => trans('need_more_amount_for_sales_complete')
				)), 422);
			}
			
			
			
			$posSalesData = array(
			"store_id" => $storeId,
			"user_id" => $userId,
			"customer_id" => $data['customer_code'],
			"sales_type" => "pos",
			"sales_id" => $data['sales_id'],
			"sales_subtotal" => $data['sales_sub_total'],
			"sales_total" => $data['sales_total'],
			"sales_vat" => $data['sales_vat'],
			"sales_discount_value" => $data['salesdiscount_type_amount'],
			"sales_discount_type" => $data['sales_discount_type'],
			"sales_discount" => $data['sales_discount'],
			"sales_note" => $data['sales_note'],
			"sales_pay_cash" => $data['sales_receive_amount'],
			"sales_pay_change" => $data['sales_pay_change'],
			"sales_payment_status" => $sales_payment_status,
			"sales_status" => $sales_status,
			"created_at"=> date("Y-m-d H:i:s"),
			"update_at"=> date("Y-m-d H:i:s")
			);
			
			if($this->db->select("SELECT `sales_id`,`is_delete` FROM `pos_sales` WHERE `sales_id`= :id AND `is_delete`= 'false'", array( 'id' => $data['sales_id']))){
				$this->db->update("pos_sales" ,  $posSalesData , "`sales_id` = :id AND `is_delete`= 'false'", array("id"=> $data['sales_id']));
				}else{
				$this->db->insert("pos_sales", $posSalesData);
			}
			
			$totalPurchasePrice = 0;
			
			for ($x = 0; $x < count($data['sub_product_id']); $x++) {
				$sub_product_id = $data['sub_product_id'][$x];
				$product_id = $data['product_id'][$x];
				$product_stock_id = $data['product_stock_id'][$x];
				$product_vat = $data['product_vat'][$x];
				$product_vat_value = $data['product_vat_value'][$x];
				$product_vat_type = $data['product_vat_type'][$x];
				$total_product_vat = $data['total_product_vat'][$x];
				$product_price = $data['product_price'][$x];
				$product_quantity = $data['product_quantity'][$x];
				$product_subtotal = $data['product_subtotal'][$x];
				$sales_purchase_price = $data['sales_purchase_price'][$x];
				$PurchasePrice = $data['sales_purchase_price'][$x] * $data['product_quantity'][$x];
				$totalPurchasePrice += $PurchasePrice;
				
				$posSalesStockData = array(
				"store_id" => $storeId,
				"stock_id" => $product_stock_id,
				"product_id" => $product_id,
				"sales_id" => $data['sales_id'],
				"sub_product_id" => $sub_product_id,
				"customer_id" => $data['customer_code'],
				"product_quantity" => $product_quantity,
				"product_price" => $product_price,
				"sales_purchase_price" => $sales_purchase_price,
				"product_subtotal" => $product_subtotal,
				"product_vat" => $product_vat,
				"product_vat_value" => $product_vat_value,
				"product_vat_type" => $product_vat_type,
				"product_vat_total" => $total_product_vat,
				"stock_category" => 'sales',
				"stock_status" => $stock_status,
				"stock_type" => 'out',
				"stock_date"=> date("Y-m-d"),
				"user_id" => $userId,
				"created_at"=> date("Y-m-d H:i:s"),
				"update_at"=> date("Y-m-d H:i:s")
				);
				
				if($this->db->select("SELECT `stock_id`,`is_delete` FROM `pos_stock` WHERE `stock_id`= :id AND `is_delete`= 'false'", array('id' => $product_stock_id))){
					$this->db->update("pos_stock" , $posSalesStockData, "`stock_id`= :id AND `is_delete`= 'false'", array('id' => $product_stock_id));
					}else{
					$this->db->insert("pos_stock", $posSalesStockData );
				}
				
				$loadPlugins = app('admin')->loadAddon('serial_product','addSalesSerialDataInput'); 
				if(app('admin')->checkAddon('serial_product')){ 
					if (isset($data['product_serial_id'][$product_id])) {
						
						for ($serials=0; $serials < count($data['product_serial_id'][$product_id]) ; $serials++) { 
							$this->db->update("pos_product_serial" ,  array( 
							"sales_id" => $data['sales_id'],
							"sell_stock_id" => $product_stock_id,
							"customer_id" => $data['customer_code'],
							"product_serial_status" => 'sell',
							"product_serial_stock_type" => 'out',
							"sold_at" => date("Y-m-d H:i:s")
							), "`product_serial_no`= :id AND `sub_product_id`= :pid", array('id' => $data['product_serial_id'][$product_id][$serials], 'pid' => $sub_product_id));
						}
					}
					
				}
			}
			
			if($totalPurchasePrice > 0){
				$this->db->update("pos_sales" , ["sales_purchase_total" => $totalPurchasePrice] , "`sales_id` = :id AND `is_delete`= 'false'", array("id"=> $data['sales_id']));
			}
			
			$CashPaymentCheck = true;
			
			if(app('admin')->checkAddon('multiple_store_warehouse')){
				// require dirname(__FILE__) .'/../'.$loadPlugins['location'];
				if(isset($data['transaction_id'])){ 
					for ($x = 0; $x < count($data['transaction_id']); $x++) {
						$posTransactionData['transaction_type'] = 'sales';
						$posTransactionData['transaction_amount'] = $data['transaction_amount'][$x];
						$posTransactionData['transaction_flow_type'] = 'credit';
						$posTransactionData['payment_method_value'] = $data['transaction_method'][$x];
						$posTransactionData['transaction_no'] = $data['transaction_no'][$x];
						$posTransactionData['transaction_note'] = $data['transaction_note'][$x];
						$posTransactionData['transaction_status'] = 'paid';
						$posTransactionData['payment_for'] = $data['customer_code'];
						if($posTransactionData['transaction_amount'] > 0){
							$transactionCheck = $this->db->table('pos_transactions')->where("transaction_id",$data['transaction_id'][$x])->where("transaction_type",'sales')->get(1);
							if($transactionCheck){
								$this->db->update("pos_transactions" ,  $posTransactionData , "`transaction_id` = :id AND `transaction_type` = :type ", array("id"=> $data['transaction_id'][$x], "type"=> "sales"));
								}else{
								$posTransactionData['user_id'] = $userId;
								$posTransactionData['transaction_id'] = $data['transaction_id'][$x];
								$posTransactionData['sales_id'] = $data['sales_id'];
								$posTransactionData['created_at'] = date("Y-m-d H:i:s");
								$this->db->insert("pos_transactions", $posTransactionData);
							}
							$CashPaymentCheck = false;
						}
					}
				}
			}
			
			$data['payment_method'] = 'cash';
			$data['transition_id'] = null;
			
			if($CashPaymentCheck){
				
				if($data['sales_need_to_pay'] < $data['sales_receive_amount']){
					$data['sales_receive_amount'] = $data['sales_need_to_pay'];
				}
				
				$posSalesTransectionData = array(
				"store_id" => $storeId,
				"user_id" => $userId,
				"sales_id" => $data['sales_id'],
				"transaction_type" => 'sales',
				"transaction_amount" => $data['sales_receive_amount'],
				"transaction_flow_type" => 'credit',
				"payment_method_value" => $data['payment_method'],
				"transaction_no" => $data['transition_id'],
				"transaction_note" => "Cash Payment",
				"transaction_status" => 'paid',
				"payment_for" => $data['customer_code'],
				"paid_date"=> date("Y-m-d H:i:s"),
				"created_at"=> date("Y-m-d H:i:s"),
				"update_at"=> date("Y-m-d H:i:s")
				);
				
				if(!$this->db->select("SELECT `sales_id` FROM `pos_transactions` WHERE `sales_id`= :id ", array( 'id' => $data['sales_id']))){
					$posSalesTransectionData['transaction_id'] = 'TXN'.gettoken(8);;
					$this->db->insert("pos_transactions", $posSalesTransectionData);
					}else{
					$this->db->update("pos_transactions" , $posSalesTransectionData, "`sales_id`= :id", array('id' => $data['sales_id']));
				}
				
			}
			
			respond(array(
			"status" => "success",
			"message" => trans('pos_sales_complete'),
			"submit_type" => $data['submit'],
			"sales_id" => $data['sales_id'],
			));
		}
		
		public function GetProductListFilter($data) {
			$SearchByCodeName = null;
			if($data['product_code']){
				$SearchByCodeName = " AND (product_name LIKE '%".$data['product_code']."%' OR product_id LIKE '%".$data['product_code']."%') ";
			}
			$SearchByBrandId = null;
			if($data['brand_id']){
				$SearchByBrandId = " AND `brand_id` = ".$data['brand_id']." ";
			}
			$SearchByCategoryId = null;
			if($data['category_id']){
				$categoryid = $data['category_id'];
				$SearchByCategoryId = " AND `category_id` = $categoryid ";
			}
			if($data['product_code'] == null && $data['brand_id'] == null && $data['category_id'] == null){
				$sql = $this->db->select("SELECT * FROM `pos_product` WHERE `product_featured` = true ");
				}else{
				$sql = $this->db->select("SELECT * FROM `pos_product` WHERE 1 ".$SearchByCodeName.$SearchByBrandId.$SearchByCategoryId);
			}
			
			$i = 0;
			if($sql){
				foreach ($sql as $sqls) {
					$getvariables = $this->db->select("SELECT `variation_name`,`product_id`,`sub_product_id` FROM `pos_variations` WHERE `product_id` = :id", array( 'id' => $sqls['product_id']));
					foreach($getvariables as $getvariable){
						$GetProductAvaliableStock = $this->GetProductAvaliableStock(null,$getvariable['sub_product_id']);
						
						$myObj[$i] = new stdClass;
						if($GetProductAvaliableStock['product_stock'] > 0){
							$myObj[$i]->product_image = "pos/image/".$sqls['product_image'].'?st='.$GetProductAvaliableStock['product_stock'];
							}else{
							$myObj[$i]->product_image = "pos/image/".$sqls['product_image'].'?wm='.$GetProductAvaliableStock['product_stock'];
						}
						if($sqls['product_type'] == "variable"){
							$myObj[$i]->name = $sqls['product_name'].' - '.$getvariable['variation_name'];
							$myObj[$i]->product_id = $getvariable['sub_product_id'];
							}else{
							$myObj[$i]->name = $sqls['product_name'];
							$myObj[$i]->product_id = $sqls['product_id'];
						}
						$i++;
					}
				}
				respond(array(
				"status" => "success",
				"product_status" => 'found',
				"source_data" => $myObj
				));
				}else{
				respond(array(
				"status" => "success",
				"product_status" => 'not_found'
				));
			}
		}
		
		public function GetProductAvaliableStock($store_id,$sub_product_id) {
			
			if($store_id == null){
				$GetProductQuantityIn = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_type" => "in", "is_delete" => "false", "stock_status" => "active" ));
				$GetProductQuantityOut = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_type" => "out", "is_delete" => "false", "stock_status" => "active" ));
				$CurrentStock = $GetProductQuantityIn["product_quantity"] - $GetProductQuantityOut["product_quantity"];
				$CurrentStockAmount = $GetProductQuantityIn["product_subtotal"] - $GetProductQuantityOut["product_subtotal"];
				$GetProducSales = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "sales", "is_delete" => "false", "stock_status" => "active" ));
				$GetProductPurchase = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "purchase", "is_delete" => "false", "stock_status" => "active" ));
				$GetProductDamage = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "damage", "is_delete" => "false", "stock_status" => "active" ));
				$GetProductSalesReturn = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "return", "is_delete" => "false", "stock_type" => "in", "stock_status" => "active" ));
				$GetProductPurchaseReturn = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "return", "is_delete" => "false", "stock_type" => "out", "stock_status" => "active" ));
				$GetProductTransfer = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "is_delete" => "false", "stock_category" => "transfer", "stock_status" => "active" ));
				}else{
				$GetProductQuantityIn = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_type" => "in", "is_delete" => "false", "store_id" => $store_id, "stock_status" => "active" ));
				$GetProductQuantityOut = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_type" => "out", "is_delete" => "false", "store_id" => $store_id, "stock_status" => "active" ));
				$CurrentStock = $GetProductQuantityIn["product_quantity"] - $GetProductQuantityOut["product_quantity"];
				$CurrentStockAmount = $GetProductQuantityIn["product_subtotal"] - $GetProductQuantityOut["product_subtotal"];
				$GetProducSales = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "sales", "is_delete" => "false", "store_id" => $store_id, "stock_status" => "active" ));
				$GetProductPurchase = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "purchase", "is_delete" => "false", "store_id" => $store_id, "stock_status" => "active" ));
				$GetProductDamage = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "damage", "is_delete" => "false", "store_id" => $store_id, "stock_status" => "active" ));
				$GetProductSalesReturn = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "return", "is_delete" => "false", "stock_type" => "in", "store_id" => $store_id, "stock_status" => "active" ));
				$GetProductPurchaseReturn = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "return", "is_delete" => "false", "stock_type" => "out", "store_id" => $store_id, "stock_status" => "active" ));
				$GetProductTransfer = app('admin')->GetSum("pos_stock",array("product_quantity","product_subtotal"),array("sub_product_id" => $sub_product_id, "stock_category" => "transfer", "is_delete" => "false", "store_id" => $store_id, "stock_status" => "active" ));
			}
			return array(
			"product_in" => $GetProductQuantityIn["product_quantity"],
			"product_in_amount" => $GetProductQuantityIn["product_subtotal"],
			"product_out" => $GetProductQuantityOut["product_quantity"], 
			"product_out_amount" => $GetProductQuantityOut["product_subtotal"], 
			"product_sale" => $GetProducSales["product_quantity"], 
			"product_sale_amount" => $GetProducSales["product_subtotal"], 
			"product_purchase" => $GetProductPurchase["product_quantity"], 
			"product_purchase_amount" => $GetProductPurchase["product_subtotal"], 
			"product_damage" => $GetProductDamage["product_quantity"], 
			"product_damage_amount" => $GetProductDamage["product_subtotal"], 
			"product_sales_return" => $GetProductSalesReturn["product_quantity"], 
			"product_sales_return_amount" => $GetProductSalesReturn["product_subtotal"], 
			"product_purchase_return" => $GetProductPurchaseReturn["product_quantity"], 
			"product_purchase_return_amount" => $GetProductPurchaseReturn["product_subtotal"], 
			"product_transfer" => $GetProductTransfer["product_quantity"], 
			"product_transfer_amount" => $GetProductTransfer["product_subtotal"], 
			"product_stock_amount" => $CurrentStockAmount,
			"product_stock" => $CurrentStock
			);
		}
		
		public function AddVariationSubmit($data,$userId,$store_id)
		{
			if (isset($data['variation_id'])){
				$this->db->update("pos_variations_category" ,  array(
				"variation_category_name"=> $data['variation_name'],
				"variation_category_value"=>implode(',',$data['variation_value']),
				"update_at"=> date("Y-m-d H:i:s")
				),
				"`variation_category_id` = :id ",
				array("id"=> $data['variation_id'])
				);
				respond(array(
				"status" => "success"
				));
				}else{
				$this->db->insert("pos_variations_category",  array(
				"variation_category_name"=> $data['variation_name'],
				"variation_category_value"=> implode(',',$data['variation_value']),
				"user_id"=> $userId,
				"store_id"=> $store_id,
				"created_at"=> date("Y-m-d H:i:s")
				));
				respond(array(
				"status" => "success"
				));
			}
		}
		
		public function GetSearchCustomerByNameId($data) {
			$sql = $this->db->select("SELECT `contact_id`,`phone`,`name` FROM `pos_contact` WHERE (`contact_id` LIKE :contact_id OR `name` LIKE :name OR `phone` LIKE :phone )", array( 
			'contact_id' => "%".$data['customer_code']."%",
			'name' => "%".$data['customer_code']."%",
			'phone' => "%".$data['customer_code']."%",
			));
			$i = 0;
			foreach ($sql as $sqls) {
				$myObj[$i] = new stdClass;
				$myObj[$i]->name = $sqls['name'].' - '.$sqls['phone'];
				$myObj[$i]->customer_id = $sqls['contact_id'];
				$myObj[$i]->customer_name = $sqls['name'];
				$myObj[$i]->customer_phone = $sqls['phone'];
				$i++;
			}
			if(!empty($myObj)){
				respond(array(
				"status" => "success",
				"source_data" => $myObj
				));
				}else{
				respond(array(
				"status" => "success",
				"source_data" => 'false'
				));
			}
		}
		public function GetSearchAccountUserByNameId($data) {
			$sql = $this->db->select("SELECT `contact_id`,`phone`,`name` FROM `pos_contact` WHERE (`contact_id` LIKE :contact_id OR `name` LIKE :name OR `phone` LIKE :phone) AND (`contact_type` = :type )", array( 
			'contact_id' => "%".$data['customer_code']."%",
			'name' => "%".$data['customer_code']."%",
			'phone' => "%".$data['customer_code']."%",
			'type' => "account",
			));
			$i = 0;
			foreach ($sql as $sqls) {
				$myObj[$i] = new stdClass;
				$myObj[$i]->name = $sqls['name'].' - '.$sqls['phone'];
				$myObj[$i]->customer_id = $sqls['contact_id'];
				$myObj[$i]->customer_name = $sqls['name'];
				$myObj[$i]->customer_phone = $sqls['phone'];
				$i++;
			}
			if(!empty($myObj)){
				respond(array(
				"status" => "success",
				"source_data" => $myObj
				));
				}else{
				respond(array(
				"status" => "success",
				"source_data" => 'false'
				));
			}
		}
		public function DeleteVariation($data){
			$this->db->update("pos_variations_category" ,  array(
			"is_delete"=> "true",
			),
			"`variation_category_id` = :id ",
			array("id"=> $data['variation_id'])
			);
			respond(array(
			"status" => "success"
			));
		}
		public function deleteVariationProduct($data){
			$this->db->update("pos_variations" ,  array(
			"is_delete"=> 'true',
			),
			"`sub_product_id` = :id ",
			array("id"=> $data['sub_product_id'])
			);
			respond(array(
			"status" => "success"
			));
		}
		public function ProductFeaturedChange($data)
		{
			if($data['featured_value']=='true'){
				$data['featured_value']='false';
			}
			else{
				$data['featured_value']='true';
			}
			
			$this->db->update("pos_product" ,  array(
			"product_featured"=> $data['featured_value'],
			),
			"`product_id` = :id ",
			array("id"=> $data['product_id'])
			);
			respond(array(
			"status" => "success",
			"message" => trans('featured_change_successfully')
			));
		}
		
		public function ProductDelete($data){
			
			$this->db->update("pos_product" ,  array(
			"is_delete"=> "true",
			),
			"`product_id` = :id ",
			array("id"=> $data['product_id'])
			);
			
			$this->db->update("pos_variations" ,  array(
			"is_delete"=> 'true',
			),
			"`product_id` = :id ",
			array("id"=> $data['product_id'])
			);
			
			respond(array(
			"status" => "success",
			"message" => trans('product_successfully_deleted')
			));
		}
		
		public function AddNewStoreSumbit($data,$userId){
			if (isset($data['store_id'])){
				$this->db->update("pos_store" ,  array(
				"store_name"=> $data['store_name'],
				"store_location"=> $data['store_location'],
				"warehouse_id"=> $data['warehouse'],
				"updated_at"=> date("Y-m-d H:i:s")
				),
				"`store_id` = :id ",
				array("id"=> $data['store_id'])
				);
				respond(array(
				"status" => "success"
				));
				}else{
				
				if(count($this->db->table('pos_store')->get()) >=  app('admin')->getAddonValue('multiple_store_warehouse','manage_store')){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'store_name' => trans('your_package_allow_to').' '.app('admin')->getAddonValue('multiple_store_warehouse','manage_store').' '.trans('store')
					)
					), 422);
				}

				$this->db->insert("pos_store",  array(
				"store_name"=> $data['store_name'],
				"store_location"=> $data['store_location'],
				"warehouse_id"=> $data['warehouse'],
				"user_id"=> $userId,
				"created_at"=> date("Y-m-d H:i:s")
				));
				
				respond(array(
				"status" => "success"
				));
			}
		}
		public function ChangeStoreStatus($data){
			if (isset($data['store_id'])){
				if ($data['status']=='active'){
					$status='deactive';
					}elseif($data['status']=='deactive'){
				$status='active';
				}
				$this->db->update("pos_store" ,  array(
				"store_status"=> $status,
				"updated_at"=> date("Y-m-d H:i:s")
				),
				"`store_id` = :id ",
				array("id"=> $data['store_id'])
				);
				respond(array(
				"status" => "success"
				));
				}
			}
			
			public function AddNewWarehouseSumbit($data,$userId){
				if (isset($data['warehouse_id'])){
					$this->db->update("pos_warehouse" ,  array(
					"warehouse_name"=> $data['warehouse_name'],
					"warehouse_location"=> $data['warehouse_location'],
					"updated_at"=> date("Y-m-d H:i:s")
					),
					"`warehouse_id` = :id ",
					array("id"=> $data['warehouse_id'])
					);
					respond(array(
					"status" => "success"
					));
					}else{
					$this->db->insert("pos_warehouse",  array(
					"warehouse_name"=> $data['warehouse_name'],
					"warehouse_location"=> $data['warehouse_location'],
					"user_id"=> $userId,
					"created_at"=> date("Y-m-d H:i:s")
					));
					respond(array(
					"status" => "success"
					));
				}
			}
			public function ChangeWarehouseStatus($data){
				if (isset($data['warehouse_id'])){
					if ($data['status']=='active'){
						$status='deactive';
						}elseif($data['status']=='deactive'){
						$status='active';
					}
					$this->db->update("pos_warehouse" ,  array(
					"warehouse_status"=> $status,
					"updated_at"=> date("Y-m-d H:i:s")
					),
					"`warehouse_id` = :id ",
					array("id"=> $data['warehouse_id'])
					);
					respond(array(
					"status" => "success"
					));
				}
			}
			public function DeleteWarehouse($data){
				$sql = $this->db->delete("pos_warehouse", "warehouse_id = :el", array( "el" => $data['warehouse_id'] ));
				respond(array(
				"status" => "success"
				));
			}
			
			public function InvoiceSettingSubmit($data)
			{
				$flag=app('admin')->getall('pos_invoice');
				$header='';
				$footer='';
				$logo='';
				$top='';
				$bottom='';
				$note='';
				if(isset($data['invoice_top'])){
					$top=$data['invoice_top'];
				}
				if(isset($data['invoice_bottom'])){
					$bottom=$data['invoice_bottom'];
				}
				if(isset($data['invoice_header'])){
					$header=$data['invoice_header'];
				}
				if(isset($data['invoice_footer'])){
					$footer=$data['invoice_footer'];
				}
				if(isset($data['invoice_logo'])){
					$logo=$data['invoice_logo'];
				}
				if(isset($data['invoice_footer_note'])){
					$note=$data['invoice_footer_note'];
				}
				if(count($flag)!=null){
					
					$this->db->update("pos_invoice" ,  array( 
					"invoice_id" => $data['invoice_id'],
					"invoice_title" => $data['invoice_title'],
					"top" => $top,
					"bottom" => $bottom,
					"header" => $header,
					"footer" => $footer,
					"logo" => $logo,
					"footer_note" => $note,
					"status" => 'active',
					), "`id` = :id ", array("id"=> "1"));
				}
				else{
					$this->db->insert("pos_invoice",  array(
					"invoice_id" => $data['invoice_id'],
					"invoice_title" => $data['invoice_title'],
					"top" => $top,
					"bottom" => $bottom,
					"header" => $header,
					"footer" => $footer,
					"logo" => $logo,
					"footer_note" => $note,
					"status" => 'active',
					));
				}
				respond(array(
				"status" => "success",
				"message" => ''
				));
			}
			
			public function AccountChartSubmit($data,$userId,$storeId)
			{
				if(isset($data['account_id'])){
					$this->db->update("accounts_chart",array(
					"chart_no" => $data['chart_no'],
					"chart_name" => $data['chart_title'],
					"chart_name_value" => $data['chart_name_value'],
					"chart_category_name" => $data['chart_category'],
					"chart_sub_category_name" => $data['chart_sub_category_name'],
					"chart_type" => $data['chart_type'],
					"user_id" => $userId
					),"`account_id` = :id ", array("id"=> $data['account_id']));
					
				}
				else{
					$this->db->insert("accounts_chart",array(
					"chart_no" => $data['chart_no'],
					"chart_name" => $data['chart_title'],
					"chart_name_value" => $data['chart_name_value'],
					"chart_category_name" => $data['chart_category'],
					"chart_sub_category_name" => $data['chart_sub_category_name'],
					"chart_type" => $data['chart_type'],
					"user_id" => $userId
					));
				}
				
				respond(array(
				"status" => "success"
				));
			}
			
			public function AddAccountSubmit($data,$userId){
				
				if(empty($data['prayer'])){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'prayer' => trans('need_account_user_code'),
					)), 422);
					}else{
					if(!$this->db->select("SELECT `contact_id` FROM `pos_contact` WHERE `contact_id`= :id AND `is_delete`= 'false'", array( 'id' => $data['prayer']))){
						respond(array(
						'status' => 'error',
						'errors' => array(
						'prayer' => trans('need_account_user_not_found'),
						)), 422);
					}
				}
				
				
				if($data['account_type'] == 'account_expense'){
					if($data['account_status'] == 'due'){
						$data['debit_account_name'] = $data['chart_category'];
						$data['debit_account_status'] = 'paid';
						$data['credit_account_name'] = $data['chart_category'];
						$data['credit_account_status'] = 'due';
						}elseif($data['account_status'] == 'advance'){
						$data['debit_account_name'] = $data['chart_category'];
						$data['debit_account_status'] = 'advance';
						if($data['method'] == 'cash'){
							$data['credit_account_name'] = 'account_cash';
							}else{
							$data['credit_account_name'] = 'account_bank';
						}
						$data['credit_account_status'] = 'paid';
						}elseif($data['account_status'] == 'paid'){
						
						if(isset($data['due_purpsoe_payment'])){
							
							$data['debit_account_name'] = $data['chart_category'];
							$data['debit_account_status'] = 'due';
							if($data['method'] == 'cash'){
								$data['credit_account_name'] = 'account_cash';
								}else{
								$data['credit_account_name'] = 'account_bank';
							}
							$data['credit_account_status'] = 'paid';
							
							}elseif(isset($data['advanced_purpsoe_payment'])){
							
							$data['debit_account_name'] = $data['chart_category'];
							$data['debit_account_status'] = 'paid';
							$data['credit_account_name'] = $data['chart_category'];
							$data['credit_account_status'] = 'advance';
							
							}else{
							
							$data['debit_account_name'] = $data['chart_category'];
							$data['debit_account_status'] = 'paid';
							if($data['method'] == 'cash'){
								$data['credit_account_name'] = 'account_cash';
								}else{
								$data['credit_account_name'] = 'account_bank';
							}
							$data['credit_account_status'] = 'paid';
							
						}
					}
					
					$AccountTransaction = array(
					
					"payer_name"=> $data['prayer'],
					"dr_account"=> $data['debit_account_name'],
					"dr_account_status"=> $data['debit_account_status'],
					"cr_account"=> $data['credit_account_name'],
					"cr_account_status"=> $data['credit_account_status'],
					"account_status"=> $data['account_status'],
					"amount"=> $data['total_amount'],
					"payment_method"=> $data['method'],
					"account_type"=> $data['account_type'],
					"date"=> $data['date'],
					"note"=> $data['note'],
					"user_id"=> $userId
					);
					if(app('admin')->checkAddon('multiple_store_warehouse')){
						
						$AccountTransaction["store_id"] = $data['business_location'];
					}
					if(isset($data['due_purpsoe_payment'])){
						$AccountTransaction['is_due_purpose'] = 'true';
						}elseif(isset($data['advanced_purpsoe_payment'])){
						$AccountTransaction['is_advance_purpose'] = 'true';
					}
					}elseif($data['account_type'] == 'account_income'){
					
					if($data['account_status'] == 'due'){
						$data['credit_account_name'] = $data['chart_category'];
						$data['credit_account_status'] = 'paid';
						$data['debit_account_name'] = $data['chart_category'];
						$data['debit_account_status'] = 'due';
						}elseif($data['account_status'] == 'advance'){
						
						if($data['method'] == 'cash'){
							$data['debit_account_name'] = 'account_cash';
							}else{
							$data['debit_account_name'] = 'account_bank';
						}
						
						$data['debit_account_status'] = 'paid';
						
						$data['credit_account_name'] = $data['chart_category'];
						$data['credit_account_status'] = 'advance';
						
						}elseif($data['account_status'] == 'paid'){
						
						if(isset($data['due_purpsoe_payment'])){
							
							if($data['method'] == 'cash'){
								$data['debit_account_name'] = 'account_cash';
								}else{
								$data['debit_account_name'] = 'account_bank';
							}
							
							$data['debit_account_status'] = 'paid';
							
							$data['credit_account_name'] = $data['chart_category'];
							$data['credit_account_status'] = 'due';
							
							}elseif(isset($data['advanced_purpsoe_payment'])){
							
							$data['credit_account_name'] = $data['chart_category'];
							$data['credit_account_status'] = 'paid';
							
							$data['debit_account_name'] = $data['chart_category'];
							$data['debit_account_status'] = 'advance';
							
							}else{
							
							if($data['method'] == 'cash'){
								$data['debit_account_name'] = 'account_cash';
								}else{
								$data['debit_account_name'] = 'account_bank';
							}
							$data['debit_account_status'] = 'paid';
							
							$data['credit_account_name'] = $data['chart_category'];
							$data['credit_account_status'] = 'paid';
							
						}
					}
					
					$AccountTransaction = array(
					"payer_name"=> $data['prayer'],
					"dr_account"=> $data['debit_account_name'],
					"dr_account_status"=> $data['debit_account_status'],
					"cr_account"=> $data['credit_account_name'],
					"cr_account_status"=> $data['credit_account_status'],
					"account_status"=> $data['account_status'],
					"amount"=> $data['total_amount'],
					"payment_method"=> $data['method'],
					"account_type"=> $data['account_type'],
					"date"=> $data['date'],
					"note"=> $data['note'],
					"user_id"=> $userId
					);
					
					if(app('admin')->checkAddon('multiple_store_warehouse')){
						
						$AccountTransaction["store_id"]= $data['business_location'];
					}
					
					if(isset($data['due_purpsoe_payment'])){
						$AccountTransaction['is_due_purpose'] = 'true';
						}elseif(isset($data['advanced_purpsoe_payment'])){
						$AccountTransaction['is_advance_purpose'] = 'true';
					}
					}elseif($data['account_type'] == 'account_withdraw'){
					
					$data['debit_account_name'] = 'account_capital';
					$data['credit_account_status'] = 'paid';
					$data['debit_account_status'] = 'paid';
					
					if($data['method'] == 'cash'){
						$data['credit_account_name'] = 'account_cash';
						}else{
						$data['credit_account_name'] = 'account_bank';
					}
					
					$data['account_status'] = 'paid';
					
					$AccountTransaction = array(
					"payer_name"=> $data['prayer'],
					"dr_account"=> $data['debit_account_name'],
					"dr_account_status"=> $data['debit_account_status'],
					"cr_account"=> $data['credit_account_name'],
					"cr_account_status"=> $data['credit_account_status'],
					"account_status"=> $data['account_status'],
					"amount"=> $data['total_amount'],
					"payment_method"=> $data['method'],
					"account_type"=> $data['account_type'],
					"date"=> $data['date'],
					"note"=> $data['note'],
					"user_id"=> $userId
					);
					
					if(app('admin')->checkAddon('multiple_store_warehouse')){
						
						$AccountTransaction["store_id"] = $data['business_location'];
					}
				}
				
				$this->db->insert("accounts_transactions", $AccountTransaction);
				
				respond(array(
				"status" => "success"
				));
			}
			
			public function CapitalCashSubmit($data, $userId){
				
				if($data['select_bank'] == 'null'){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'select_bank' => trans('select_bank_field_required'),
					)), 422);
				}
				
				if(empty($data['amount'])){
					respond(array(
					'status' => 'error',
					'errors' => array(
					'amount' => trans('amount_field_required'),
					)), 422);
				}
				
				
				
				if(isset($data['select_bank'])){
					$data['credit_account_name'] = 'account_capital';
					$data['account_type'] = 'account_capital_bank';
					$data['debit_account_name'] = 'account_bank';
					}elseif(isset($data['select_assets'])){
					$data['select_bank'] = '';
					$data['credit_account_name'] = 'account_capital';
					$data['account_type'] = 'account_capital_assets';
					$data['debit_account_name'] = $data['select_assets'];
					}elseif(isset($data['loan_with'])){
					$data['select_bank'] = $data['payment_method'];
					$data['debit_account_name'] = $data['loan_with'];
					$data['credit_account_name'] = 'account_loan';
					$data['account_type'] = 'account_capital_loans';
					}elseif(isset($data['select_lease'])){
					$data['select_bank'] = 'null';
					$data['debit_account_name'] = $data['select_lease'];
					$data['credit_account_name'] = 'account_lease';
					$data['account_type'] = 'account_capital_lease';
					}else{
					$data['select_bank'] = 'cash';
					$data['credit_account_name'] = 'account_capital';
					$data['debit_account_name'] = 'account_cash';
					$data['account_type'] = 'account_capital_cash';
				}
				
				$data['credit_account_status'] = 'paid';
				$data['account_status'] = 'paid';
				$data['debit_account_status'] = 'paid';
				
				
				$AccountTransaction = array(
				"store_id"=> $data['store_id'],
				"payer_name"=> 'AU00000000',
				"dr_account"=> $data['debit_account_name'],
				"dr_account_status"=> $data['debit_account_status'],
				"cr_account"=> $data['credit_account_name'],
				"cr_account_status"=> $data['credit_account_status'],
				"account_status"=> $data['account_status'],
				"amount"=> $data['amount'],
				"payment_method"=> $data['select_bank'],
				"account_type"=> $data['account_type'],
				"date"=> $data['date'],
				"note"=> $data['note'],
				"user_id"=> $userId
				);
				
				if(isset($data['installment'])){
					$AccountTransaction['loan_installment'] = $data['installment'];
				}
				
				if(isset($data['leasting_time'])){
					$AccountTransaction['leasting_date'] = $data['leasting_time'];
				}
				
				$this->db->insert("accounts_transactions", $AccountTransaction);
				
				respond(array(
				"status" => "success"
				));
			}
			
			public function AddPurchaseReturn($data,$userId,$storeid){
				if(!isset($data['document'])){
					$data['document']=null;
				}
				
				if($this->db->select("SELECT `return_id` FROM `pos_return` WHERE `return_id`= :id AND `is_delete`= 'false' ", array( 'id' => $data['return_id']))){
					$this->db->update("pos_return" ,  array( 
					"user_id" => $userId,
					"return_subtotal" => $data['purchase_return_subtotal'],
					"return_discount" => $data['purchase_return_discount'],
					"return_total" => $data['purchase_return_total'],
					"update_at"=> date("Y-m-d H:i:s"),
					"return_note" => $data['return_note'],
					"return_status" => 'returned',
					"document" => $data['document']
					), "`return_id` = :id ", array("id"=> $data['return_id']));
					}else{
					$this->db->insert("pos_return",  array(
					"return_id" => $data['return_id'],
					"purchase_id" => $data['purchase_id'],
					"supplier_id" => $data['supplier_id'],
					"store_id" => $storeid,
					"user_id" => $userId,
					"return_subtotal" => $data['purchase_return_subtotal'],
					"return_discount" => $data['purchase_return_discount'],
					"return_total" => $data['purchase_return_total'],
					"return_note" => $data['return_note'],
					"document" => $data['document'],
					"created_at"=> date("Y-m-d H:i:s"),
					"return_type" => 'purchase',
					"return_status" => 'returned'
					));
				}
				
				for ($x = 0; $x < count($data['sub_product_id']); $x++) {
					$product_stock_id = 'ST'.gettoken(8);
					$product_id = $data['product_id'][$x];
					$sub_product_id = $data['sub_product_id'][$x];
					$already_returned = (float) $data['already_returned'][$x];
					$total_return_product_quantity = $data['return_product_quantity'][$x];
					$return_product_quantity = (float) $total_return_product_quantity + $already_returned;
					$product_price = $data['product_price'][$x];
					$return_product_sub_total = $data['return_product_sub_total'][$x];
					if($return_product_quantity > 0 ){
						if($this->db->select("SELECT `return_id`,sub_product_id FROM `pos_stock` WHERE `return_id`= :id AND `sub_product_id`= :spid AND `is_delete`= 'false' ", array( 'id' => $data['return_id'], 'spid' => $sub_product_id))){
							$this->db->update("pos_stock" ,  array( 
							"product_quantity" => $return_product_quantity,
							"product_price" => $product_price,
							"product_subtotal" => $return_product_sub_total,
							"stock_status" => 'active',
							"user_id" => $userId,
							"update_at"=> date("Y-m-d H:i:s")
							), "`return_id`= :id AND `sub_product_id`= :spid ", array('id' => $data['return_id'], 'spid' => $sub_product_id));
							}else{
							$this->db->insert("pos_stock",  array(
							"stock_id" => $product_stock_id,
							"product_id" => $product_id,
							"store_id" => $storeid,
							"sub_product_id" => $sub_product_id,
							"supplier_id" => $data['supplier_id'],
							"purchase_id" => $data['purchase_id'],
							"return_id" => $data['return_id'],
							"product_quantity" => $return_product_quantity,
							"product_price" => $product_price,
							"product_subtotal" => $return_product_sub_total,
							"stock_category" => 'return',
							"stock_type" => 'out',
							"stock_status" => 'active',
							"user_id" => $userId,
							"stock_date"=> date("Y-m-d"),
							"created_at"=> date("Y-m-d H:i:s")
							));
						}
						if(isset($data['serial_number'][$sub_product_id])){
							for ($serials=0; $serials < count($data['serial_number'][$sub_product_id]) ; $serials++) { 
								$this->db->update("pos_product_serial" ,  array( 
								"return_id" => $data['return_id'],
								"product_serial_status" => 'transfer',
								"product_serial_stock_type" => 'out',
								"product_serial_category" => 'purchase_return',
								"sold_at" => date("Y-m-d H:i:s")
								), "`product_serial_no`= :id AND `sub_product_id`= :pid", array('id' => $data['serial_number'][$sub_product_id][$serials], 'pid' => $sub_product_id));
							}
						}
					}
				}
				
				if(app('admin')->checkAddon('multiple_store_warehouse')){
					if(isset($data['transaction_id'])){ 
						// require dirname(__FILE__) .'/../'.$loadPlugins['location'];
						for ($x = 0; $x < count($data['transaction_id']); $x++) {
							$posTransactionData['transaction_type'] = 'return';
							$posTransactionData['transaction_amount'] = $data['transaction_amount'][$x];
							$posTransactionData['transaction_flow_type'] = 'credit';
							$posTransactionData['payment_method_value'] = $data['transaction_method'][$x];
							$posTransactionData['transaction_no'] = $data['transaction_no'][$x];
							$posTransactionData['transaction_note'] = $data['transaction_note'][$x];
							$posTransactionData['purchase_id'] = $data['purchase_id'];
							$posTransactionData['transaction_status'] = 'paid';
							$posTransactionData['payment_for'] = $data['supplier_id'];
							
							$transactionCheck = $this->db->table('pos_transactions')->where("transaction_id",$data['transaction_id'][$x])->where("transaction_type",'return')->get(1);
							if($transactionCheck){
								$this->db->update("pos_transactions" ,  $posTransactionData , "`transaction_id` = :id AND `transaction_type` = :type ", array("id"=> $data['transaction_id'][$x], "type"=> "return"));
								}else{
								$posTransactionData['user_id'] = $userId;
								$posTransactionData['transaction_id'] = $data['transaction_id'][$x];
								$posTransactionData['return_id'] = $data['return_id'];
								$posTransactionData['created_at'] = date("Y-m-d H:i:s");
								$this->db->insert("pos_transactions", $posTransactionData);
							}
						}
					}
					}else{
					$data['transaction_id'] = 'TXN'.gettoken(8);
					if($this->db->table('pos_transactions')->where('return_id',$data['return_id'])->where('transaction_type','return')->where('is_delete','false')->get()){
						$this->db->update("pos_transactions",  array(
						"store_id" => $storeid,
						"user_id" => $userId,
						"purchase_id" => $data['purchase_id'],
						"transaction_amount" => $data['purchase_return_total'],
						"payment_for" => $data['supplier_id'],
						"paid_date"=> date("Y-m-d H:i:s"),
						"update_at"=> date("Y-m-d H:i:s")
						), "`return_id`= :id AND `transaction_type`= 'return' AND `is_delete`= 'false'", array('id' => $data['return_id']));
						}else{
						$this->db->insert("pos_transactions",  array(
						"store_id" => $storeid,
						"transaction_id" => $data['transaction_id'],
						"user_id" => $userId,
						"purchase_id" => $data['purchase_id'],
						"return_id" => $data['return_id'],
						"transaction_type" => 'return',
						"transaction_amount" => $data['purchase_return_total'],
						"transaction_flow_type" => 'credit',
						"transaction_note" => "Purchase Return Paid",
						"transaction_status" => 'paid',
						"payment_for" => $data['supplier_id'],
						"paid_date"=> date("Y-m-d H:i:s"),
						"created_at"=> date("Y-m-d H:i:s"),
						"update_at"=> date("Y-m-d H:i:s")
						));
					}
				}
				
				$GetPurchaseByReturnOrderCheck = $this->GetPurchaseByCustomerOrder(false,$data['purchase_id']);
				if($data['purchase_return_total'] <= $GetPurchaseByReturnOrderCheck['total_return_paid']){	
					$this->db->update("pos_return" ,  array( 
					"return_payment_status" => 'paid'
					), "`return_id` = :id ", array("id"=> $data['return_id']));
					}else{
					$this->db->update("pos_return" ,  array( 
					"return_payment_status" => 'due'
					), "`return_id` = :id ", array("id"=> $data['return_id']));
				}
				
				respond(array(
				"status" => "success",
				"massage" => trans('purchase_return_complete_without_payment'),
				"payment" => "false"
				));
			}
			
			public function getlastproductstock($sub_product_id,$start_date,$end_date) {
				$result = $this->db->select("SELECT * FROM `pos_stock` WHERE `stock_type` = 'in' AND `sub_product_id` = :id AND date(expire_date) BETWEEN :start_date AND :end_date ORDER BY `id` DESC LIMIT 1", array( 
				'id' => $sub_product_id,
				'start_date' => $start_date,
				'end_date' => $end_date,
				));
				return count($result) > 0 ? $result[0] : null;
			}	
			
			public function AddNewPaymentMethodSubmit($data,$userId)
			{
				$payment_method_value =strtolower(preg_replace('/\s/', '', $data['payment_method_name']));
				$checkExist=count(app('admin')->getwhere('pos_payment_method','payment_method_value',$payment_method_value));
				if(isset($data['payment_method_id'])){
					$this->db->update("pos_payment_method" ,  array(
					"payment_method_name"=>$data['payment_method_name'],
					"payment_method_value"=>$payment_method_value,
					"account_number"=>$data['account_number'],
					"minimum_amount"=>$data['minimum_amount'],
					"updated_at" => date("Y-m-d H:i:s")
					),
					"`payment_method_id` = :id ",
					array("id"=> $data['payment_method_id'])
					);
					respond(array(
					"status" => "success"
					));
				}
				else{
					if($checkExist==0){
						$this->db->insert("pos_payment_method",  array(				
						"payment_method_name"=>$data['payment_method_name'],
						"payment_method_value"=>$payment_method_value,
						"payment_method_type"=>"account_bank",
						"account_number"=>$data['account_number'],
						"minimum_amount"=>$data['minimum_amount'],
						"user_id"=>$userId,
						"payment_method_status"=>'active',
						"created_at"=> date("Y-m-d H:i:s")
						));
						respond(array(
						"status" => "success",
						"payment_method_value" => $payment_method_value,
						"payment_method" => $data['payment_method_name']
						));
					}
					else{
						respond(array(
						"status" => "error",
						"message"	=>$data['payment_method_name'].' already exists'
						));
					}
				}
			}
			public function StockTransferSubmit($data,$userId,$storeId)
			{
				$from = '';
				$to ='';
				if(app('admin')->checkAddon('multiple_store_warehouse')){
					if($data['from_location'] == $data['to_location']){
						
						respond(array(
						'status' => 'error',
						'errors' => array(
						'to_location' => trans('transfer_not_possible_to_same_location')
						)
						), 422);
					}
					
					$from = $data['from_location'];
					$to = $data['to_location'];
					
				}
				
				$stock_transfer_id = 'STR'.gettoken(8);
				$this->db->insert("pos_stock_transfer",array(
				"stock_transfer_id" => $stock_transfer_id,
				"from_store_id" => $from,
				"to_store_id" => $to ,
				"date" => $data['date'],
				"reference_no" => $data['reference_no'],
				"store_id" => $storeId,
				"user_id" => $userId,
				"shipping_charge" => $data['shipping_charge'],
				"stock_transfer_note" => $data['note'],
				"stock_transfer_status"=> $data['status'],
				"created_at"=> date("Y-m-d H:i:s"),
				));
				for ($x = 0; $x < count($data['sub_product_id']); $x++) {
					$product_id = $data['product_id'][$x];
					$sub_product_id = $data['sub_product_id'][$x];
					$product_stock_id = $data['product_stock_id'][$x];
					$available_stock = $data['available_stock'][$x];
					$product_quantity = $data['purchase_product_quantity'][$x];
					$product_price = $data['purchase_product_price'][$x];
					$product_sub_total = $data['purchase_product_sub_total'][$x];
					$this->db->insert("pos_stock",  array(
					"stock_id" => $product_stock_id,
					"product_id" => $product_id,
					"transfer_id" => $stock_transfer_id,
					"sub_product_id" => $sub_product_id,
					"store_id" => $data['from_location'],
					"user_id" => $userId,
					"product_quantity" => $product_quantity,
					"product_price" => $product_price,
					"stock_category" => 'transfer',
					"stock_type" => 'out',
					"stock_date"=> $data['date'],
					"product_subtotal" => $product_sub_total,
					"created_at"=> date("Y-m-d H:i:s"),
					"stock_status" => 'active',
					));
					$this->db->insert("pos_stock",  array(
					"stock_id" => 'ST'.gettoken(8),
					"product_id" => $product_id,
					"transfer_id" => $stock_transfer_id,
					"sub_product_id" => $sub_product_id,
					"store_id" => $data['to_location'],
					"user_id" => $userId,
					"product_quantity" => $product_quantity,
					"product_price" => $product_price,
					"stock_category" => 'transfer',
					"stock_type" => 'in',
					"stock_date"=> $data['date'],
					"product_subtotal" => $product_sub_total,
					"created_at"=> date("Y-m-d H:i:s"),
					"stock_status" => 'active',
					));
				}
				respond(array(
				"status" => "success"
				));
			}
			public function DeleteStockTransfer($data){
				$this->db->update("pos_stock_transfer" , array('is_delete' => 'true'), "`stock_transfer_id`= :id", array('id' => $data['stock_transfer_id']));
				$this->db->update("pos_stock",  array('is_delete' => 'true'), "transfer_id = :el", array( "el" => $data['stock_transfer_id'] ));
				
				respond(array(
				"status" => "success"
				));
			}
			
			public function StockAdjustmentSubmit($data,$userId,$storeId){
				
				$store_id='';
				
				if(app('admin')->checkAddon('multiple_store_warehouse')){
					$store_id = $data['store'];
				}
				
				$stock_adjustment_id = 'STA'.gettoken(8);
				$this->db->insert("pos_stock_adjustment",array(
				"stock_adjustment_id" => $stock_adjustment_id,
				"from_store_id" => $store_id ,
				"date" => $data['date'],
				"reference_no" => $data['reference_no'],
				"type" => $data['type'],
				"store_id" => $storeId,
				"user_id" => $userId,
				"shipping_charge" => $data['shipping_charge'],
				"stock_adjustment_note" => $data['note'],
				"created_at"=> date("Y-m-d H:i:s"),
				));
				for ($x = 0; $x < count($data['sub_product_id']); $x++) {
					$product_quantity = $data['purchase_product_quantity'][$x];
					$product_id = $data['product_id'][$x];
					$sub_product_id = $data['sub_product_id'][$x];
					$product_stock_id = $data['product_stock_id'][$x];
					$available_stock = $data['available_stock'][$x];
					$product_price = $data['purchase_product_price'][$x];
					$product_sub_total = $data['purchase_product_sub_total'][$x];
					if($product_quantity<1){
						respond(array(
						'status' => 'error',
						'errors' => array(
						'purchase_quantity_'.$sub_product_id => trans('0_quantity_not_allow'),
						)), 422);
					}
					$this->db->insert("pos_stock",  array(
					"stock_id" => $product_stock_id,
					"adjustment_id" => $stock_adjustment_id,
					"adjustment_type" => $data['type'],
					"product_id" => $product_id,
					"sub_product_id" => $sub_product_id,
					"product_quantity" => $product_quantity,
					"product_price" => $product_price,
					"product_subtotal" => $product_sub_total,
					"stock_category" => 'damage',
					"stock_type" => 'out',
					"store_id" => $storeId,
					"user_id" => $userId,
					"stock_date"=> $data['date'],
					"created_at"=> date("Y-m-d H:i:s"),
					"stock_status" => 'active',
					));		
				}
				$this->db->insert("pos_transactions",array(
				"transaction_type" => 'adjustment',
				"adjustment_id" => $stock_adjustment_id,
				"transaction_amount" => $data['redovered_amount'],
				"transaction_flow_type" => 'credit',
				"payment_method_value" => $data['payment_method_value'],
				"transaction_no" => $data['transaction_no'],
				"paid_date" => $data['date'],
				"user_id" => $userId,
				"store_id" => $storeId,
				"transaction_status" => 'paid',
				"created_at"=> date("Y-m-d H:i:s"),
				));
				respond(array(
				"status" => "success"
				));
			}
			
			public function DeleteStockAdjustment($data){
				$this->db->update("pos_stock_adjustment" , array('is_delete' => 'true'), "`stock_adjustment_id`= :id", array('id' => $data['stock_adjustment_id']));
				$this->db->update("pos_stock",  array('is_delete' => 'true'), "adjustment_id = :el", array( "el" => $data['stock_adjustment_id'] ));
				$this->db->update("pos_transactions", array('is_delete' => 'true'), "adjustment_id = :el", array( "el" => $data['stock_adjustment_id'] ));
				respond(array(
				"status" => "success"
				));
			}
			public function AddPaymentTransferSubmit($data,$user_id){
				$save_amount=0;
				$getCreditAmount = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("transaction_flow_type" => 'credit',"payment_method_value" => $data['payment_method_value'],"transaction_status" => 'paid'));
				$getdebitAmount = app('admin')->GetSum("pos_transactions",array("transaction_amount"),array("transaction_flow_type" => 'debit',"payment_method_value" => $data['payment_method_value'],"transaction_status" => 'paid'));
				$current_balance = $getCreditAmount['transaction_amount'] - $getdebitAmount['transaction_amount'];
				$getMinimumAmount = app('admin')->getwhereid('pos_payment_method','payment_method_value',$data['payment_method_value']);
				$transfer_able_amount = $data['transfer_amount'] + $getMinimumAmount['minimum_amount'];
				if($current_balance > $getMinimumAmount['minimum_amount']){
					if($transfer_able_amount <= $current_balance){
						$this->db->insert("pos_transactions",array(
						"transaction_type" 		=> "transfer",
						"transaction_flow_type" => 'debit',
						"payment_method_value"	=> $data['payment_method_value'],
						"transaction_amount"	=> $data['transfer_amount'],
						"transaction_no"		=> $data['transaction_number'],
						"transaction_note"		=> $data['payment_note'],
						"user_id"				=> $user_id,
						"created_at"			=> date("Y-m-d H:i:s")
						));
						$matching_id = $this->db->lastInsertId();
						$this->db->insert("pos_transactions",array(
						"transaction_type" 		=> "transfer",
						"transaction_flow_type" => 'credit',
						"payment_method_value"	=> $data['payment_transfer_method'],
						"matching_id"	=> $matching_id,
						"transaction_amount"	=> $data['transfer_amount'],
						"transaction_no"		=> $data['transaction_number'],
						"transaction_note"		=> $data['payment_note'],
						"user_id"		=> $user_id,
						"created_at"		=> date("Y-m-d H:i:s")
						));
						respond(array(
						'status' => 'success',
						));
						}else{
						respond(array(
						'status' => 'error',
						'errors' => array(
						'transfer_amount' => trans('not_availiable_amount')
						)
						), 422);
					}
					}else{
					respond(array(
					'status' => 'error',
					'errors' => array(
					'transfer_amount' => trans('not_allow')
					)
					), 422);
				}
			}
			public function DeleteStockByStockId($data){
				
				$this->db->update("pos_stock" ,  array(
				"is_delete"=> "true",
				),
				"`stock_id` = :id ",
				array("id"=> $data['stock_id'])
				);
				
				
				respond(array(
				"status" => "success",
				"message" => trans('product_successfully_deleted')
				));
			}
			
			public function AddAccountLeaseData($data , $user){
				$this->db->insert("accounts_chart",array(
				"chart_name" 		=> $data["lease_chart_account_name"],
				"chart_name_value" => $data['lease_chart_account_value'],
				"chart_category_name"	=> $data['lease_chart_account_category'],
				"chart_sub_category_name"	=> $data['lease_chart_account_subcategory'],
				"chart_type"		=> 'debit',
				"user_id"			=> $user,
				));
				
				respond(array(
				"status"       => "success",
				"chart_name"   => $data['lease_chart_account_name'],
				"chart_name_value"  => $data['lease_chart_account_value'],
				));
			}
			public function AddAccountAssetData($data , $user){
				$this->db->insert("accounts_chart",array(
				"chart_name" 		=> $data["asset_chart_account_name"],
				"chart_name_value" => $data['asset_chart_account_value'],
				"chart_category_name"	=> $data['asset_chart_account_category'],
				"chart_sub_category_name"	=> $data['asset_chart_account_subcategory'],
				"chart_type"		=> 'debit',
				"user_id"			=> $user,
				));
				
				respond(array(
				"status"       => "success",
				"chart_name"   => $data['asset_chart_account_name'],
				"chart_name_value"  => $data['asset_chart_account_value'],
				));
			}
			
			public function TransactionDeleteById($data)
			{
				
				$this->db->update("accounts_transactions" ,  array(
				"is_delete"=> "true",
				),
				"`id` = :id ",
				array("id"=> $data['id'])
				);
				
				respond(array(
				"status"       => "success",
				"message"   => trans('successfully_deleted'),
				));
			}
			public function DeletePaymentMethod($data)
			{
				
				$this->db->update("pos_payment_method" ,  array(
				"is_delete"=> "true",
				),
				"`payment_method_id` = :id ",
				array("id"=> $data['payment_method_id'])
				);
				
				respond(array(
				"status"       => "success",
				"message"   => trans('successfully_deleted'),
				));
			}
			public function AddNewPaymentTransferSubmit($data,$userId,$storeId)
			{
				$dr = '';
				$cr = '';
				if($data['account_from']=='cash'){
					$cr = 'account_cash';
				}
				else{
					$cr = 'account_bank';
				}
				
				if($data['account_to']=='cash'){
					$dr = 'account_cash';
				}
				else{
					$dr = 'account_bank';
				}
				$store_id = '';
				if(app('admin')->checkAddon('multiple_store_warehouse')){
					$store_id = $data['store_id'];
				}
				
				$this->db->insert("accounts_transactions",array(
				"store_id" 		=> $store_id,
				"user_id" 		=> "AU00000000",
				"dr_account" 		=> "account_transfer",
				"dr_account_status" 		=> 'paid',
				"cr_account" 		=> $cr,
				"cr_account_status" 		=> 'paid',
				"amount" 		=> $data["amount"],
				"payment_method" 		=> $data["account_from"],
				"account_type" 		=> 'account_transfer',
				"account_status" 	=> 'paid',
				"date" 	=> $data["date"],
				"note" 		        => $data["transfer_note"]
				));
				
				
				$transferFrom = $this->db->lastInsertId();
				
				$this->db->insert("accounts_transactions",array(
				"store_id" 		=> $store_id,
				"user_id" 		=> "AU00000000",
				"dr_account" 		=> $dr,
				"dr_account_status" 		=> 'paid',
				"cr_account" 		=> "account_transfer",
				"cr_account_status" 		=> 'paid',
				"amount" 		=> $data["amount"],
				"payment_method" 		=> $data["account_to"],
				"from_payment_method" 		=> $transferFrom,
				"account_type" 		=> 'account_transfer',
				"account_status" 	=> 'paid',
				"date" 	=> $data["date"],
				"note" 		        => $data["transfer_note"]
				));
				
				respond(array(
				"status"       => "success",
				"message"   => 'Transfered',
				));
			}
			public function TransferDelete($data)
			{
				
				$this->db->update("accounts_transactions" ,  array(
				"is_delete"=> "true",
				),
				"`id` = :id ",
				array("id"=> $data['id'])
				);
				
				respond(array(
				"status"       => "success",
				"message"   => trans('successfully_deleted'),
				));
			}
	}
