<?php defined('_AZ') or die('Restricted access');
	
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri_segments = explode('/', $uri_path);
	
	if($uri_segments[2]=="pos-sales"){
		echo "<body class='canvas-menu'>";
		}elseif($uri_segments[2]=="registry"||$uri_segments[2]=="access-denied"){
		echo "<body class='pace-done mini-navbar'>";
		}else{
		echo "<body class='pace-done'>";
	}
?>
<div id="wrapper">
	<nav class="navbar-default navbar-static-side" role="navigation">
		<div class="sidebar-collapse">
			<a class="close-canvas-menu"><i class="fa fa-times"></i></a>
			
			<ul class="nav metismenu" id="side-menu">
				<li class="nav-header">
					<div class="dropdown profile-element">
						<a data-toggle="dropdown" class="dropdown-toggle" href="">
							<span class="clear">
								<span class="m-t-xs">
									<strong class="font-bold"><?php echo $this->currentUser->first_name." ".$this->currentUser->last_name; ?></strong>
								</span>
								<span class="text-muted text-xs block"><?php echo $this->currentUser->role; ?>
									<b class="caret"></b>
								</span>
							</span>
						</a>
						
						<ul class="dropdown-menu animated fadeInRight m-t-xs">
							<li><a href="pos/account-setting"><?php echo trans('my_profile'); ?></a></li>
							<li class="divider"></li>
							<li>
								<a href="javascript:void(0)" class="account_lock" id="<?php echo ASSession::get("user_id"); ?>">
									<?php echo trans('lock'); ?>                                    		
								</a>
							</li>
						</ul>
					</div>
					<div class="logo-element">
						<span class="m-t-xs"><?php echo $this->currentUser->first_name." ".$this->currentUser->last_name; ?></span>
						<?php echo @$this->currentUser->role; ?>
					</div>
				</li>
				
				<?php if($uri_segments[2]== "home"){?><li class="active"><?php }else{echo "<li>";} ?>
					<a href="pos/home"><i class="fa fa-home"></i> <span class="nav-label"><?php echo trans('home'); ?></span> </a>
				</li>
				
				<li class="<?php getActiveMenu($this->uri,'pos/customer') || getActiveMenu($this->uri,'pos/supplier') || getActiveMenu($this->uri,'pos/customer-view') || getActiveMenu($this->uri,'pos/supplier-view'); ?>">
					<a><i class="fa fa-address-book"></i> <span class="nav-label"><?php echo trans('contact');?> </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						<li class="<?php getActiveMenu($this->uri,'pos/customer') || getActiveMenu($this->uri,'pos/customer-view'); ?>">
							<a href="pos/customer"><i class="fa fa-address-card"></i><?php echo trans('customer'); ?></a>
						</li>
						<li class="<?php getActiveMenu($this->uri,'pos/supplier') || getActiveMenu($this->uri,'pos/supplier-view'); ?>">
							<a href="pos/supplier"><i class="fas fa-shipping-fast"></i><?php echo trans('supplier'); ?></a>
						</li>
					</ul>
				</li>
				
				<li class="<?php getActiveMenu($this->uri,'pos/product-list') || getActiveMenu($this->uri,'pos/ad-product'); ?>">
					<a ><i class="fas fa-cubes"></i> <span class="nav-label"><?php echo trans('products'); ?> </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						<li class="<?php getActiveMenu($this->uri,'pos/product-list'); ?>">
							<a href="pos/product-list"><i class="fab fa-product-hunt"></i><?php echo trans('product_list'); ?></a>
						</li>
						<li class="<?php getActiveMenu($this->uri,'pos/print-barcode'); ?>">
							<a href="pos/print-barcode"><i class="fa fa-barcode"></i><?php echo trans('print_barcode'); ?></a>
						</li>
						
						
					</ul>
				</li>
				
				<?php if(($uri_segments[2]=='purchase')||($uri_segments[2]=='purchase-list')||($uri_segments[2]=='purchase-return')||($uri_segments[2]=='return-purchase-list')){ echo '<li class="active">'; }else{ echo "<li>";}?>
					<a ><i class="fa fa-arrow-circle-down"></i> <span class="nav-label"><?php echo trans('purchase');?> </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						
						<?php if(($uri_segments[2]=='purchase-list')){ echo '<li class="active">';}else{ echo "<li>";}?>
							<a href="pos/purchase-list"><i class="fa fa-list"></i><?php echo trans('list_purchase');?></a>
						</li>
						<?php if(app('admin')->checkAddon('purchase_return')){
						if(($uri_segments[2]=='purchase-list')){ echo '<li class="active">';}else{ echo "<li>";}?>
							<a href="pos/return-purchase-list"><i class="fa fa-list"></i><?php echo trans('list_purchase_return');?></a>
						</li>
						<?php } ?>
					</ul>
				</li>
				<?php if(($uri_segments[2]=='pos-sales')||($uri_segments[2]=='pos-sales-list')||($uri_segments[2]=='invoice-sales-list')||($uri_segments[2]=='sales-view')||($uri_segments[2]=='challan')||($uri_segments[2]=='sell-return-report')||($uri_segments[2]=='quotations-list')){ echo '<li class="active">'; }else{ echo "<li>";}?>
					<a ><i class="fa fa-arrow-circle-up"></i> <span class="nav-label"><?php echo trans('sell');?> </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
					
						<?php if(app('admin')->checkAddon('add_pos_sales')){ ?>
						<?php if(($uri_segments[2]=='pos-sales-list')){ echo '<li class="active">'; }else{ echo "<li>";}?>
							<a href="pos/pos-sales-list"><i class="fa fa-list"></i><?php echo trans('pos_sell_list');?></a>
						</li>
						<?php } ?>
						
						<?php if(app('admin')->checkAddon('invoice_sale')){
						 if($uri_segments[2]=="invoice-sales-list" || $uri_segments[2]=="sales-view" || $uri_segments[2]=="challan"){?><li class="active"><?php }else{echo "<li>";} ?>
								<a href="pos/invoice-sales-list"><i class="fa fa-list"></i><?php echo trans('invoice_sells_list');?></a>
							</li>
						<?php } ?>

						<?php if(app('admin')->checkAddon('sale_return')){ if($uri_segments[2]=='sell-return-report'){ echo '<li class="active">'; }else{ echo "<li>";} ?>
						<a href="pos/sell-return-report"><i class="fa fa-undo"></i><?php echo trans('list_sell_return');?></a>
						</li>
						<?php } ?>
						
						<?php if(app('admin')->checkAddon('quotations')){ if($uri_segments[2]=='quotations-list'){ echo '<li class="active">'; }else{ echo "<li>";} ?>
						<a href="pos/quotations-list"><i class="fa fa-undo"></i><?php echo trans('list_quotation');?></a>
						</li>
						<?php } ?>
						
						
						
					</ul>
				</li>
				<?php if(app('admin')->checkAddon('accounts')){
					if(($uri_segments[2]=='accounts')||($uri_segments[2]=='income')||($uri_segments[2]=='expense') || ($uri_segments[2]=='chart-accounts' || ($uri_segments[2]=='transfer') || ($uri_segments[2]=='account-new') || ($uri_segments[2]=='capital') || ($uri_segments[2]=='withdraw') || ($uri_segments[2]=='account-new')||($uri_segments[2]=='trial-balance')||($uri_segments[2]=='income-statement')||($uri_segments[2]=='financial-statement')||($uri_segments[2]=='owner-equity'))){ echo '<li class="active">'; }else{ echo "<li>";}?>
					<a ><i class="fa fa-university"></i> <span class="nav-label"><?php echo trans('accounts');?> </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						<?php //if(($uri_segments[2]=='chart-accounts')){ echo '<li class="active">';}else{ echo "<li>";}?>
								<!--a href="pos/chart-accounts"><i class="far fa-gem"></i><?php //echo trans('account_chart');?></a>
							</li-->
						
						<?php if(($uri_segments[2]=='income')){ echo '<li class="active">'; }else{ echo "<li>";}?>
							<a href="pos/income"><i class="fa fa-plus-circle"></i><?php echo trans('income');?></a>
						</li>
						<?php if(($uri_segments[2]=='account-user')){ echo '<li class="active">'; }else{ echo "<li>";}?>
							<a href="pos/account-user"><i class="fa fa-user"></i><?php echo trans('account_user');?></a>
						</li>
						<?php if(($uri_segments[2]=='expense')){ echo '<li class="active">'; }else{ echo "<li>";}?>
							<a href="pos/expense"><i class="fa fa-minus-circle"></i><?php echo trans('expense');?></a>
						</li>
						<?php if(($uri_segments[2]=='capital')){ echo '<li class="active">'; }else{ echo "<li>";}?>
							<a href="pos/capital"><i class="fa fa-plus-circle"></i><?php echo trans('capital_deposit');?></a>
						</li>
						<?php if(($uri_segments[2]=='withdraw')){ echo '<li class="active">'; }else{ echo "<li>";}?>
							<a href="pos/withdraw"><i class="fa fa-plus-circle"></i><?php echo trans('withdraw');?></a>
						</li>
						<?php if(($uri_segments[2]=='transfer')){ echo '<li class="active">'; }else{ echo "<li>";}?>
							<a href="pos/transfer"><i class="far fa-gem"></i><?php echo trans('transfer');?></a>
						</li>
						<?php if(($uri_segments[2]=='accounts')){ echo '<li class="active">'; }else{ echo "<li>";}?>
							<a href="pos/accounts"><i class="fa fa-plus-circle"></i><?php echo trans('head_of_account');?></a>
						</li>
						<?php if(($uri_segments[2]=='trial-balance') || ($uri_segments[2]=='income-statement')||($uri_segments[2]=='financial-statement')||($uri_segments[2]=='owner-equity')){ echo '<li class="active">';} else { echo "<li>"; }?>
						<a href="#"><i class="far fa-chart-bar"></i> <span class="nav-label"><?php echo trans('report');?> </span><span class="fa arrow"></span></a>
							<ul class="nav nav-third-level collapse" style="">
								</li>
								<?php if($uri_segments[2]=='trial-balance'){ echo '<li class="active">';} else { echo "<li>"; }?>
									<a href="pos/trial-balance"><?php echo trans('trial_balance');?></a>
								</li>
								<?php if(($uri_segments[2]=='/income-statement')){ echo '<li class="active">'; }else{ echo "<li>";}?>
									<a href="pos/income-statement"><?php echo trans('income_statement');?></a>
								</li>
								<?php if(($uri_segments[2]=='/financial-statement')){ echo '<li class="active">'; }else{ echo "<li>";}?>
									<a href="pos/financial-statement"><?php echo trans('financial_statement');?></a>
								</li>
								<?php if(($uri_segments[2]=='/owner-equity')){ echo '<li class="active">'; }else{ echo "<li>";}?>
									<a href="pos/owner-equity"><?php echo trans("owner_equity");?></a>
								</li>
								
							</ul>
						</li>
						
					</ul>
				</li>
				<?php if(app('admin')->checkAddon('multiple_payment_method')){
					if($uri_segments[2]=='payment-transfer'){ ?><li class="active"><?php  } else echo "<li>"; ?>
					<!--a href="pos/payment-transfer"><i class="fas fa-exchange-alt"></i> <span class="nav-label"><?php echo trans('payment_transfer');?></span></a>
					</li-->
				
					
				<?php if(app('admin')->checkAddon('stock_transfer')){ if(($uri_segments[2]=='stock-transfer')||($uri_segments[2]=='stock-transfer-list')){ echo '<li class="active">';}else{ echo "<li>";}?>
					<a ><i class="fa fa-truck"></i> <span class="nav-label"><?php echo trans('stock_transfer');?> </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						<?php if($uri_segments[2]=='stock-transfer'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/stock-transfer"><i class="fas fa-money-bill-alt"></i><?php echo trans('add_stock_transfer');?></a>
						</li>
						<?php if($uri_segments[2]=='stock-transfer-list'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/stock-transfer-list"><i class="fas fa-money-bill-alt"></i><?php echo trans('list_stock_transfer');?></a>
						</li>
					</ul>
				</li>
				<?php } if(app('admin')->checkAddon('stock_adjustment')){  if(($uri_segments[2]=='stock-adjustment')||($uri_segments[2]=='stock-adjustment-list')){ echo '<li class="active">';}else{ echo "<li>";}?>
					<a ><i class="fa fa-pause-circle"></i> <span class="nav-label"><?php echo trans('stock_adjustment');?> </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						<?php if($uri_segments[2]=='stock-adjustment'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/stock-adjustment"><i class="fa fa-plus-circle"></i><?php echo trans('add_stock_adjustment');?></a>
						</li>
						<?php if($uri_segments[2]=='stock-adjustment-list'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/stock-adjustment-list"><i class="fa fa-list"></i><?php echo trans('list_stock_adjustment');?></a>
						</li>
					</ul>
				</li>
				<?php } ?>
				<?php }} if(($uri_segments[2]=='register-report')||($uri_segments[2]=='sale-payment-report')||($uri_segments[2]=='purchase-payment-report')||($uri_segments[2]=='product-sale-report')||($uri_segments[2]=='profit-loss')||($uri_segments[2]=='purchase-sell-report')||($uri_segments[2]=='contact-report')||($uri_segments[2]=='stock-report')||($uri_segments[2]=='vat-report')||($uri_segments[2]=='purchase-report')||($uri_segments[2]=='sell-report')||($uri_segments[2]=='stock-adjustment-report')){ echo '<li class="active">';}else{ echo "<li>";}?>
					<a ><i class="far fa-chart-bar"></i> <span class="nav-label"><?php echo trans('reports');?> </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						<?php if($uri_segments[2]=='profit-loss'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/profit-loss"><i class="fas fa-money-bill-alt"></i><?php echo trans('profit_loss_report');?></a>
						</li>
						<?php if($uri_segments[2]=='purchase-sell-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/purchase-sell-report"><i class="fas fa-exchange-alt"></i><?php echo trans('purchase_sell_report');?></a>
						</li>
						<?php if($uri_segments[2]=='contact-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/contact-report"><i class="fa fa-address-book"></i><?php echo trans('supplier_customer_report');?></a>
						</li>
						<?php if($uri_segments[2]=='stock-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/stock-report"><i class="fa fa-hourglass-half"></i><?php echo trans('stock_report');?></a>
						</li>
						<?php if($uri_segments[2]=='vat-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/vat-report"><i class="fa fa-hourglass-half"></i><?php echo trans('tax_report');?></a>
						</li>
						<?php if($uri_segments[2]=='purchase-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/purchase-report"><i class="fa fa-arrow-circle-down"></i><?php echo trans('product_purchase_report');?></a>
						</li>
						<?php if($uri_segments[2]=='product-sale-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/product-sale-report"><i class="fa fa-arrow-circle-down"></i><?php echo trans('product_sell_report');?></a>
						</li>
						<?php if($uri_segments[2]=='purchase-payment-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/purchase-payment-report"><i class="fa fa-arrow-circle-down"></i><?php echo trans('purchase_payment_report');?></a>
						</li>
						<?php if($uri_segments[2]=='sale-payment-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/sale-payment-report"><i class="fa fa-arrow-circle-down"></i><?php echo trans('sell_payment_report');?></a>
						</li>
						<?php if($uri_segments[2]=='register-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/register-report"><i class="fa fa-arrow-circle-down"></i><?php echo trans('register_report');?></a>
						</li>
						<?php if(app('admin')->checkAddon('stock_adjustment')){ if($uri_segments[2]=='stock-adjustment-report'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/stock-adjustment-report"><i class="fa fa-arrow-circle-down"></i><?php echo trans('stock_adjustment_report');?></a>
						</li>
						<?php }?>
						
				<?php if($uri_segments[2]=='ticket_list'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/ticket_list"><i class="fa fa-arrow-circle-down"></i><?php echo trans('ticket_list');?></a>
						</li>
					</ul>
				</li>
				<?php if(($uri_segments[2]=='setting')||($uri_segments[2]=='multiple-payment-method')||($uri_segments[2]=='category')||($uri_segments[2]=='unit')||($uri_segments[2]=='brand')||($uri_segments[2]=='manage-user')||($uri_segments[2]=='new-user')||($uri_segments[2]=='store')||($uri_segments[2]=='warehouse')||($uri_segments[2]=='invoice-setting')||($uri_segments[2]=='variation')){ echo '<li class="active">';}else{ echo "<li>";}?>
					<a><i class="fa fa-cog"></i> <span class="nav-label"><?php echo trans('setting');?> </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						<?php if($uri_segments[2]=='setting'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/setting"><i class="fa fa-cog"></i><?php echo trans('pos_setting'); ?></a>
						</li>
						<?php if($uri_segments[2]=='unit'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/unit"><i class="fa fa-balance-scale"></i><?php echo trans('unit'); ?></a>
						</li>
						<?php if($uri_segments[2]=='category'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/category"><i class="fa fa-list"></i><?php echo trans('category'); ?></a>
						</li>
						<?php if($uri_segments[2]=='brand'){ echo '<li class="active">';} else { echo "<li>"; }?>
							<a href="pos/brand"><i class="fa fa-sort-alpha-desc"></i><?php echo trans('brand'); ?></a>
						</li>
						<?php if(app('admin')->checkAddon('multiple_store_warehouse')){ ?>
							<?php if(($uri_segments[2]=='store')){ echo '<li class="active">';}else{ echo "<li>";}?>
								<a href="pos/store"><i class="fa fa-shopping-cart"></i><?php echo trans('store');?></a>
							</li>
						<?php } ?>
						<?php if(app('admin')->checkAddon('multiple_store_warehouse')){ ?>
							<?php if(($uri_segments[2]=='warehouse')){ echo '<li class="active">';}else{ echo "<li>";}?>
								<a href="pos/warehouse"><i class="fas fa-warehouse"></i><?php echo trans('warehouse');?></a>
							</li>
						<?php } ?>
						<li class="<?php getActiveMenu($this->uri,'pos/variation'); ?>">
							<a href="pos/variation"><i class="far fa-circle"></i><?php echo trans('variations');?></a>
						</li>
						<?php if(app('admin')->checkAddon('manage_user')){ ?>
							<?php if(($uri_segments[2]=='manage-user')||($uri_segments[2]=='new-user')){ echo '<li class="active">';}else{ echo "<li>";}?>
								<a href="pos/manage-user"><i class="fa fa-users"></i><?php echo trans('manage_user');?></a>
							</li>
						<?php } ?>
						<?php if(app('admin')->checkAddon('invoice_setting')){ ?>
							<?php if(($uri_segments[2]=='invoice-setting')){ echo '<li class="active">';}else{ echo "<li>";}?>
								<a href="pos/invoice-setting"><i class="fa fa-users"></i><?php echo trans('invoice_setting');?></a>
							</li>
						<?php } ?>
						<?php if(app('admin')->checkAddon('multiple_payment_method')){ ?>
							<?php if(($uri_segments[2]=='multiple-payment-method')){ echo '<li class="active">';}else{ echo "<li>";}?>
								<a href="pos/multiple-payment-method"><i class="fa fa-credit-card"></i><?php echo trans('add_multiple_payment_method');?></a>
							</li>
						<?php } ?>
						</ul>
						
					</li>
				</ul>
			</div>
		</nav>																																																																																												