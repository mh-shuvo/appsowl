<?php
	class ASAdmin
	{
		private $db = null;
		
		private $root = null;
		
		private $users;
		
		public function __construct(ASDatabase $db,ASDatabase $root, ASUser $users)
		{
			$this->db = $db;
			$this->root = $root;
			$this->users = $users;
		}
		
		public function GetSubscribeUpdate($userId,$softwareId){ 
			$result = $this->db->select("SELECT * FROM `as_subscribe` WHERE `software_id` = :sid AND `user_id` = :id", array( 'id' => $userId, 'sid' => $softwareId));
			$result = count($result) > 0 ? $result[0] : null;
			if($result){
				$subscribe_payment = $this->getsumtotalbywhereand('as_subscribe_payment','subscribe_month','subscribe_id',$result['subscribe_id'],'subscribe_payment_status','paid');
				if($result['subscribe_status'] == 'active'){
					if($subscribe_payment){
						$date = new DateTime($result['subscribe_date']);
						$date->modify('+'.$subscribe_payment.'month');
						$subscribe_end_date = $date->format('Y-m-d H:i:s');
						if($subscribe_end_date > date("Y-m-d H:i:s")){
							return 'active';
							}else{
							return 'renew';
						}
					}
				}
			}
			return $result;
		}
		
		public function GetSubscribePaymentConfirmation($token,$OrderId,$RefId){ 
			
			$result = $this->db->select("SELECT `payment_token`,`subscribe_payment_id` FROM `as_subscribe_payment` WHERE `payment_token` = :sid AND `subscribe_payment_id` = :oid",array(
			"sid" => $token,
			"oid" => $OrderId
			));
			if (count($result) == 1) {
				$this->db->update("as_subscribe_payment" ,  array( 
				'payment_request_time'		=>	date('Y-m-d H:i:s'),
				'payment_token'		=>	"",
				), " `subscribe_payment_id` = :sid", array(
				"sid" => $OrderId
				));
			}
		}
		
		public function GetSubscribePaymentRequestTokenUpdate($token,$OrderId,$RefId){ 
			$this->db->update("as_payment" ,  array( 
			'payment_request_time'		=>	date('Y-m-d H:i:s'),
			'payment_transaction_id'		=>	$RefId,
			'payment_token'		=>	$token,
			), " `payment_id` = :sid", array(
			"sid" => $OrderId
			));
			return;
		}
		
		public function GetSubscribePaymentRequest($userid, $data){ 
			$result = $this->db->select("SELECT * FROM `as_payment` WHERE `user_id` = :uid AND `payment_status` != :paystatus AND `payment_status` != :pstatus AND `payment_type` = :gatestatus ",array(
			"uid" => $userid,
			"gatestatus" => 'gateway',
			"paystatus" => "cancel",
			"pstatus" => "paid"
			));
			
			
			if (count($result) !== 1) {
				$this->db->insert('as_payment',array(
				'user_id'				=>	$userid,
				'created_at'				=>	date('Y-m-d H:i:s'),
				'payment_amount'		=>	$data['payment_amount'],
				'payment_type'		=>	"gateway",
				'payment_status'		=>	"due"
				));
				$paymentId = $this->db->lastInsertId();
				}else{
				$getPayment = count($result) > 0 ? $result[0] : null;
				$this->db->update("as_payment" ,  array( 
				'created_at'				=>	date('Y-m-d H:i:s'),
				'payment_amount'		=>	$data['payment_amount'],
				'payment_status'		=>	"due"
				), " `payment_id` = :sid", array(
				"sid" => $getPayment['payment_id']
				));
				$paymentId = $getPayment['payment_id'];
				
			}
			return $paymentId;
		}
		
		public function getDomainRegister($userid, $data)
		{ 
			$result = $this->db->select(
			"SELECT * FROM `as_sub_domain`
			WHERE `sub_domain` = :v",
			array("v" => $data['domain_name'])
			);
			
			if (count($result) !== 1) {
				$this->db->insert('as_sub_domain',array(
				'user_id'				=>	$userid,
				'created_at'				=>	date('Y-m-d H:i:s'),
				'sub_domain'				=>	$data['domain_name'],
				'root_domain'	=>	ROOT_DOMAIN,
				'basedir'	=>	BASE_URID
				));
				
				respond(array(
				"status" => "success"
				));
				
				}else{
				respond(array(
				'status' => 'error',
				'errors' => array(
				'domain_name' => trans('domain_already_register')
				)
				), 422);
			}
		}
		
		public function GetDomainCheck($data)
		{ 
			if($data['domain_name']!=null){
				$result = $this->db->select(
				"SELECT * FROM `as_sub_domain`
				WHERE `sub_domain` = :v",
				array("v" => $data['domain_name'])
				);
				
				if (count($result) !== 1) {
					echo '<span class="text-center success h4 text-success">Availabile</span> <br>';
					// echo '
					// <button type="submit" class="btn btn-success" >Next<i class="fa fa-arrow-right"></i></button>';
					}else{
					echo '<span class="text-center success h5">Not Availabile</span> <br>';
					
				}
			}
			
			
		}
		
		public function getallLimit($col,$limit,$orderby){
			$user_id=ASSession::get("user_id");
			$result = $this->db->select("SELECT * FROM `$col` WHERE `user_id`=$user_id ORDER BY `$orderby` DESC LIMIT $limit ");
			return $result;
		}
		
		public function getallLimitAnd($col,$where,$limit,$orderby,$and,$andid,$user_id){
			$result = $this->db->select("SELECT * FROM `$col` WHERE `$where` = :id AND `$and` = :andid ORDER BY `$orderby` DESC LIMIT $limit", array( 'id' => $user_id, 'andid' => $andid));
			return $result;
		}
		
		public function sendsms($number,$text) {
			$text = urlencode($text);
			$api = 'http://masking.zaman-it.com/smsapi?';
			$var = 'api_key='.SMS_API.'&type=text&senderid='.SMS_SENDER.'&msg='.$text.'&contacts='.str_replace("+", "", $number);
			$curl_handle = curl_init();  
			curl_setopt($curl_handle, CURLOPT_URL, $api.$var);  
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1); 
			$buffer = curl_exec($curl_handle);  
			curl_close($curl_handle);
			return $curl_handle;
		}
		
		public function getwhereandid($col,$where,$id,$and,$andid)
		{ 
			$result = $this->db->select("SELECT * FROM `$col` WHERE `$where` = :id AND `$and` = :andid", array( 'id' => $id, 'andid' => $andid));
			return count($result) > 0 ? $result[0] : null;
		}
		
		public function getwhereand($col,$where,$id,$and,$andid)
		{ 
			$result = $this->db->select("SELECT * FROM `$col` WHERE `$where` = :id AND `$and` = :andid", array( 'id' => $id, 'andid' => $andid));
			return $result;
			
		}
		
		
		public function getwhereid($col,$where,$id)
		{ 
			$result = $this->db->select("SELECT * FROM `$col` WHERE `$where` = :id", array( 'id' => $id));
			return count($result) > 0 ? $result[0] : null;
			
		} 
		
		public function getwhere($col,$where,$id)
		{ 
			$result = $this->db->select("SELECT * FROM `$col` WHERE `$where` = :id", array( 'id' => $id));
			return $result;
		}
		
		public function getall($col)
		{ 
			$result = $this->db->select("SELECT * FROM `$col` ");
			return $result;
			
		}
		
		public function getalluserdetails($id){
			$result = $this->root->select("SELECT `as_users`.*, `as_user_details`.*
			FROM `as_users`, `as_user_details`
			WHERE `as_users`.`user_id` = `as_user_details`.`user_id` AND `as_user_details`.`added_by` = :id", array( 'id' => $id));
			return $result;
		}
		
		public function getuserdetails($id){
			
			$result = $this->root->select("SELECT `as_users`.*, `as_user_details`.*
			FROM `as_users`, `as_user_details`
			WHERE `as_users`.`user_id` = `as_user_details`.`user_id` AND `as_users`.`user_id` = :id", array( 'id' => $id));
			
			return count($result) > 0 ? $result[0] : null;
		}
		
		public function GetApiCrossCheck($col,$where,$id)
		{ 
			$result = $this->db->select("SELECT `$where` FROM `$col` WHERE `$where` = :id", array( 'id' => $id));
			return $result;
			
		} 
		
		public function getallagentuserdetails($id){
			$result = $this->root->select("SELECT `as_users`.*, `as_user_details`.*
			FROM `as_users`, `as_user_details`
			WHERE `as_users`.`user_id` = `as_user_details`.`user_id` AND `as_user_details`.`agent_id` = :id", array( 'id' => $id));
			return $result;
		}
		
		public function getlastrow($col,$order) {
			$result = $this->db->select("SELECT * FROM `$col` ORDER BY `$order` DESC LIMIT 1");
			return count($result) > 0 ? $result[0] : null;
		}
		
		public function getlastrowwhere($col,$where,$whereid,$order) {
			$result = $this->db->select("SELECT * FROM `$col` WHERE `$where` = :id ORDER BY `$order` DESC LIMIT 1", array( 
			'id' => $whereid
			));
			return count($result) > 0 ? $result[0] : null;
		}
		
		
		public function getlastrowwhereand($col,$where,$whereid,$and,$andid,$order) {
			$result = $this->db->select("SELECT * FROM `$col` WHERE `$where` = :id AND `$and` = :aid ORDER BY `$order` DESC LIMIT 1", array( 
			'id' => $whereid,
			'aid' => $andid,
			));
			return count($result) > 0 ? $result[0] : null;
		}
		
		public function getwheredatefilter($col,$where,$start_date,$end_date){
			$sql = $this->db->select("SELECT * FROM `$col` WHERE date($where) BETWEEN :start_date AND :end_date ORDER BY `$where` DESC", array( 
			'start_date' => $start_date,
			'end_date' => $end_date,
			));
			return $sql;
		}
		
		public function getwhereanddatefilter($col,$where,$whereid,$between,$start_date,$end_date)
		{
			$sql = $this->db->select("SELECT * FROM `$col` WHERE `$where` = :whereid AND `$between` BETWEEN :start_date AND :end_date ORDER BY `$between` DESC", array( 
			'whereid' => $whereid,
			'start_date' => $start_date,
			'end_date' => $end_date,
			));
			return $sql;
			
		}
		
		public function getsumwhereanddatefilter($sum,$col,$where,$whereid,$between,$start_date,$end_date)
		{
			$sql = $this->db->select("SELECT sum($sum) FROM `$col` WHERE `$where` = :whereid AND `$between` BETWEEN :start_date AND :end_date ORDER BY `$between` DESC", array(
			'whereid' => $whereid,
			'start_date' => $start_date,
			'end_date' => $end_date,
			));
			return $sql;
			
		}
		
		public function getsumwhereandjoin($type,$start_date,$end_date)
		{
			$sql = $this->db->select("SELECT sum(pos_transactions.transaction_amount) AS `total_amount`
			FROM `pos_stock_adjustment`, `pos_transactions`
			WHERE `pos_stock_adjustment`.`stock_adjustment_id` = `pos_transactions`.`adjustment_id` AND `pos_stock_adjustment`.`type` = :id AND date(`pos_stock_adjustment`.`date`) BETWEEN :start AND :end ", array(
			'id' => $type,
			'start' => $start_date,
			'end' => $end_date,
			));
			return count($sql) > 0 ? $sql[0] : null;
			
		}
		
		public function getsumdatefilter($sum,$col,$between,$start_date,$end_date) {
			$query = $this->db->prepare("SELECT sum($sum) FROM `$col` WHERE $between BETWEEN ? AND ? ORDER BY `$between` DESC");
			$query->bindValue(1, $start_date);
			$query->bindValue(2, $end_date);
			try{ $query->execute();     
				$rows =  $query->fetch();
				return $rows[0];
				
			} catch (PDOException $e){die($e->getMessage());}  
		}
		
		public function getsumdatefilterdate($sum,$col,$between,$start_date,$end_date) {
			$query = $this->db->prepare("SELECT sum($sum) FROM `$col` WHERE date($between) BETWEEN ? AND ? ORDER BY `$between` DESC");
			$query->bindValue(1, $start_date);
			$query->bindValue(2, $end_date);
			try{ $query->execute();     
				$rows =  $query->fetch();
				return $rows[0];
				
			} catch (PDOException $e){die($e->getMessage());}  
		}
		
		public function getsumwheredatefilter($sum,$col,$where,$whereid,$between,$start_date,$end_date) {
			$query = $this->db->prepare("SELECT sum($sum) FROM `$col` WHERE `$where` = ? AND $between BETWEEN ? AND ? ORDER BY `$between` DESC");
			$query->bindValue(1, $whereid);
			$query->bindValue(2, $start_date);
			$query->bindValue(3, $end_date);
			try{ $query->execute();
				$rows =  $query->fetch();
				return $rows[0];
				
			} catch (PDOException $e){die($e->getMessage());}
		}
		
		public function getsumwheredatefilterdate($sum,$col,$where,$whereid,$between,$start_date,$end_date) {
			$query = $this->db->prepare("SELECT sum($sum) FROM `$col` WHERE `$where` = ? AND date($between) BETWEEN ? AND ? ORDER BY `$between` DESC");
			$query->bindValue(1, $whereid);
			$query->bindValue(2, $start_date);
			$query->bindValue(3, $end_date);
			try{ $query->execute();
				$rows =  $query->fetch();
				return $rows[0];
				
			} catch (PDOException $e){die($e->getMessage());}
		}
		
		public function getsumtotalbywhereandand($col,$sum,$where,$id,$and,$andid,$and1,$andid1) {
			$query = $this->db->prepare("SELECT sum($sum) FROM `$col` WHERE `$where` = ? AND `$and` = ? AND `$and1` = ? ");
			$query->bindValue(1, $id);
			$query->bindValue(2, $andid);
			$query->bindValue(3, $andid1);
			try{ $query->execute();     
				$rows =  $query->fetch();
				return $rows[0];
				
			} catch (PDOException $e){die($e->getMessage());}  
		}
		
		public function getsumtotalbywhereand($col,$sum,$where,$id,$and,$andid) {
			$query = $this->db->prepare("SELECT sum($sum) FROM `$col` WHERE `$where` = ? AND `$and` = ? ");
			$query->bindValue(1, $id);
			$query->bindValue(2, $andid);
			try{ $query->execute();     
				$rows =  $query->fetch();
				return $rows[0];
				
			} catch (PDOException $e){die($e->getMessage());}  
		}
		
		public function getsumtotalbywhere($col,$sum,$where,$id) {
			$query = $this->db->prepare("SELECT sum($sum) FROM `$col` WHERE `$where` = ? ");
			$query->bindValue(1, $id);
			try{ $query->execute();     
				$rows =  $query->fetch();
				return $rows[0];
				
			} catch (PDOException $e){die($e->getMessage());}  
		} 
		
		public function getsumtotalbymonthdays($col,$sum,$where,$id) {
			$query = $this->db->prepare("SELECT sum($sum) FROM `$col` WHERE `is_delete`= 'false' AND date($where) = ? ");
			$query->bindValue(1, $id);
			try{ $query->execute();     
				$rows =  $query->fetch();
				return $rows[0];
				
			} catch (PDOException $e){die($e->getMessage());}  
		}
		
		public function getsumtotal($col,$sum) {
			$query = $this->db->prepare("SELECT sum($sum) FROM `$col`");
			try{ $query->execute();     
				$rows =  $query->fetch();
				return $rows[0];
				
			} catch (PDOException $e){die($e->getMessage());}  
		}
		
		public function GetSearch($col,$selected = false,$search = false,$WhereAnd = false,$WhereNot = false,$ReturnType = true) {
			
			$searchQuery = ' ';
			$i = 0;
			if($search){
				if(count($search) > 0){
					foreach($search as $key => $value){
						if($i == 0){
							$searchQuery .= $key." LIKE :".$key;
							}else{
							$searchQuery .= " OR ".$key." LIKE :".$key;
						}
						$i++;
					}
					$searchQuery = " AND ( ".$searchQuery." ) ";
				}
			}
			
			$SelectedRows = ' ';
			if(count($selected) > 0){
				$i = 0;
				foreach($selected as $selectedV){
					if($i == 0){
						$SelectedRows .= " `".$selectedV."` ";
						}else{
						$SelectedRows .= ", `".$selectedV."` ";
					}
					$i++;
				}
				}else{
				$SelectedRows = " * ";
			}
			
			$WhereAndDetails = ' ';
			if($WhereAnd){
				if(count($WhereAnd) > 0){
					foreach($WhereAnd as $key => $value){
						if($value != null){
							$WhereAndDetails .= " AND `".$key."` = :".$key." ";
						}
					}
				}
			}
			
			$WhereNotDetails = ' ';
			if($WhereNot){
				if(count($WhereNot) > 0){
					foreach($WhereNot as $key => $value){
						if($value != null){
							$WhereNotDetails .= " AND `".$key."` != :".$key." ";
						}
					}
				}
			}
			
			$sql = "SELECT $SelectedRows  FROM `$col` WHERE 1 ".$WhereAndDetails.$WhereNotDetails.$searchQuery;
			$sth = $this->db->prepare($sql);
			$sth->bindValue(":limits", (int) $rowperpage, PDO::PARAM_INT);
			$sth->bindValue(":offsets", (int) $offsets, PDO::PARAM_INT);
			
			if($searchValues != ''){
				if($search){
					if(count($search) > 0){
						foreach($search as $key => $value){
							$sth->bindValue(":$key", "%".$value."%");
						}
					}
				}
			}
			
			if($WhereAnd){
				if(count($WhereAnd) > 0){
					foreach($WhereAnd as $key => $value){
						if($value != null){
							$sth->bindValue(":$key", $value);
						}
					}
				}
			}
			
			if($WhereNot){
				if(count($WhereNot) > 0){
					foreach($WhereNot as $key => $value){
						if($value != null){
							$query->bindValue(":$key", $value);
						}
					}
				}
			}
			
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			if($ReturnType){
				return $result;
				}else{
				return count($result) > 0 ? $result[0] : null;
			}
			
		}
		
		public function GetSum($col,$selected = false,$WhereAnd = false,$Between = false,$WhereNot = false) {
			
			$BetweenQuery = null;
			if($Between){
				if($Between[3]){
					$BetweenQuery = " AND date(".$Between[0].") BETWEEN :start AND :end ";
					}else{
					$BetweenQuery = " AND `".$Between[0]."` BETWEEN :start AND :end ";
				}
			}
			
			$WhereAndDetails = ' ';
			if($WhereAnd){
				if(count($WhereAnd) > 0){
					foreach($WhereAnd as $key => $value){
						if($value != null){
							$WhereAndDetails .= " AND `".$key."` = :".$key." ";
						}
					}
				}
			}
			
			$WhereNotDetails = ' ';
			if($WhereNot){
				if(count($WhereNot) > 0){
					foreach($WhereNot as $key => $value){
						if($value != null){
							$WhereNotDetails .= " AND `".$key."` != :".$key." ";
						}
					}
				}
			}
			
			$SelectedRows = ' ';
			if($selected){
				if(count($selected) > 0){
					$i = 0;
					foreach($selected as $selectedV){
						if($i == 0){
							$SelectedRows .= " sum(".$selectedV.") AS `".$selectedV."` ";
							}else{
							$SelectedRows .= ", sum(".$selectedV.") AS `".$selectedV."` ";
						}
						$i++;
					}
				}
			}
			
			$query = $this->db->prepare("SELECT $SelectedRows FROM `$col` WHERE 1".$WhereAndDetails.$WhereNotDetails.$BetweenQuery);
			
			if($WhereAnd){
				if(count($WhereAnd) > 0){
					foreach($WhereAnd as $key => $value){
						if($value != null){
							$query->bindValue(":$key", $value);
						}
					}
				}
			}
			
			if($Between){
				if(count($Between) > 0){
					$query->bindValue(":start", $Between[1]);
					$query->bindValue(":end", $Between[2]);
				}
			}
			
			if($WhereNot){
				if(count($WhereNot) > 0){
					foreach($WhereNot as $key => $value){
						if($value != null){
							$query->bindValue(":$key", $value);
						}
					}
				}
			}
			
			try{ $query->execute();     
				$rows =  $query->fetch(PDO::FETCH_ASSOC);
				return $rows;
				} catch (PDOException $e){
				die($e->getMessage());
			}  
			
		}
		
		public function GetFilterData($data,$col,$selected = array(),$search = array(),$WhereAnd = array(),$WhereNot = array(),$selectRaw='',$whereRaw='') {
			$draw = $data['draw'];
			$offsets = $data['start'];
			$rowperpage = $data['length'];
			$columnIndex = $data['order'][0]['column'];
			$columnName = $data['columns'][$columnIndex]['data'];
			$columnSortOrder = $data['order'][0]['dir'];
			$searchValues = $data['search']['value'];
			
			
			$LimitPage = " LIMIT :limits OFFSET :offsets ";
			
			$OrderBy = " ORDER BY $columnName $columnSortOrder ";
			
			
			
			$BetweenQuery = null;
			if(isset($data['between_action'])){
				if(isset($data['date_range']) && $data['date_range'] == "true"){
					$BetweenQuery = " AND date(".$data['between_action'].") BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
					}else{
					$BetweenQuery = " AND `".$data['between_action']."` BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
				}
			}
			
			$searchQuery = ' ';
			$searchCountQuery = ' ';
			if($searchValues != ''){
				$i = 0;
				if(count($search) > 0){
					foreach($search as $SearchV){
						if($i == 0){
							$searchQuery .= $SearchV." LIKE :".$SearchV;
							$searchCountQuery .= $SearchV." LIKE '%".$searchValues."%' ";
							}else{
							$searchQuery .= " OR ".$SearchV." LIKE :".$SearchV;
							$searchCountQuery .= " OR ".$SearchV." LIKE '%".$searchValues."%' ";
						}
						$i++;
					}
					$searchQuery = " AND ( ".$searchQuery." ) ";
					$searchCountQuery = " AND ( ".$searchCountQuery." )";
				}
			}
			
			$SelectedRows = ' ';
			if(count($selected) > 0){
				$i = 0;
				foreach($selected as $selectedV){
					if($i == 0){
						$SelectedRows .= " `".$selectedV."` ";
						}else{
						$SelectedRows .= ", `".$selectedV."` ";
					}
					$i++;
				}
				}else{
				$SelectedRows = " * ";
			}
			
			$WhereAndDetails = ' ';
			$WhereAndDisplayuDetails = ' ';
			if(count($WhereAnd) > 0){
				foreach($WhereAnd as $key => $value){
					if($value != null){
						$WhereAndDetails .= " AND `".$key."` = :".$key." ";
						$WhereAndDisplayuDetails .= " AND `".$key."` = '".$value."' ";
					}
				}
			}
			
			$WhereNotDetails = ' ';
			$WhereNotDisplayuDetails = ' ';
			if(count($WhereNot) > 0){
				foreach($WhereNot as $key => $value){
					if($value != null){
						$WhereNotDetails .= " AND `".$key."` != :".$key." ";
						$WhereNotDisplayuDetails .= " AND `".$key."` != '".$value."' ";
					}
				}
			}
			
			$sql = "SELECT $SelectedRows $selectRaw  FROM `$col` WHERE 1 ".$WhereAndDetails.$WhereNotDetails.$searchQuery.$BetweenQuery.$OrderBy.$LimitPage.$whereRaw;
			$sth = $this->db->prepare($sql);
			
			$sth->bindValue(":limits", (int) $rowperpage, PDO::PARAM_INT);
			$sth->bindValue(":offsets", (int) $offsets, PDO::PARAM_INT);
			
			
			if($searchValues != ''){
				if(count($search) > 0){
					foreach ($search as $SearchV) {
						$sth->bindValue(":$SearchV", "%".$searchValues."%");
					}
				}
			}
			
			if(count($WhereAnd) > 0){
				foreach($WhereAnd as $key => $value){
					if($value != null){
						$sth->bindValue(":$key", $value);
					}
				}
			}
			
			if(count($WhereNot) > 0){
				foreach($WhereNot as $key => $value){
					if($value != null){
						$sth->bindValue(":$key", $value);
					}
				}
			}
			
			$sth->execute();
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			$iTotalDisplayRecords = count($this->db->select("SELECT $SelectedRows  FROM `$col` WHERE 1 ".$WhereAndDisplayuDetails.$WhereNotDisplayuDetails.$searchCountQuery.$BetweenQuery));
			$iTotalRecords = count($this->db->select("SELECT $SelectedRows  FROM `$col` WHERE 1 ".$WhereAndDisplayuDetails.$WhereNotDisplayuDetails));
			
			return array("draw" => $draw, "iTotalRecords" => $iTotalRecords, "iTotalDisplayRecords" => $iTotalDisplayRecords, "Data" => $data);
		}
		
		public function GetFilterDataJoint($data,$col = array(),$selected = array(),$search = array(),$WhereAnd = array(),$WhereJoint = array(),$WhereNot = array(),$selectRaw= '') {
			$draw = $data['draw'];
			$offsets = $data['start'];
			$rowperpage = $data['length'];
			$columnIndex = $data['order'][0]['column'];
			$columnName = $data['columns'][$columnIndex]['data'];
			$columnSortOrder = $data['order'][0]['dir'];
			$searchValues = $data['search']['value'];
			
			
			if(strpos($columnName, '/') !== false){
				list($ColName, $ColVelue) = explode("/", $columnName , 2);
				$OrderBy = " ORDER BY `".$ColName."`.`".$ColVelue."` $columnSortOrder ";
				}else{
				$OrderBy = " ORDER BY `".$col[0]."`.`".$columnName."` $columnSortOrder ";
			}
			
			
			$LimitPage = " LIMIT :limits OFFSET :offsets";
			
			
			
			
			$BetweenQuery = null;
			if(isset($data['between_action'])){
				if(isset($data['date_range']) && $data['date_range'] == "true"){
					if(strpos($data['between_action'], '/') !== false){
						list($KeyCol, $KeyVelue) = explode("/", $data['between_action'] , 2);
						$BetweenQuery = " AND date(`".$KeyCol."`.`".$KeyVelue."`) BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
						}else{
						$BetweenQuery = " AND date(`".$col[0]."`.`".$data['between_action']."`) BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
					}
					}else{
					if(strpos($data['between_action'], '/') !== false){
						list($KeyCol, $KeyVelue) = explode("/", $data['between_action'] , 2);
						$BetweenQuery = " AND `".$KeyCol."`.`".$KeyVelue."` BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
						}else{
						$BetweenQuery = " AND `".$col[0]."`.`".$data['between_action']."` BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
					}
				}
			}
			
			$searchQuery = ' ';
			$searchCountQuery = ' ';
			if($searchValues != ''){
				$i = 0;
				if(count($search) > 0){
					foreach($search as $SearchV){
						$keyV = str_replace(".", "", $SearchV);
						list($KeyCol, $KeyVelue) = explode(".", $SearchV , 2);
						if($i == 0){
							$searchQuery .= " `".$KeyCol."`.`".$KeyVelue."` LIKE :".$keyV;
							$searchCountQuery .= " `".$KeyCol."`.`".$KeyVelue."` LIKE '%".$searchValues."%' ";
							}else{
							$searchQuery .= " OR `".$KeyCol."`.`".$KeyVelue."` LIKE :".$keyV;
							$searchCountQuery .= " OR `".$KeyCol."`.`".$KeyVelue."` LIKE '%".$searchValues."%' ";
						}
						$i++;
					}
					$searchQuery = " AND ( ".$searchQuery." ) ";
					$searchCountQuery = " AND ( ".$searchCountQuery." )";
				}
			}
			
			$SelectedRows = ' ';
			if(count($selected) > 0){
				$i = 0;
				foreach($selected as $selectedV){
					list($KeyCol, $KeyVelue) = explode(".", $selectedV , 2);
					if($i == 0){
						$SelectedRows .= " `".$KeyCol."`.`".$KeyVelue."` ";
						}else{
						$SelectedRows .= ", `".$KeyCol."`.`".$KeyVelue."` ";
					}
					$i++;
				}
			}
			
			$WhereAndDetails = ' ';
			$WhereAndDisplayuDetails = ' ';
			if(count($WhereAnd) > 0){
				foreach($WhereAnd as $key => $value){
					$keyV = str_replace(".", "", $key);
					list($KeyCol, $KeyVelue) = explode(".", $key , 2);
					if($value != null){
						$WhereAndDetails .= " AND `".$KeyCol."`.`".$KeyVelue."` = :".$keyV." ";
						$WhereAndDisplayuDetails .= " AND `".$KeyCol."`.`".$KeyVelue."` = '".$value."' ";
					}
				}
			}
			
			$WhereNotDetails = ' ';
			$WhereNotDisplayuDetails = ' ';
			if(count($WhereNot) > 0){
				foreach($WhereNot as $key => $value){
					$keyV = str_replace(".", "", $key);
					list($KeyCol, $KeyVelue) = explode(".", $key , 2);
					if($value != null){
						$WhereNotDetails .= " AND `".$KeyCol."`.`".$KeyVelue."` != :".$keyV." ";
						$WhereNotDisplayuDetails .= " AND `".$KeyCol."`.`".$KeyVelue."` != '".$value."' ";
					}
				}
			}
			
			$WhereJointDetails = ' ';
			if(count($WhereJoint) > 0){
				foreach($WhereJoint as $key => $value){
					$keyV = str_replace(".", "", $key);
					list($KeyCol1, $KeyVelue1) = explode(".", $key , 2);
					list($KeyCol2, $KeyVelue2) = explode(".", $value , 2);
					if($value != null){
						$WhereJointDetails .= " AND `".$KeyCol1."`.`".$KeyVelue1."` = `".$KeyCol2."`.`".$KeyVelue2."` ";
					}
				}
			}
			
			$ColQuery = ' ';
			if(count($col) > 0){
				$i = 0;
				foreach($col as $value){
					if($i == 0){
						$ColQuery .= " `".$value."` ";
						}else{
						$ColQuery .= ", `".$value."` ";
					}
					$i++;
				}
			}
			
			$sql = "SELECT $SelectedRows $selectRaw  FROM $ColQuery WHERE 1 ".$WhereAndDetails.$WhereNotDetails.$WhereJointDetails.$searchQuery.$BetweenQuery.$OrderBy.$LimitPage;
			$sth = $this->db->prepare($sql);
			
			$sth->bindValue(":limits", (int) $rowperpage, PDO::PARAM_INT);
			$sth->bindValue(":offsets", (int) $offsets, PDO::PARAM_INT);
			
			
			if($searchValues != ''){
				if(count($search) > 0){
					foreach ($search as $SearchV) {
						$SearchV = str_replace(".", "", $SearchV);
						$sth->bindValue(":$SearchV", "%".$searchValues."%");
					}
				}
			}
			
			if(count($WhereAnd) > 0){
				foreach($WhereAnd as $key => $value){
					$key = str_replace(".", "", $key);
					if($value != null){
						$sth->bindValue(":$key", $value);
					}
				}
			}
			
			if(count($WhereNot) > 0){
				foreach($WhereNot as $key => $value){
					$key = str_replace(".", "", $key);
					if($value != null){
						$sth->bindValue(":$key", $value);
					}
				}
			}
			
			$sth->execute();
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			$iTotalDisplayRecords = count($this->db->select("SELECT $SelectedRows  FROM $ColQuery WHERE 1 ".$WhereAndDisplayuDetails.$WhereNotDisplayuDetails.$WhereJointDetails.$searchCountQuery.$BetweenQuery));
			$iTotalRecords = count($this->db->select("SELECT $SelectedRows  FROM $ColQuery WHERE 1".$WhereAndDisplayuDetails.$WhereNotDisplayuDetails.$WhereJointDetails));
			
			return array("draw" => $draw, "iTotalRecords" => $iTotalRecords, "iTotalDisplayRecords" => $iTotalDisplayRecords, "Data" => $data);
		}
		
		public function GetFilterLeftJointData($data,$col = array(),$selected = array(),$search = array(),$WhereAnd = array(),$WhereJoint = array(),$WhereNot = array(),$SelectSum = '',$leftJoinTable = '',$jointOn = '') {
			$draw = $data['draw'];
			$offsets = $data['start'];
			$rowperpage = $data['length'];
			$columnIndex = $data['order'][0]['column'];
			$columnName = $data['columns'][$columnIndex]['data'];
			$columnSortOrder = $data['order'][0]['dir'];
			$searchValues = $data['search']['value'];
			
			
			if(strpos($columnName, '/') !== false){
				list($ColName, $ColVelue) = explode("/", $columnName , 2);
				$OrderBy = " ORDER BY `".$ColName."`.`".$ColVelue."` $columnSortOrder ";
				}else{
				$OrderBy = " ORDER BY `".$col[0]."`.`".$columnName."` $columnSortOrder ";
			}
			
			
			$LimitPage = " LIMIT :limits OFFSET :offsets";
			
			
			
			
			$BetweenQuery = null;
			if(isset($data['between_action'])){
				if(isset($data['date_range']) && $data['date_range'] == "true"){
					if(strpos($data['between_action'], '/') !== false){
						list($KeyCol, $KeyVelue) = explode("/", $data['between_action'] , 2);
						$BetweenQuery = " AND date(`".$KeyCol."`.`".$KeyVelue."`) BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
						}else{
						$BetweenQuery = " AND date(`".$col[0]."`.`".$data['between_action']."`) BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
					}
					}else{
					if(strpos($data['between_action'], '/') !== false){
						list($KeyCol, $KeyVelue) = explode("/", $data['between_action'] , 2);
						$BetweenQuery = " AND `".$KeyCol."`.`".$KeyVelue."` BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
						}else{
						$BetweenQuery = " AND `".$col[0]."`.`".$data['between_action']."` BETWEEN '".$data['from_data']."' AND '".$data['to_data']."' ";
					}
				}
			}
			
			$searchQuery = ' ';
			$searchCountQuery = ' ';
			if($searchValues != ''){
				$i = 0;
				if(count($search) > 0){
					foreach($search as $SearchV){
						$keyV = str_replace(".", "", $SearchV);
						list($KeyCol, $KeyVelue) = explode(".", $SearchV , 2);
						if($i == 0){
							$searchQuery .= " `".$KeyCol."`.`".$KeyVelue."` LIKE :".$keyV;
							$searchCountQuery .= " `".$KeyCol."`.`".$KeyVelue."` LIKE '%".$searchValues."%' ";
							}else{
							$searchQuery .= " OR `".$KeyCol."`.`".$KeyVelue."` LIKE :".$keyV;
							$searchCountQuery .= " OR `".$KeyCol."`.`".$KeyVelue."` LIKE '%".$searchValues."%' ";
						}
						$i++;
					}
					$searchQuery = " AND ( ".$searchQuery." ) ";
					$searchCountQuery = " AND ( ".$searchCountQuery." )";
				}
			}
			
			$SelectedRows = ' ';
			if(count($selected) > 0){
				$i = 0;
				foreach($selected as $selectedV){
					list($KeyCol, $KeyVelue) = explode(".", $selectedV , 2);
					if($i == 0){
						$SelectedRows .= " `".$KeyCol."`.`".$KeyVelue."` ";
						}else{
						$SelectedRows .= ", `".$KeyCol."`.`".$KeyVelue."` ";
					}
					$i++;
				}
			}
			
			$WhereAndDetails = ' ';
			$WhereAndDisplayuDetails = ' ';
			if(count($WhereAnd) > 0){
				foreach($WhereAnd as $key => $value){
					$keyV = str_replace(".", "", $key);
					list($KeyCol, $KeyVelue) = explode(".", $key , 2);
					if($value != null){
						$WhereAndDetails .= " AND `".$KeyCol."`.`".$KeyVelue."` = :".$keyV." ";
						$WhereAndDisplayuDetails .= " AND `".$KeyCol."`.`".$KeyVelue."` = '".$value."' ";
					}
				}
			}
			
			$WhereNotDetails = ' ';
			$WhereNotDisplayuDetails = ' ';
			if(count($WhereNot) > 0){
				foreach($WhereNot as $key => $value){
					$keyV = str_replace(".", "", $key);
					list($KeyCol, $KeyVelue) = explode(".", $key , 2);
					if($value != null){
						$WhereNotDetails .= " AND `".$KeyCol."`.`".$KeyVelue."` != :".$keyV." ";
						$WhereNotDisplayuDetails .= " AND `".$KeyCol."`.`".$KeyVelue."` != '".$value."' ";
					}
				}
			}
			
			$WhereJointDetails = ' ';
			if(count($WhereJoint) > 0){
				foreach($WhereJoint as $key => $value){
					$keyV = str_replace(".", "", $key);
					list($KeyCol1, $KeyVelue1) = explode(".", $key , 2);
					list($KeyCol2, $KeyVelue2) = explode(".", $value , 2);
					if($value != null){
						$WhereJointDetails .= " AND `".$KeyCol1."`.`".$KeyVelue1."` = `".$KeyCol2."`.`".$KeyVelue2."` ";
					}
				}
			}
			
			$ColQuery = ' ';
			if(count($col) > 0){
				$i = 0;
				foreach($col as $value){
					if($i == 0){
						$ColQuery .= " `".$value."` ";
						}else{
						$ColQuery .= ", `".$value."` ";
					}
					$i++;
				}
			}
			
			$sql = "SELECT ".$SelectedRows.$SelectSum." FROM $ColQuery WHERE 1 ".$WhereAndDetails.$WhereNotDetails.$WhereJointDetails.$searchQuery.$BetweenQuery.$OrderBy.$LimitPage;
			$sth = $this->db->prepare($sql);
			
			$sth->bindValue(":limits", (int) $rowperpage, PDO::PARAM_INT);
			$sth->bindValue(":offsets", (int) $offsets, PDO::PARAM_INT);
			
			
			if($searchValues != ''){
				if(count($search) > 0){
					foreach ($search as $SearchV) {
						$SearchV = str_replace(".", "", $SearchV);
						$sth->bindValue(":$SearchV", "%".$searchValues."%");
					}
				}
			}
			
			if(count($WhereAnd) > 0){
				foreach($WhereAnd as $key => $value){
					$key = str_replace(".", "", $key);
					if($value != null){
						$sth->bindValue(":$key", $value);
					}
				}
			}
			
			if(count($WhereNot) > 0){
				foreach($WhereNot as $key => $value){
					$key = str_replace(".", "", $key);
					if($value != null){
						$sth->bindValue(":$key", $value);
					}
				}
			}
			
			$sth->execute();
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			$iTotalDisplayRecords = count($this->db->select("SELECT $SelectedRows  FROM $ColQuery WHERE 1 ".$WhereAndDisplayuDetails.$WhereNotDisplayuDetails.$WhereJointDetails.$searchCountQuery.$BetweenQuery));
			$iTotalRecords = count($this->db->select("SELECT $SelectedRows  FROM $ColQuery WHERE 1".$WhereAndDisplayuDetails.$WhereNotDisplayuDetails.$WhereJointDetails));
			
			return array("draw" => $draw, "iTotalRecords" => $iTotalRecords, "iTotalDisplayRecords" => $iTotalDisplayRecords, "Data" => $data);
		}
		
		
		
		public function GetSoftwareSubscribe($data,$user_id,$agent_id = null,$userData)
		{
			if(isset($data['software_id'])){
				
				if($this->root->select("SELECT `user_id`,`software_variation_id`,`subscribe_type` FROM `as_subscribe` WHERE `user_id` = :uid AND `subscribe_type` = 'software' AND `software_variation_id` = :sid", array("uid" => $user_id, "sid" => $data['software_variation_id']))){
					respond(array(
					"status" => "exits",
					"message" => "Already Subscribed"
					));
					return;
					}else{
					$GetSoftwareDetail = $this->root->select("SELECT * FROM `as_software` WHERE `software_id` = :pid", array("pid" => $data['software_id']));
					$GetSoftwareDetails = count($GetSoftwareDetail) > 0 ? $GetSoftwareDetail[0] : null;
					
					$result = $this->root->select("SELECT * FROM `as_software_variation` WHERE `software_id` = :sid AND `software_variation_id` = :id", array( 'id' => $data['software_variation_id'], 'sid' => $data['software_id']));
					$getVariation = count($result) > 0 ? $result[0] : null;
					
					$totalAmount = $getVariation['software_variation_price'] * $data['billing_period'];
					$totalAmountWithSetupFee = $totalAmount + $getVariation['software_setup_fee'];
					
					$currentFund = $this->currentFund($user_id);
					
					$this->InsertUpdatePosRequirements($data,$userData);
					
					if($currentFund['current_balance'] < $totalAmountWithSetupFee && $getVariation['software_subscribe_in_advance']){
						respond(array(
						"status" => "exits",
						"message" => "You do not have sufficient balance for subscribe"
						));
						return;
					}
					
					$SubscribeData = array(
					"user_id"      => $user_id,
					"agent_id"      => $agent_id,
					"software_id"      => $data['software_id'],
					"software_variation_id"      => $data['software_variation_id'],
					"subscribe_for"      => 'Software Bill',
					"subscribe_type"      => 'software',
					"subscribe_date"      => date("Y-m-d H:i:s"),
					'subscribe_activation_date'	=>	date("Y-m-d H:i:s"),
					"subscribe_amount"      => $totalAmount,
					"subscribe_payment_terms"      => 'monthly',
					"subscribe_payment_terms_value"      => $data['billing_period'],
					"subscribe_status"   => "inactive",
					"created_at" => date("Y-m-d H:i:s")
					);
					
					$this->root->insert("as_subscribe", $SubscribeData);
					$SoftwareSubscribeId = $this->root->lastInsertId();
					
					if($GetSoftwareDetails['software_billing_type'] == 'postpaid'){
						$SubscribeData["subscribe_status"] = 'active';
						}elseif($GetSoftwareDetails['software_billing_type'] == 'prepaid'){
						if($currentFund['current_balance'] >= $totalAmountWithSetupFee){
							$date = new DateTime();
							$date->modify('+ '.$data['billing_period'].' month');
							$softwareSubscribeEndDate = $date->format('Y-m-d H:i:s');
							
							$SoftwareInvoiceData = array(
							"user_id"      => $user_id,
							"agent_id"      => $agent_id,
							"subscribe_id"      => $SoftwareSubscribeId,
							"invoice_amount"      => $totalAmount,
							"invoice_transaction_id"      => gettoken(10),
							"subscribe_month"      => $data['billing_period'],
							"invoice_type"      => 'bill',
							"subscribe_start_date"      => date("Y-m-d H:i:s"),
							"subscribe_end_date" => $softwareSubscribeEndDate,
							"invoice_paid_date" => date("Y-m-d H:i:s"),
							"invoice_status"   => "paid",
							"created_at" => date("Y-m-d H:i:s")
							);
							
							$this->root->insert("as_invoices", $SoftwareInvoiceData);
							$invoiceId = $this->root->lastInsertId();
							
							$this->GetAgentSubscribeCommissionPayment($invoiceId);
							
							$SubscribeData["subscribe_status"] = 'active';
							}else{
							$SubscribeData["subscribe_status"] = 'expire';
						}
					}
					
					if($getVariation['software_setup_fee'] > 0){
						$SubscribeFeeInvoiceData = array(
						"user_id"      => $user_id,
						"agent_id"      => $agent_id,
						"subscribe_id"      => $SoftwareSubscribeId,
						"invoice_amount"      => $getVariation['software_setup_fee'],
						"invoice_transaction_id"      => gettoken(10),
						"subscribe_month"      => 'onetime',
						"invoice_type"      => 'setup_fee',
						"subscribe_start_date"      => date("Y-m-d H:i:s"),
						"subscribe_end_date" => date("Y-m-d H:i:s"),
						"invoice_paid_date" => date("Y-m-d H:i:s"),
						"invoice_status"   => "paid",
						"invoice_details"   => "Software Setup Fee ".$getVariation['software_setup_fee']." Taka",
						"created_at" => date("Y-m-d H:i:s")
						);
						
						$this->root->insert("as_invoices", $SubscribeFeeInvoiceData);
					}
					
					$this->root->update("as_subscribe" , array( 
					'subscribe_status'	=>	$SubscribeData["subscribe_status"],
					'subscribe_reminder'	=>	null
					), "`subscribe_id` = :id ", array("id" => $SoftwareSubscribeId));
					
					if(IS_ONLINE){
						$db_name = CPANEL_PREFIX.$data['domain_name'];
						}else{
						$db_name = 'test';
						$data['domain_name'] = 'local';
					}
					$this->GetSubdomainInsert($user_id,$data['domain_name'],CPANEL_HOST,$db_name,'inactive');
					$this->userPluginsJsonUpdate($user_id);
				}
				}else{
				respond(array(
				"status" => "exits",
				"message" => "Failed Try Again"
				));
			}
			
			respond(array(
			"status" => "success",
			"message" => "Subscription Completed"
			));
		}
		
		public function subscribeStatusCheck($userid,$agentId){
			$checkSubsActiveAccounts = $this->root->select("SELECT `user_id`,`subscribe_status`,`plugins_id`,`subscribe_type`,`subscribe_id`,`subscribe_reminder`,`software_id`,`subscribe_activation_date`,`subscribe_amount` FROM `as_subscribe` WHERE `user_id` = :uid AND `subscribe_status` != 'inactive' AND `subscribe_status` != 'cancel'AND `subscribe_status` != 'return'", 
			array("uid" => $userid));
			if($checkSubsActiveAccounts){
				$sendReminderCheck = false;
				$ReminderText = "";
				$totalAmount = 0;
				$LastWarning = false;
				$currentFund = $this->currentFund($userid);
				foreach($checkSubsActiveAccounts as $subscribeDetails){
					$invoiceCheck = $this->root->select("SELECT `invoice_id`,`subscribe_id`,`invoice_status`,`subscribe_end_date` FROM `as_invoices` WHERE `subscribe_id` = :id AND `invoice_type` = 'bill' AND `invoice_status` = 'paid' ORDER BY `invoice_id` DESC LIMIT 1", 
					array( 'id' => $subscribeDetails['subscribe_id']));
					$lastInvoice = count($invoiceCheck) > 0 ? $invoiceCheck[0] : null;
					if($subscribeDetails['subscribe_status'] == 'active'){
						$type = " ";
						if($subscribeDetails['subscribe_type'] == 'plugins'){
							
							$pluginsDetails = $this->root->select("SELECT `plugins_id`,`plugins_name`,`plugins_billing_type`,`plugins_billing`,`plugins_billing_value` FROM `as_plugins` WHERE `plugins_id` = :id", array( 'id' => $subscribeDetails['plugins_id']));
							// $type = " Addons: ";
							$title = $pluginsDetails[0]['plugins_name'];
							$checkBillingType = $pluginsDetails[0]['plugins_billing_type'];
							$invoiceData['billing_type'] = $pluginsDetails[0]['plugins_billing'];
							$invoiceData['billing_value'] = $pluginsDetails[0]['plugins_billing_value'];
							
							}elseif($subscribeDetails['subscribe_type'] == 'software'){
							
							$SoftwareDetails = $this->root->select("SELECT `software_id`,`software_title`,`software_billing_type`,`software_billing`,`software_billing_value` FROM `as_software` WHERE `software_id` = :id", array( 'id' => $subscribeDetails['software_id']));
							// $type = " Software: ";
							$title = $SoftwareDetails[0]['software_title'];
							$checkBillingType = $SoftwareDetails[0]['software_billing_type'];
							$invoiceData['billing_type'] = $SoftwareDetails[0]['software_billing'];
							$invoiceData['billing_value'] = $SoftwareDetails[0]['software_billing_value'];
							
							}elseif($subscribeDetails['subscribe_type'] == 'service'){
							// $type = " Service: ";
							$title = "";
							$checkBillingType = "postpaid";
							$invoiceData['billing_type'] = 'monthly';
							$invoiceData['billing_value'] = '1';
						}
						$subdatecheck = [];
						if(!$lastInvoice){
							if($checkBillingType == 'postpaid'){
								$date = new DateTime($subscribeDetails['subscribe_activation_date']);
								$date->modify('+ 1 month');
								$subdatecheck['subscribe_end_date'] = $date->format('Y-m-d H:i:s');
								}else{
								$this->root->update("as_subscribe" , array( 
								'subscribe_status'	=>	'expire',
								'subscribe_reminder'	=>	null
								), "`subscribe_id` = :id ", array("id" => $subscribeDetails['subscribe_id']));
							}
							}else{
							$subdatecheck['subscribe_end_date'] = $lastInvoice['subscribe_end_date'];
						}
						
						
						$date = new DateTime($subdatecheck['subscribe_end_date']);
						$date->modify('- 7 days');
						$day7notify = $date->format('Y-m-d');
						
						$date = new DateTime($subdatecheck['subscribe_end_date']);
						$date->modify('- 5 days');
						$day5notify = $date->format('Y-m-d');
						
						$date = new DateTime($subdatecheck['subscribe_end_date']);
						$date->modify('- 3 days');
						$day3notify = $date->format('Y-m-d');
						
						$date = new DateTime($subdatecheck['subscribe_end_date']);
						$date->modify('- 1 days');
						$day1notify = $date->format('Y-m-d');
						
						$cdate = new DateTime($subdatecheck['subscribe_end_date']);
						$currentDate = $cdate->format('d/m/Y');
						
						$invoiceData['invoice_amount'] = $subscribeDetails['subscribe_amount'];
						$invoiceData['subscribe_id'] = $subscribeDetails['subscribe_id'];
						
						if($cdate->format('Y-m-d') <= date("Y-m-d")){
							if(!$this->getSubscribeInvoiceGenerate($invoiceData,$userid,$agentId)){
								$subscribeDetails['subscribe_status'] = 'expire';
								$subscribeDetails['subscribe_reminder'] = null;
								$sendReminderCheck = true;
								$ReminderText .= $type.$title." EXP: ".$currentDate;
								$totalAmount += $subscribeDetails['subscribe_amount'];
								$LastWarning = true;
							}
							
							}elseif($day1notify == date("Y-m-d") && $subscribeDetails['subscribe_reminder'] != 1){
							$subscribeDetails['subscribe_reminder'] = 1;
							$sendReminderCheck = true;
							$ReminderText .= $type.$title." EXP: ".$currentDate;
							$totalAmount += $subscribeDetails['subscribe_amount'];
							}elseif($day3notify == date("Y-m-d") && $subscribeDetails['subscribe_reminder'] != 3){
							$subscribeDetails['subscribe_reminder'] = 3;
							$sendReminderCheck = true;
							$ReminderText .= $type.$title." EXP: ".$currentDate;
							$totalAmount += $subscribeDetails['subscribe_amount'];
							}elseif($day5notify == date("Y-m-d") && $subscribeDetails['subscribe_reminder'] != 5){
							$subscribeDetails['subscribe_reminder'] = 5;
							$sendReminderCheck = true;
							$ReminderText .= $type.$title." EXP: ".$currentDate;
							$totalAmount += $subscribeDetails['subscribe_amount'];
							}elseif($day7notify == date("Y-m-d") && $subscribeDetails['subscribe_reminder'] != 7){
							$subscribeDetails['subscribe_reminder'] = 7;
							$sendReminderCheck = true;
							$ReminderText .= $type.$title." EXP: ".$currentDate;
							$totalAmount += $subscribeDetails['subscribe_amount'];
							
						}
						
						// $subscribeDetails['subscribe_reminder'] = null;
						$this->root->update("as_subscribe" , array( 
						'subscribe_status'	=>	$subscribeDetails['subscribe_status'],
						'subscribe_reminder'	=>	$subscribeDetails['subscribe_reminder']
						), "`subscribe_id` = :id ", array("id" => $subscribeDetails['subscribe_id']));
						$this->userPluginsJsonUpdate($userid);
						}elseif($lastInvoice && $subscribeDetails['subscribe_status'] == 'expire' && $lastInvoice['subscribe_end_date'] >= date("Y-m-d H:i:s")){
						$this->root->update("as_subscribe" , array( 
						'subscribe_status'	=>	'active',
						'subscribe_reminder'	=>	null
						), "`subscribe_id` = :id ", array("id" => $subscribeDetails['subscribe_id']));
						$this->userPluginsJsonUpdate($userid);
					}
					
					
				}
				if($sendReminderCheck){
					if($currentFund['current_balance'] < $totalAmount){
						if($LastWarning){
							$message =  "Your Appsowl Subscription Just Expired, Your Total Monthly Amount: ".$totalAmount." Expire list :".$ReminderText;
							}else{
							$message =  "Your Appsowl Subscription Total Monthly Amount: ".$totalAmount." Expire list :".$ReminderText;
						}
						
						$NotifyData = array(
						"user_id"      => $userid,
						"title"      => 'Payment Alert',
						"message"      => $message,
						"link"      => ROOT_WEBSITE_DOMAIN.'/history',
						"status"      => 'active',
						"notification_type"      => 'user',
						"created_at"      => date("Y-m-d H:i:s"),
						"updated_at" => date("Y-m-d H:i:s")
						);
						$this->root->insert("as_notification", $NotifyData);
					}				
				}
			}
		}
		
		public function getSubscribeInvoiceGenerate($data,$userId,$agent_id){
			$currentFund = $this->currentFund($userId);
			if($currentFund['current_balance'] >= $data['invoice_amount']){
				$date = new DateTime();
				if($data['billing_type'] == 'monthly'){
					$date->modify('+'.$data['billing_value'].'month');
					$data['subscribe_month'] = $data['billing_value'];
					}elseif($data['billing_type'] == 'yearly'){
					$date->modify('+'.$data['billing_value'].'year');
					$data['subscribe_month'] = $data['billing_value'] * 12;
					}elseif($data['billing_type'] == 'free'){
					$date->modify('+'.$data['billing_value'].'month');
					$data['subscribe_month'] = $data['billing_value'];
					}elseif($data['billing_type'] == 'onetime'){
					$date->modify('+'.$data['billing_value'].'month');
					$data['subscribe_month'] = $data['billing_value'];
				}
				
				$PluginsInvoiceData = array(
				"user_id"      => $userId,
				"agent_id"      => $agent_id,
				"subscribe_id"      => $data['subscribe_id'],
				"invoice_amount"      => $data['invoice_amount'],
				"invoice_transaction_id"      => gettoken(10),
				"invoice_type"      => 'bill',
				"subscribe_month"      => $data['subscribe_month'],
				"subscribe_start_date"      => date("Y-m-d H:i:s"),
				"subscribe_end_date" => $date->format('Y-m-d H:i:s'),
				"invoice_paid_date" => date("Y-m-d H:i:s"),
				"invoice_status"   => "paid",
				"created_at" => date("Y-m-d H:i:s")
				);
				
				$this->root->insert("as_invoices", $PluginsInvoiceData);
				
				$invoiceId = $this->root->lastInsertId();
				$this->GetAgentSubscribeCommissionPayment($invoiceId);
				
				$this->root->update("as_subscribe" , array( 
				'subscribe_status'	=>	'active',
				'subscribe_reminder'	=>	null
				), "`subscribe_id` = :id ", array("id" => $data['subscribe_id']));
				
				return true;
				}else{
				return false;
			}
			
		}
		
		public function GetAgentSubscribeCommissionPayment($invoice_id){
			
			$checksubscribes = $this->root->select("SELECT * FROM `as_invoices` WHERE `invoice_id` = :id AND `invoice_status` = 'paid'", array( 'id' => $invoice_id));
			$checksubscribe = count($checksubscribes) > 0 ? $checksubscribes[0] : null;
			
			if($checksubscribe){
				if($checksubscribe['agent_id'] != 'null'){
					
					
					$checkAgentCommissions = $this->root->select("SELECT * FROM `as_agent_commission` WHERE `agent_id` = :id ORDER BY `commission_id` DESC LIMIT 1", array( 'id' => $checksubscribe['agent_id']));
					$checkAgentCommission = count($checkAgentCommissions) > 0 ? $checkAgentCommissions[0] : null;
					
					if($checksubscribe['invoice_type'] == 'setup_fee'){
						$agent_commission = $checksubscribe['invoice_amount'] * $checkAgentCommission['setup_commission'] / 100;
						}elseif($checksubscribe['invoice_type'] == 'bill'){
						$agent_commission = $checksubscribe['invoice_amount'] * $checkAgentCommission['new_rate'] / 100;
					}
					
					$payment_details = "You have received ".$agent_commission." TK Commission From user id :".$checksubscribe['user_id'];
					
					if(!$this->root->select("SELECT `subscribe_invoice_id` FROM `as_agent_payment` WHERE `subscribe_invoice_id` = :id", array( 'id' => $invoice_id))){
						$this->root->insert('as_agent_payment',array(
						"user_id"      		=> 	$checksubscribe['user_id'],
						"agent_id"      	=> 	$checksubscribe['agent_id'],
						'subscribe_id'		=>	$checksubscribe['subscribe_id'],
						'subscribe_invoice_id'	=>	$invoice_id,
						'payment_type'		=>	"receive",
						'payment_date'		=>	date("Y-m-d H:i:s"),
						'payment_amount'	=>	$agent_commission,
						'payment_details'	=>	$payment_details,
						'payment_status'	=>	'paid'
						));
						}else{
						$this->root->update("as_agent_payment" ,  array( 
						'payment_date'		=>	date("Y-m-d H:i:s"),
						'payment_amount'	=>	$agent_commission,
						'payment_details'	=>	$payment_details,
						'payment_status'	=>	'paid'
						), "`subscribe_invoice_id` = :id ", array("id" => $invoice_id));
					}
				}
			} 
		}
		
		public function GetSubdomainInsert($userid,$sub_domain,$db_host,$db_name,$db_status)
		{
			$DomainChecks = $this->root->select("SELECT * FROM `as_sub_domain` WHERE `user_id` = :id", array("id" => $userid));
			$DomainCheck = count($DomainChecks) > 0 ? $DomainChecks[0] : null;
			
			$rootDomain = ROOT_DOMAIN_LOCAL;
			if(IS_ONLINE){
				$rootDomain = ROOT_DOMAIN;
			}
			
			if($DomainCheck){
				$this->root->update("as_sub_domain" ,  array( 
				'basedir'		=> BASE_URID,
				'db_host'		=> $db_host,
				'db_name'		=> $db_name,
				'db_status'		=> $db_status,
				), "`domain_id` = :id ", array("id" => $DomainCheck['domain_id']));
				$last_doamin_id = $DomainCheck['domain_id'];
				}else{
				$this->root->insert("as_sub_domain",array(
				'user_id'		=> $userid,
				'sub_domain'	=> $sub_domain,
				'root_domain'	=> $rootDomain,
				'basedir'		=> BASE_URID,
				'db_host'		=> $db_host,
				'db_name'		=> $db_name,
				'db_status'		=> $db_status,
				));
				$last_doamin_id = $this->root->lastInsertId();
			}
			
			$this->root->update("as_user_details" ,  array( 
			"domain_id"=> $last_doamin_id
			), "`user_id` = :id ", array("id"      => $userid));
			
			return $last_doamin_id;
		}
		
		public function PosInstallation($userId)
		{
			$getsubdomains = $this->root->select("SELECT * FROM `as_sub_domain` WHERE `user_id` = :id", array("id" => $userId));
			$getchecksubdomain = count($getsubdomains) > 0 ? $getsubdomains[0] : null;
			
			if($getchecksubdomain['install_status'] == 'active'){
				respond(array(
				"status" => "error",
				"message" => "Installation Already Completed"
				));
				return;
			}
			
			if($getchecksubdomain){
				if($getchecksubdomain['domain_status'] != 'active'){
					if(IS_ONLINE){
						$cPanel = new ASCpanel(CPANEL_USERNAME,CPANEL_PASSWORD,CPANEL_DOMAIN);
    					$response = $cPanel->uapi->Mysql->create_database(['name' => $getchecksubdomain['db_name']]);
						$response = $cPanel->uapi->Mysql->set_privileges_on_database(['user' => ROOT_USER_DB_NAME, 'database' => $getchecksubdomain['db_name'], 'privileges' => 'ALL']);
					}
					$this->root->update("as_sub_domain" ,  array( 
					'domain_status'		=> 'active',
					'db_status'		=> '1',
					), "`user_id` = :id ", array("id" => $userId));
				}
				
				$getsubdomain = $this->root->table('as_sub_domain')->where('domain_status','active')->where('db_status','1')->where("user_id",$userId)->get(1);
				
				if($getsubdomain){
					$fullsubdomain = $getsubdomain['sub_domain'].'.'.$getsubdomain['root_domain'];
					$getPosRequirement = $this->root->select("SELECT * FROM `as_pos_requirements` WHERE `user_id` = :id", array("id" => $userId));
					$getPosRequirements = count($getPosRequirement) > 0 ? $getPosRequirement[0] : null;
					$newdb = new ASDatabase(ROOT_DB_TYPE, $getsubdomain['db_host'], $getsubdomain['db_name'], ROOT_USER_DB_NAME, ROOT_USER_DB_PASS);
					if(IS_ONLINE){
						$fullsubdomainTls = 'https://'.$fullsubdomain;
						}else{
						$fullsubdomainTls = 'http://'.$fullsubdomain;
					}
					
					$stubsPath = dirname(__FILE__) ."/../install/stubs";
					$asEnginePath = dirname(__FILE__) . "/config";
					$asSqlPath = dirname(__FILE__) ."/../install/stubs";
					
					$installer = new ASInstaller($newdb, $stubsPath, $asEnginePath, $asSqlPath);
					$checkInstall = $installer->install(array(
					'website_name' => $getPosRequirements['company_name'],
					'user_id' => $userId,
					'website_domain' => $fullsubdomainTls,
					'script_url' => $fullsubdomainTls,
					'sub_domain' => $fullsubdomain,
					'db_host' => $getsubdomain['db_host'],
					'db_user' => ROOT_USER_DB_NAME,
					'db_pass' => ROOT_USER_DB_PASS,
					'db_name' => $getsubdomain['db_name']
					));
					
					
					$possetting = $checkInstall->select("SELECT * FROM `pos_setting`");
					
					if(count($possetting) > 0){
						$query = $checkInstall->update("pos_setting" ,  array(
						'company_name'		=> $getPosRequirements['company_name'],
						'currency'			=> $getPosRequirements['currency'],
						'address'			=> $getPosRequirements['company_address'],
						'email'				=> $getPosRequirements['company_email'],
						'phone'				=> $getPosRequirements['company_phone'],
						'nbr_no'			=> $getPosRequirements['vat_no'],
						'nbr_unit'			=> $getPosRequirements['vat_unit'],
						'website'			=> $fullsubdomain,
						'vat'				=> $getPosRequirements['vat_percentage'],
						'vat_type'			=> $getPosRequirements['vat_type'],
						),
						"`id` = :id ",
						array("id"      => 1)
						);
						
						}else{
						$query = $checkInstall->insert("pos_setting",array(
						'company_name'		=> $getPosRequirements['company_name'],
						'currency'			=> $getPosRequirements['currency'],
						'address'			=> $getPosRequirements['company_address'],
						'email'				=> $getPosRequirements['company_email'],
						'phone'				=> $getPosRequirements['company_phone'],
						'nbr_no'			=> $getPosRequirements['vat_no'],
						'nbr_unit'			=> $getPosRequirements['vat_unit'],
						'website'			=> $fullsubdomain,
						'vat'				=> $getPosRequirements['vat_percentage'],
						'vat_type'			=> $getPosRequirements['vat_type'],
						));
					}
					
					$this->root->update("as_sub_domain" ,  array('install_status'	=> 'active'), "`user_id` = :id",array("id"      => $userId));
					
					respond(array(
					"status" => "success",
					"message" => "installation Complete"
					));
					
					return;
					}else{
					respond(array(
					"status" => "error",
					"message" => "installation Incomplete"
					));
					return;
				}
				}else{
				respond(array(
				"status" => "error",
				"message" => "installation Incomplete"
				));
				return;
			}
		}
		
		public function posInstallDBConnect($host,$name,$user,$pass){
			try {
				$db = new ASDatabase(ROOT_DB_TYPE, $host, $name, $user, $pass);
				$db->debug(DEBUG);
				return $db;
				} catch (PDOException $e) {
				die('Connection failed: ' . $e->getMessage());
			}
		}
		
		
		public function posSettingSubmit($data,$userId)
		{
			$query = $this->db->insert("pos_setting",array(
			'company_name'		=> $data['company_name'],
			'currency'			=> $data['currency'],
			'address'			=> $data['address'],
			'email'				=> $data['email'],
			'phone'				=> $data['phone'],
			'website'			=> $data['website'],
			'receipt_footer'	=> $data['receipt'],
			'vat'				=> $data['vat'],
			'vat_type'			=> $data['vat_type'],
			'company_logo'		=> $company,
			'invoice_logo'		=> $invoice,
			));
			return "Setting Successfully Completed";
		}
		
		
		public function InsertUpdatePosRequirements($data,$userData){
			if($this->getwhere('as_pos_requirements','user_id',$userData->id)){
				$this->db->update("as_pos_requirements" ,  array( 
				'company_name'		=> $data['company_name'],
				'company_address'	=> $data['address'],
				'company_email'		=> $userData->email,
				'company_phone'		=> $userData->phone,
				'company_city'		=> $data['city'],
				'company_country'	=> $data['country'],
				'company_postcode'	=> $data['postcode'],
				'vat_no'			=> $data['nbr_no'],
				'vat_unit'			=> $data['nbr_unite'],
				'vat_percentage'	=> $data['vat'],
				'vat_type'			=> $data['vat_type'],
				'status'			=> "active",
				), "`user_id` = :id ", array("id"      => $userData->id));
				}else{
				$query = $this->db->insert("as_pos_requirements",array(
				'company_name'		=> $data['company_name'],
				'company_address'	=> $data['address'],
				'company_email'		=> $userData->email,
				'company_phone'		=> $userData->phone,
				'vat_no'			=> $data['nbr_no'],
				'vat_unit'			=> $data['nbr_unite'],
				'vat_percentage'	=> $data['vat'],
				'vat_type'			=> $data['vat_type'],
				'user_id'			=> $userData->id,
				'status'			=> "active",
				));
			}
			return;
		}
		
		
		
		public function account_update($data,$id){
			$sql = $this->root->update("as_user_details" ,  array( 
			"first_name"=> $data['first_name'],
			"last_name"=> $data['last_name'],
			"phone"=> $data['phone'],
			"address"=> $data['address'],
			),
			"`user_id` = :id ",
			array("id"      => $id)
			);
			
			$sql = $this->root->update("as_users" ,  array( 
			"email"=> $data['email']
			),
			"`user_id` = :id ",
			array("id"      => $id)
			);
			
			respond(array(
			"status" => "success",
			"message" => trans('message')
			));
		}
		
		public function admin_account_update($data,$id){
			$sql = $this->root->update("as_user_details" ,  array( 
			"first_name"=> $data['first_name'],
			"last_name"=> $data['last_name'],
			"phone"=> $data['phone'],
			"address"=> $data['address'],
			),
			"`user_id` = :id ",
			array("id"      => $id)
			);
			
			$sql = $this->root->update("as_users" ,  array( 
			"email"=> $data['email']
			),
			"`user_id` = :id ",
			array("id"      => $id)
			);
			
			// if($data['newdb'] != "null"){
			// $data['newdb']->update("as_user_details" ,  array( 
			// "first_name"=> $data['first_name'],
			// "last_name"=> $data['last_name'],
			// "phone"=> $data['phone'],
			// "address"=> $data['address'],
			// ),
			// "`user_id` = :id ",
			// array("id"      => $id)
			// );
			
			// $data['newdb']->update("as_users" ,  array( 
			// "email"=> $data['email']
			// ),
			// "`user_id` = :id ",
			// array("id"      => $id)
			// );
			// }
			
			respond(array(
			"status" => "success",
			"message" => trans('message')
			));
		}
		
		public function updatepospermission($id){
			$this->root->update("as_user_details" ,  array( 
			"pos_sale"=> 'edit',
			"pos_category"=> 'edit',
			"pos_unit"=> 'edit',
			"pos_product"=> 'edit',
			"pos_supplier"=> 'edit',
			"pos_stock"=> 'edit',
			"pos_customer"=> 'edit',
			"pos_return"=> 'edit',
			"pos_damage"=> 'edit',
			"pos_report"=> 'edit'
			),
			"`user_id` = :id ",
			array("id"      => $id)
			);
		}
		
		public function GetDeleteUser($id,$domain_id) {
			$as_users_sql = $this->root->delete("as_users", "user_id = :el", array( "el" => $id ));
			$as_user_details_sql = $this->root->delete("as_user_details", "user_id = :el", array( "el" => $id ));
			$this->newdb($domain_id)->delete("as_users", "user_id = :el", array( "el" => $id ));
			$this->newdb($domain_id)->delete("as_user_details", "user_id = :el", array( "el" => $id ));
			return $as_user_details_sql;
		}
		
		public function getApiDeleteAuto($col,$where,$id) {
			return $this->root->delete($col, "$where = :el", array( "el" => $id ));
		}
		
		public function newdb($domain_id) {
			$getsubdomain = $this->getwhereid('as_sub_domain','domain_id',$domain_id);
			$newdb = "null";
			if($getsubdomain){
				$newdb = new ASDatabase(
				'mysql',
				$getsubdomain['db_host'],
				$getsubdomain['db_name'],
				$getsubdomain['db_username'],
				$getsubdomain['db_password']
				);
			}
			return $newdb;
		}
		
		public function getexistcheck($col,$where,$id,$field,$error_msg){
			
			$result = $this->db->select("SELECT * FROM `$col` WHERE `$where` = :id", array( 'id' => $id));
			
			if ($result) {
				respond(array(
				'status' => 'error',
				'errors' => array(
				$field => $error_msg
				)), 422);
			}
		}
		
		public function Agent_withdraw($data,$userID)
		{
			$user_data=$this->getwhereid('as_users','user_id',$userID);
			if($user_data['withdrawal_password'] == app('hasher')->hashPassword($data['withdrawal_password'])){
				
				$agent_received = $this->getsumtotalbywhereand('as_agent_payment','payment_amount','payment_type','receive','agent_id',$userID);
				$agent_withdrawal = $this->getsumtotalbywhereand('as_agent_payment','payment_amount','payment_type','withdraw','agent_id',$userID);
				$agent_availiable_balance = $agent_received - $agent_withdrawal;
				
				if($agent_availiable_balance == $data['withdrawal_amount']){
					if(empty($data['account_number'])){
						$payment_details = "Office cash";
						}else{
						$payment_details = "Account Details : ".$data['account_number'];
					}
					
					$this->db->insert('as_agent_payment',array(
					"user_id"      		=> $userID,
					"agent_id"      	=> $userID,
					'payment_type'		=>	"withdraw",
					'payment_method'	=>	$data['method'],
					'payment_date'		=>	date("Y-m-d H:i:s"),
					'payment_amount'	=>	$data['withdrawal_amount'],
					'payment_details'	=>	$payment_details,
					'payment_status'		=>	"due"
					));
					
					respond(array(
					"status" => "success",
					"message" => "You Withdrawal Successfull"
					));
					}else{
					respond(array(
					'status' => 'error',
					'errors' => array(
					'withdrawal_amount' => 'Not Avaliable Balance'
					)), 422);
				}
			}
			else{
				respond(array(
				'status' => 'error',
				'errors' => array(
				'withdrawal_password' => "Withdrawal Password Don't Match"
				)), 422);
			}
		}
		
		public function getapikeycheck($apiKey,$apistatus = null,$userId = null) {
			if($userId){
				$result = $this->root->select("SELECT `as_pos_api`.*,`as_user_details`.* FROM `as_pos_api`,`as_user_details` WHERE `as_pos_api`.`domain_id` = `as_user_details`.`domain_id` AND `as_pos_api`.`api_key` = :id AND `as_user_details`.`user_id` = :uid AND `as_pos_api`.`api_status` = :apistatus", array( 
				'id' => $apiKey,
				'uid' => $userId,
				'apistatus' => $apistatus
				));
				}elseif($apistatus){
				$result = $this->root->select("SELECT * FROM `as_pos_api` WHERE `api_key` = :id AND `api_status` = :apistatus ", array( 
				'id' => $apiKey,
				'apistatus' => $apistatus
				));
				}else{
				$result = $this->root->select("SELECT * FROM `as_pos_api` WHERE `api_key` = :id", array( 
				'id' => $apiKey
				));
			}
			
			return count($result) > 0 ? $result[0] : null;
		}
		
		public function GetApiKeyUpdate($apikey,$api_status,$pc_name,$last_ip,$uid,$domain_id) {
			if($this->root->select("SELECT * FROM `as_pos_api` WHERE `api_key` = :id", array('id' => $apikey))){
				$this->root->update("as_pos_api" ,  array( 
				"api_status"=> $api_status,
				"pc_name"=> $pc_name,
				"last_ip"=> $last_ip,
				"active_user_id"=> $uid
				),
				"`api_key` = :id ",
				array("id"      => $apikey)
				);
				}else{
				$this->root->insert('as_pos_api',array(
				"api_key"	=> $apikey,
				"api_status"=> $api_status,
				"pc_name"=> $pc_name,
				"last_ip"=> $last_ip,
				"domain_id"=> $domain_id,
				"active_user_id"=> $uid
				));
			}
			return true;
		}
		
		public function notification()
		{
			$newdb = new ASDatabase(
			'mysql',
			ROOT_DB_HOST,
			ROOT_DB_NAME,
			ROOT_DB_USER,
			ROOT_DB_PASS
			);
			
			$result = $newdb->select("SELECT * FROM `as_notification` WHERE `status` = :id ", array( 'id' => 'active'));
			return $result;
			
		}
		
		public function getTutorials()
		{
			$newdb = new ASDatabase(
			'mysql',
			ROOT_DB_HOST,
			ROOT_DB_NAME,
			ROOT_DB_USER,
			ROOT_DB_PASS
			);
			
			$result = $newdb->select("SELECT * FROM `as_tutorials` WHERE `status` = :id ", array( 'id' => 'active'));
			return $result;
			
		}
		public function SendVerifyNumber($data)
		{
			$smskey = rand(100000 , 999999);
			$smsbody = "Welcome to ".WEBSITE_NAME.". Your Verify key is : ".$smskey;
			$phone_number=$data['country_code'].''.$data['phone_number'];
			
			$getuserdetails=$this->getwhereid('as_user_details','phone',$phone_number);
			if(count($getuserdetails)!=0){
				$this->db->update(
				'as_users',array(
				"sms_verify_key" => $smskey
				),
				"`user_id` = :userid",
				array("userid" => $getuserdetails['user_id']));
				
				$this->sendsms($phone_number,$smsbody);
				
				respond(array(
				"status" => "success",
				"phone" =>$phone_number,
				"user_id" =>$getuserdetails['user_id'],
				"country_code" =>$data['country_code'],
				));
			}
			else{
				respond(array(
				"status" => "error",
				"message" =>trans('number_wrong')
				));
			}
		}
		public function GetSmskeyVerify($data){
			$getuserdetails=$this->getwhereid('as_users','user_id',$data['user_id']);
			if(count($getuserdetails)!=0 && $getuserdetails['sms_verify_key']==$data['verification_number']){
				respond(array(
				"status" => "success",
				"user_id" =>$getuserdetails['user_id'],
				));
				}else{
				respond(array(
				"status" => "error",
				"message" =>trans('verification_number_dont_match')
				));
			}
		}
		public function ForgotPasswordChange($data)
		{
			if($data['password']==$data['password_confirmation']){
				
				$password=app('register')->hashPassword(hash('sha512',$data['password']));
				
				$this->db->update(
				'as_users',array(
				"password" => $password
				),
				"`user_id` = :userid",
				array("userid" => $data['user_id']));
				respond(array(
				"status" => "success",
				"success_message" => trans('password_change_successfully'),
				));
			}
			else{
				respond(array(
				"status" => "error",
				"message" => trans('password_dont_match'),
				));
			}
		}
		
		public function userPluginsJsonUpdate($userId){
			$getSubscribes = $this->root->select("SELECT * FROM `as_subscribe` WHERE `subscribe_type` = 'software' AND `user_id` = :id", array( 'id' => $userId));
			$i = 0;
			$config = new stdClass;
			foreach($getSubscribes as $getSubscribe){
				$invoiceCheck = $this->root->select("SELECT `invoice_id`,`subscribe_id`,`subscribe_end_date` FROM `as_invoices` WHERE `subscribe_id` = :id AND `invoice_type` = 'bill' AND `invoice_status` = 'paid' ORDER BY `invoice_id` DESC LIMIT 1", 
				array( 'id' => $getSubscribe['subscribe_id']));
				$lastInvoice = count($invoiceCheck) > 0 ? $invoiceCheck[0] : null;
				if($lastInvoice){
					$date = new DateTime($lastInvoice['subscribe_end_date']);
					$subscribe_end_date = $date->format('Y-m-d H:i:s');
					}else{
					$date = new DateTime($getSubscribe['subscribe_activation_date']);
					$date->modify('+ 1 month');
					$subscribe_end_date = $date->format('Y-m-d');
				}
				
				$getSoftwares = $this->root->select("SELECT * FROM `as_software` WHERE `software_id` = :id", array( 'id' => $getSubscribe['software_id']));;
				$getSoftware = count($getSoftwares) > 0 ? $getSoftwares[0] : null;
				$myObj[$getSoftware['software_unique_name']] = new stdClass;
				$myObj[$getSoftware['software_unique_name']]->type = $getSubscribe['subscribe_type'];
				$myObj[$getSoftware['software_unique_name']]->name = $getSoftware['software_title'];
				$myObj[$getSoftware['software_unique_name']]->software_name = $getSoftware['software_unique_name'];
				$myObj[$getSoftware['software_unique_name']]->subscribe_date = $getSubscribe['subscribe_date'];
				$myObj[$getSoftware['software_unique_name']]->renew_date = $subscribe_end_date;
				$myObj[$getSoftware['software_unique_name']]->subscribe_status = $getSubscribe['subscribe_status'];
				$config->software = $myObj;
				
				
				$getSoftwareVariation = $this->root->table('as_software_variation')->where("software_variation_id", $getSubscribe['software_variation_id'])->get(1);
				foreach(explode(",",$getSoftwareVariation['required_plugins']) as $pluginName){
					$getPlugin = $this->root->select("SELECT * FROM `as_plugins` WHERE `plugins_unique_name` = :id", array( 'id' => $pluginName));
					$getPlugins = count($getPlugin) > 0 ? $getPlugin[0] : null;
					if($getPlugins){
						$myObjp[$getPlugins['plugins_unique_name']] = new stdClass;
						$myObjp[$getPlugins['plugins_unique_name']]->type = 'plugins';
						$myObjp[$getPlugins['plugins_unique_name']]->name = $getPlugins['plugins_name'];
						$myObjp[$getPlugins['plugins_unique_name']]->plugin_name = $getPlugins['plugins_unique_name'];
						$myObjp[$getPlugins['plugins_unique_name']]->plugin_version = $getPlugins['plugins_version'];
						$myObjp[$getPlugins['plugins_unique_name']]->plugin_page = $getPlugins['plugins_page'];
						$myObjp[$getPlugins['plugins_unique_name']]->plugin_page_required = $getPlugins['plugins_page_required'];
						$myObjp[$getPlugins['plugins_unique_name']]->plugin_published_date = $getPlugins['plugins_published_date'];
						$myObjp[$getPlugins['plugins_unique_name']]->plugin_update_date = $getPlugins['plugins_update_date'];
						$myObjp[$getPlugins['plugins_unique_name']]->plugin_software_id = $getPlugins['plugins_software_id'];
						$myObjp[$getPlugins['plugins_unique_name']]->plugin_update_type = $getPlugins['plugins_update_type'];
						$myObjp[$getPlugins['plugins_unique_name']]->subscribe_date = $getSubscribe['subscribe_date'];
						$myObjp[$getPlugins['plugins_unique_name']]->renew_date = $subscribe_end_date;
						$myObjp[$getPlugins['plugins_unique_name']]->subscribe_status = $getSubscribe['subscribe_status'];
						if($getPlugins['plugins_unique_name'] == 'multiple_store_warehouse'){
							if($getSubscribe['subscribe_manage_store'] != null){
								$myObjp[$getPlugins['plugins_unique_name']]->manage_store = $getSubscribe['subscribe_manage_store'];
								}else{
								$myObjp[$getPlugins['plugins_unique_name']]->manage_store = $getSoftwareVariation['software_manage_store'];
							}
						}
						
						$config->addons = $myObjp;
						if($getPlugins['plugins_page_required'] == "true"){
							$myObjPage[$getPlugins['plugins_page']] = new stdClass;
							$myObjPage[$getPlugins['plugins_page']]->name = $getPlugins['plugins_name'];
							$myObjPage[$getPlugins['plugins_page']]->plugin_page_name = $getPlugins['plugins_page'];
							$myObjPage[$getPlugins['plugins_page']]->default_addon = $getPlugins['plugins_unique_name'];
							$myObjPage[$getPlugins['plugins_page']]->plugin_page_file = $getPlugins['plugins_page_file'];
							$myObjPage[$getPlugins['plugins_page']]->plugin_extra_page_file = $getPlugins['plugins_extra_page_file'];
							$myObjPage[$getPlugins['plugins_page']]->plugin_software_id = $getPlugins['plugins_software_id'];
							$myObjPage[$getPlugins['plugins_page']]->plugin_update_type = $getPlugins['plugins_update_type'];
							$myObjPage[$getPlugins['plugins_page']]->subscribe_date = $getSubscribe['subscribe_date'];
							$myObjPage[$getPlugins['plugins_page']]->renew_date = $subscribe_end_date;
							$myObjPage[$getPlugins['plugins_page']]->subscribe_status = $getSubscribe['subscribe_status'];
							$config->plugins = $myObjPage;
						}
					}
					$i++;
				}
				
			}
			$jsonData =  json_encode($config);
			$getSubdomains = $this->root->select("SELECT * FROM `as_sub_domain` WHERE `user_id` = :pid", array("pid" => $userId));
			$getSubdomain = count($getSubdomains) > 0 ? $getSubdomains[0] : null;
			$SubDomainName = str_replace(".", "",$getSubdomain['sub_domain'].$getSubdomain['root_domain']);
			$jsonLocation = 'user-config/'.$SubDomainName.'.json';
			$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../');
			$filesystem = new League\Flysystem\Filesystem($adapter);
			$exists = $filesystem->has($jsonLocation);
			if($exists){
				$filesystem->update($jsonLocation, $jsonData);
				}else{
				$filesystem->write($jsonLocation, $jsonData);
			}
			return $jsonData;
		}
		
		public function softwareSubscribeCheck(){
			$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../');
			$filesystem = new League\Flysystem\Filesystem($adapter);
			$SubDomainName = str_replace(".", "", $_SERVER['SERVER_NAME']);
			$jsonPluginLocation = 'user-config/'.$SubDomainName.'.json';
			$contents = $filesystem->read($jsonPluginLocation);
			$jsonData = json_decode($contents);
			$exists = $filesystem->has($jsonPluginLocation);
			$softwareName ='point_of_sale';
			if($exists){
				if(isset($jsonData->software->$softwareName)){
					if($jsonData->software->$softwareName->type == 'software'){
						if($jsonData->software->$softwareName->renew_date > date('Y-m-d H:i:s')){
							return true;
						}
						return false;
					}
					return false;
				}
				return false;
			}
			return false;
		}
		
		public function getAddonValue($pluginName,$addonName,$Redriect = false){
			$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../');
			$filesystem = new League\Flysystem\Filesystem($adapter);
			$SubDomainName = str_replace(".", "", $_SERVER['SERVER_NAME']);
			$jsonPluginLocation = 'user-config/'.$SubDomainName.'.json';
			$jsonPlugin = $filesystem->read($jsonPluginLocation);
			$jsonPluginData = json_decode($jsonPlugin);
			$pluginSexists = $filesystem->has($jsonPluginLocation);
			if($pluginSexists){
				if(isset($jsonPluginData->addons->$pluginName->$addonName)){
					return $jsonPluginData->addons->$pluginName->$addonName;
					}else{
					if($Redriect){
						redirect("pos/plugins-status");
					}
				}
			}
			
			if($Redriect){
				redirect("pos/plugins-status/".$pluginName);
			}
			return false;
		}
		
		public function checkAddon($pluginName,$Redriect = false){
			$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../');
			$filesystem = new League\Flysystem\Filesystem($adapter);
			$SubDomainName = str_replace(".", "", $_SERVER['SERVER_NAME']);
			$jsonPluginLocation = 'user-config/'.$SubDomainName.'.json';
			$jsonPlugin = $filesystem->read($jsonPluginLocation);
			$jsonPluginData = json_decode($jsonPlugin);
			$pluginSexists = $filesystem->has($jsonPluginLocation);
			if($pluginSexists){
				if(isset($jsonPluginData->addons->$pluginName)){
					if($jsonPluginData->addons->$pluginName->subscribe_status == 'active'){
						return true;
					}
					}elseif(isset($jsonPluginData->plugins->$pluginName)){
					if($jsonPluginData->plugins->$pluginName->subscribe_status == 'active'){
						return true;
					}
					}else{
					if($Redriect){
						redirect("pos/plugins-status");
					}
				}
			}
			
			if($Redriect){
				redirect("pos/plugins-status/".$pluginName);
			}
			return false;
		}
		
		public function loadAddon($pluginName,$addonName = false,$data = array()){
			$adapter = new League\Flysystem\Adapter\Local(__DIR__.'/../');
			$filesystem = new League\Flysystem\Filesystem($adapter);
			$SubDomainName = str_replace(".", "", $_SERVER['SERVER_NAME']);
			$jsonPluginLocation = 'user-config/'.$SubDomainName.'.json';
			
			$jsonPlugin = $filesystem->read($jsonPluginLocation);
			$jsonPluginData = json_decode($jsonPlugin);
			$pluginSexists = $filesystem->has($jsonPluginLocation);
			if($pluginSexists){
				if(isset($jsonPluginData->addons->$pluginName) && $addonName){
					if($jsonPluginData->addons->$pluginName->type == 'plugins' && $jsonPluginData->addons->$pluginName->subscribe_status == 'active'){
						if($jsonPluginData->addons->$pluginName->renew_date > date('Y-m-d H:i:s')){
							if(isset($data['plugins_version'])){
								$pluginsVersion = $data['plugins_version'];
								}else{
								$pluginsVersion = $jsonPluginData->addons->$pluginName->plugin_version;
							}
							$jsonAddonLocation = 'addons/'.$jsonPluginData->addons->$pluginName->plugin_name.'/'.$pluginsVersion.'/config.json';
							$addonSexists = $filesystem->has($jsonAddonLocation);
							if($addonSexists){
								$jsonAddon = $filesystem->read($jsonAddonLocation);
								$jsonAddonData = json_decode($jsonAddon);
								if($jsonAddonData->version == $pluginsVersion){
									if(isset($jsonAddonData->structure->$addonName)){
										$fileLocation = 'addons/'.$jsonPluginData->addons->$pluginName->plugin_name.'/'.$pluginsVersion.'/'.$jsonAddonData->structure->$addonName.'.php';
										$fileSexists = $filesystem->has($fileLocation);
										if($fileSexists){
											return ['load_status' => true, 'load_msg' => "Addon File Found", 'load_addon' => $addonName, 'location' => $fileLocation];
											}else{
											return ['load_status' => false, 'load_msg' => "Addon File Missing"];
										}
										}elseif(isset($jsonAddonData->required->$addonName)){
										$data['plugins_version'] = $jsonAddonData->required->$addonName;
										$this->loadAddon($jsonPluginData->addons->$pluginName->plugin_name,$addonName,$data);
										return;
										}else{
										return ['load_status' => false, 'load_msg' => "Invalid Addon Name"];
									}
								}
							}
						}
						return ['load_status' => false, 'load_msg' => "Invalid Addon Name"];
					}
					return ['load_status' => false, 'load_msg' => "Addon Inactive"];
					}elseif(isset($jsonPluginData->plugins->$pluginName)){
					$fileLocation = 'plugins/'.$jsonPluginData->plugins->$pluginName->plugin_page_name.'/'.$jsonPluginData->plugins->$pluginName->plugin_page_file.'.php';
					$fileSexists = $filesystem->has($fileLocation);
					if($fileSexists){
						include dirname(__FILE__) .'/../'.$fileLocation;
						// return ['load_status' => true, 'load_msg' => "Addon File Found", 'location' => '/../../'.$fileLocation];
						}else{
						echo "Plugin File Missing";
						return false;
					}
					}elseif(count($data) > 0){
					return $data;
				}
				return false;
			}
			return false;
		}
		
		public function getSoftwareVariationPrice($data){
			
			$result = $this->root->select("SELECT * FROM `as_software_variation` WHERE `software_id` = :sid AND `software_variation_id` = :id", array( 'id' => $data['variation_id'], 'sid' => $data['software_id']));
			$getVariation = count($result) > 0 ? $result[0] : null;
			if(!empty($getVariation['required_plugins'])){
				$Getplugin = explode(',',$getVariation['required_plugins']);
				$getVariationPlugins = [];
				for ($x = 0; $x < count($Getplugin); $x++) {
					$results = $this->root->select("SELECT * FROM `as_plugins` WHERE `plugins_unique_name` = :id", array( 'id' => $Getplugin[$x]));
					$getPluginData = count($results) > 0 ? $results[0] : null;
					if($getPluginData){
						$getVariationPlugins['plugin_price'][$x] = $getPluginData['plugins_price'];
						$getVariationPlugins['plugin_name'][$x] = $getPluginData['plugins_name'];
						$getVariationPlugins['plugin_unique_name'][$x] = $getPluginData['plugins_unique_name'];
						$getVariationPlugins['plugin_id'][$x] = $getPluginData['plugins_id'];
						if($getPluginData['plugins_billing'] == "monthly"){
							$getVariationPlugins['plugin_billing'][$x] = ' TK/Per Month';
							}elseif($getPluginData['plugins_billing'] == "onetime"){
							$getVariationPlugins['plugin_billing'][$x] = ' TK/OneTime';
							}elseif($getPluginData['plugins_billing'] == "yearly"){
							$getVariationPlugins['plugin_billing'][$x] = ' TK/Per Year';
							}elseif($getPluginData['plugins_billing'] == "free"){
							$getVariationPlugins['plugin_billing'][$x] = 'Free';
						}
						}else{
						$getVariationPlugins = null;
					}
					
				}
				}else{
				$getVariationPlugins = null;
			}
			if($getVariation['software_subscribe_in_advance']){
				$advanceFeeCheck = '(Advance Payment)';
				}else{
				$advanceFeeCheck = '';
			}
			
			respond(array(
			"status" => "success",
			"variation_name" => $getVariation['software_variation_name'],
			"variation_price" => $getVariation['software_variation_price'],
			"variation_fee" => $getVariation['software_subscribe_fee'],
			"variation_fee_advance" => $advanceFeeCheck,
			"required_plugins" => $getVariationPlugins
			));
		}
		public function GetVoucherSubmit($data,$userId)
		{
			$getVoucher = $this->getwhereid('as_voucher','voucher_code',$data['voucher_code']);
			if($getVoucher['voucher_available']=="not_available"){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'voucher_code' => trans('invalid_voucher_code')
				)
				), 422);
			}
			if($getVoucher){
				
				$this->db->update("as_voucher" ,  array( 
				'voucher_available'		=>	"not_available",
				'user_id'		=>	$userId,
				), " `voucher_id` = :id", array(
				"id" => $getVoucher['voucher_id']
				));
				
				respond(array(
				"status" => "success",
				));
			}
			else{
				respond(array(
				'status' => 'error',
				'errors' => array(
				'voucher_code' => trans('no_voucher_code_found')
				)
				), 422);
			}
			
		}
		public function GetWithdrawalSubmit($data,$userId)
		{
			$currentFund = $this->currentFund($userId);
			
			$userWithdrawalCharge = $data['withdrawal_amount'] * USER_WITHDRAWAL_CHARGE / 100;
			
			$totalWithdrawalAmount = $data['withdrawal_amount'] - $userWithdrawalCharge;
			
			if($data['withdrawal_amount'] < 500 ){
				respond(array(
				'status' => 'error',
				'errors' => array(
				'withdrawal_amount' => trans('minimum_withdrawal_amount_500_tk')
				)
				), 422);	
			}	
			
			
			if($currentFund['current_balance'] >= $data['withdrawal_amount']){
				
				$transaction_number=0;
				if($data['withdrawal_method'] == 'bank'){
					$transaction_number = $data['bank_account_number'];
				}
				else if($data['withdrawal_method'] == 'office'){
					$transaction_number=0;
				}
				else{
					$transaction_number = $data['account_number'];
				}
				
				$this->db->insert('as_withdrawal',array(
				"user_id" => $userId,
				"withdrawal_type" => "user",
				"withdrawal_charge" => $userWithdrawalCharge,
				"withdrawal_amount" => $data['withdrawal_amount'],
				"withdrawal_total_amount" => $totalWithdrawalAmount,
				"withdrawal_method" => $data['withdrawal_method'],
				"withdrawal_transaction_id" => $transaction_number,
				"withdrawal_status" => "requested",
				));
				
				respond(array(
				"status" => "success",
				"message" => trans("withdrawal_successfull"),
				));
				
				}else{
				respond(array(
				"status" => "error",
				"message" => 'you do not have sufficient balance for withdrawal',
				));	
			}
			
			
			
		}
		public function GetWithdrawalCancel($data){
			$this->db->update('as_withdrawal',array(
			"withdrawal_status"=>"cancel"
			),"`withdrawal_id` =:id",array("id"=>$data['id']));
			respond(array(
			"status" => "success"
			));
		}
		
		public function getAddFund($data){
			echo json_encode($data);
			
			respond(array(
			"status" => "success"
			));
		}
		
		public function currentFund($userId){
			$fundAmount = $this->root->select("SELECT sum(payment_amount) AS `total_fund` FROM `as_payment` WHERE `payment_status` = :status AND `payment_load_date` <= :load AND `user_id` = :id", array('status' => 'paid','load' => date('Y-m-d'),'id' => $userId ));
			$withdrawalAmount = $this->root->select("SELECT sum(withdrawal_amount) AS `total_withdrawal` FROM `as_withdrawal` WHERE `withdrawal_status` != :status AND `user_id` = :id", array('status' => 'cancel','id' => $userId));
			$invoiceAmount = $this->root->select("SELECT sum(invoice_amount) AS `total_invoice` FROM `as_invoices` WHERE `invoice_status` != :status AND `user_id` = :id", array('status' => 'cancel','id' => $userId));
			$voucherAmount = $this->root->select("SELECT sum(voucher_amount) AS `total_voucher` FROM `as_voucher` WHERE `voucher_status` = :status AND `voucher_available` != :astatus AND `user_id` = :id", array('status' => 'active', 'astatus' => 'reject','id' => $userId));
			$currentBalance = $fundAmount[0]['total_fund'] + $voucherAmount[0]['total_voucher'] - $withdrawalAmount[0]['total_withdrawal'] - $invoiceAmount[0]['total_invoice'];
			return['current_balance' => $currentBalance,'total_fund' => $fundAmount[0]['total_fund'], 'total_withdrawal' => $withdrawalAmount[0]['total_withdrawal'],'total_invoice' => $invoiceAmount[0]['total_invoice'], 'total_withdrawal' => $voucherAmount[0]['total_voucher']];
		}
		
		public function ModuleStatusChange($data,$userId,$agentId)
		{
			
			
			$GetPluginsDetail = $this->root->select("SELECT * FROM `as_plugins` WHERE `plugins_id` = :pid", array("pid" => $data['id']));
			$GetPluginsDetails = count($GetPluginsDetail) > 0 ? $GetPluginsDetail[0] : null;
			
			$getPluginsSubscribes = $this->root->select("SELECT * FROM `as_subscribe` WHERE `user_id` = :uid AND `subscribe_type` = 'plugins' AND `software_id` = :sid AND `plugins_id` = :pid", array("uid" => $userId, "sid" => $GetPluginsDetails['plugins_software_id'], "pid" => $data['id']));
			$getPluginsSubscribe = count($getPluginsSubscribes) > 0 ? $getPluginsSubscribes[0] : null;
			
			$currentFund = $this->currentFund($userId);
			
			$PluginsSubscribeData = array(
			"user_id"      => $userId,
			"agent_id"      => $agentId,
			"software_id"      => $GetPluginsDetails['plugins_software_id'],
			"plugins_id"      => $data['id'],
			"subscribe_type"      => 'plugins',
			"subscribe_date"      => date("Y-m-d H:i:s"),
			'subscribe_activation_date'	=>	date("Y-m-d H:i:s"),
			"subscribe_amount"      => $GetPluginsDetails['plugins_price'],
			"subscribe_payment_terms"      => $GetPluginsDetails['plugins_billing'],
			"subscribe_payment_terms_value"      => $GetPluginsDetails['plugins_billing_value'],
			"subscribe_status"   => "inactive",
			"created_at" => date("Y-m-d H:i:s")
			);
			
			if(!$GetPluginsDetails){
				respond(array(
				"status" => 'error',
				"massage" => 'Plugins Not Found'
				));
				return;
			}
			
			if(!$getPluginsSubscribe){
				if($GetPluginsDetails['plugins_billing_type'] == 'postpaid'){
					$PluginsSubscribeData["subscribe_status"] = 'active';
					}elseif($GetPluginsDetails['plugins_billing_type'] == 'prepaid'){
					if($currentFund['current_balance'] >= $GetPluginsDetails['plugins_price']){
						$PluginsSubscribeData["subscribe_status"] = 'active';
						}else{
						respond(array(
						"status" => 'error',
						"massage" => 'you do not have sufficient balance for active this plugins'
						));
						return;
					}
				}
				}elseif($getPluginsSubscribe['subscribe_status'] == 'active'){
				$PluginsSubscribeData["subscribe_status"] = 'cancel';
				}elseif($getPluginsSubscribe['subscribe_status'] == 'cancel'){
				respond(array(
				"status" => 'error',
				"massage" => trans("contact_appsowl_support_team_for_active_plugins")
				));
				return;
			}
			
			
			
			if(!$getPluginsSubscribe){
				$this->root->insert("as_subscribe", $PluginsSubscribeData);
				$PluginsSubscribeId = $this->root->lastInsertId();
				}else{
				$this->root->update("as_subscribe" , $PluginsSubscribeData , "`subscribe_id` = :id ", array("id" => $getPluginsSubscribe['subscribe_id']));
				$PluginsSubscribeId = $getPluginsSubscribe['subscribe_id'];
			}
			
			
			
			
			if($GetPluginsDetails['plugins_billing_type'] == 'prepaid' && $PluginsSubscribeData["subscribe_status"] == 'active'){
				if($currentFund['current_balance'] >= $GetPluginsDetails['plugins_price']){
					$date = new DateTime();
					if($GetPluginsDetails['plugins_billing'] == 'monthly'){
						$date->modify('+'.$GetPluginsDetails['plugins_billing_value'].'month');
						$data['subscribe_month'] = $GetPluginsDetails['plugins_billing_value'];
						}elseif($GetPluginsDetails['plugins_billing'] == 'yearly'){
						$date->modify('+'.$GetPluginsDetails['plugins_billing_value'].'year');
						$data['subscribe_month'] = $GetPluginsDetails['plugins_billing_value'] * 12;
						}elseif($GetPluginsDetails['plugins_billing'] == 'free'){
						$date->modify('+'.$GetPluginsDetails['plugins_billing_value'].'month');
						$data['subscribe_month'] = $GetPluginsDetails['plugins_billing_value'];
						}elseif($GetPluginsDetails['plugins_billing'] == 'onetime'){
						$date->modify('+'.$GetPluginsDetails['plugins_billing_value'].'month');
						$data['subscribe_month'] = $GetPluginsDetails['plugins_billing_value'];
					}
					
					$pluginsSubscribeEndDate = $date->format('Y-m-d H:i:s');
					
					$PluginsInvoiceData = array(
					"user_id"      => $userId,
					"agent_id"      => $agentId,
					"subscribe_id"      => $PluginsSubscribeId,
					"invoice_amount"      => $GetPluginsDetails['plugins_price'],
					"invoice_transaction_id"      => gettoken(10),
					"invoice_type"      => 'bill',
					"subscribe_month"      => $data['subscribe_month'],
					"subscribe_start_date"      => date("Y-m-d H:i:s"),
					"subscribe_end_date" => $pluginsSubscribeEndDate,
					"invoice_paid_date" => date("Y-m-d H:i:s"),
					"invoice_status"   => "paid",
					"created_at" => date("Y-m-d H:i:s")
					);
					
					$this->root->insert("as_invoices", $PluginsInvoiceData);
					$invoiceId = $this->root->lastInsertId();
					$this->GetAgentSubscribeCommissionPayment($invoiceId);
				}
			}
			$this->userPluginsJsonUpdate($userId);				
			
			respond(array(
			"status" => 'success',
			"massage" => 'Plugins Activation Successfull',
			));
		}
		
		public function GetNotification($data){
			$notification = $this->root->select("SELECT * FROM `as_notification`
			WHERE (`notification_type`='admin' OR (`notification_type`='user' 
			AND `user_id` =:id)) AND `notification_id` > :n_id",array('id'=>$data['u_id'],'n_id'=>$data['n_id']));
			$last_notification = end($notification);
			$last_id = $last_notification['notification_id'];
			if(count($notification)!=0){
				respond(array(
				"status" => 'success',
				"notifications" => $notification,
				"last_id" => $last_id,
				"total" => count($notification),
				));	
			}
		}
		
		public function UpdateNotificationReadStatus($data){
			$this->root->update("as_notification" , ['read_status' => 'read'] , "`notification_id` = :id ", array("id" => $data['n_id']));
			respond(array(
			"status" => "success",
			));	
		}
		
		public function GetManualPayment($data,$userId,$agentId){
			
			$getSubscribes = $this->root->select("SELECT * FROM `as_subscribe` WHERE `user_id` = :uid AND `subscribe_id` = :sid", array("uid" => $userId, "sid" => $data['subscribe_id']));
			$getSubscribe = count($getSubscribes) > 0 ? $getSubscribes[0] : null;
			$currentFund = $this->currentFund($userId);
			
			if($getSubscribe['subscribe_status'] == 'expire'){
				if($currentFund['current_balance'] >= $getSubscribe['subscribe_amount']){
					$date = new DateTime();
					if($getSubscribe['subscribe_payment_terms'] == 'monthly'){
						$date->modify('+'.$getSubscribe['subscribe_payment_terms_value'].'month');
						$data['subscribe_month'] = $getSubscribe['subscribe_payment_terms_value'];
						}elseif($getSubscribe['subscribe_payment_terms'] == 'yearly'){
						$date->modify('+'.$getSubscribe['subscribe_payment_terms_value'].'year');
						$data['subscribe_month'] = $getSubscribe['subscribe_payment_terms_value'] * 12;
						}elseif($getSubscribe['subscribe_payment_terms'] == 'free'){
						$date->modify('+'.$getSubscribe['subscribe_payment_terms_value'].'month');
						$data['subscribe_month'] = $getSubscribe['subscribe_payment_terms_value'];
						}elseif($getSubscribe['subscribe_payment_terms'] == 'onetime'){
						$date->modify('+'.$getSubscribe['subscribe_payment_terms_value'].'month');
						$data['subscribe_month'] = $getSubscribe['subscribe_payment_terms_value'];
					}
					
					$SubscribeEndDate = $date->format('Y-m-d H:i:s');
					
					$InvoiceData = array(
					"user_id"      => $userId,
					"agent_id"      => $agentId,
					"subscribe_id"      => $data['subscribe_id'],
					"invoice_amount"      => $getSubscribe['subscribe_amount'],
					"invoice_transaction_id"      => gettoken(10),
					"invoice_type"      => 'bill',
					"subscribe_month"      => $data['subscribe_month'],
					"subscribe_start_date"      => date("Y-m-d H:i:s"),
					"subscribe_end_date" => $SubscribeEndDate,
					"invoice_paid_date" => date("Y-m-d H:i:s"),
					"invoice_status"   => "paid",
					"created_at" => date("Y-m-d H:i:s")
					);
					$this->root->insert("as_invoices", $InvoiceData);
					$invoiceId = $this->root->lastInsertId();
					$this->GetAgentSubscribeCommissionPayment($invoiceId);
					
					$this->root->update("as_subscribe" , ['subscribe_status' => 'active'] , "`subscribe_id` = :id ", array("id" => $data['subscribe_id']));
					$this->userPluginsJsonUpdate($userId);	
					respond(array(
					"status" => 'success',
					"title" => 'Payment Status',
					"massage" => 'Payment successfully',
					));
					
					}else{
					respond(array(
					"status" => 'error',
					"title" => 'Payment Status',
					"massage" => 'you do not have sufficient balance for due payment. Please add some fund first.'
					));
				}
				}elseif($getSubscribe['subscribe_status'] == 'active'){
				respond(array(
				"status" => 'error',
				"title" => 'Activation Status',
				"massage" => 'You already paid',
				));
				}elseif($getSubscribe['subscribe_status'] == 'cancel'){
				respond(array(
				"status" => 'error',
				"title" => 'Activation Status',
				"massage" => 'This subscribetion has been cancel. Please contact appsowl support team for active this '.$getSubscribe['subscribe_type'],
				));
			}
			
		}
		
		public function GetSubscribeCancel($data,$userId){
			
			$getSubscribes = $this->root->select("SELECT * FROM `as_subscribe` WHERE `user_id` = :uid AND `subscribe_id` = :sid", array("uid" => $userId, "sid" => $data['subscribe_id']));
			$getSubscribe = count($getSubscribes) > 0 ? $getSubscribes[0] : null;
			
			if($getSubscribe['subscribe_status'] == 'expire' || $getSubscribe['subscribe_status'] == 'active' ){
				$this->root->update("as_subscribe" , ['subscribe_status' => 'cancel'] , "`subscribe_id` = :id ", array("id" => $data['subscribe_id']));
				$this->userPluginsJsonUpdate($userId);	
				respond(array(
				"status" => 'success',
				"title" => 'Activation Status',
				"massage" => 'This '.$getSubscribe['subscribe_type'].' has been canceled successfully',
				));
				
				}elseif($getSubscribe['subscribe_status'] == 'cancel'){
				respond(array(
				"status" => 'error',
				"title" => 'Activation Status',
				"massage" => 'This '.$getSubscribe['subscribe_type'].' already canceled',
				));
				}else{
				respond(array(
				"status" => 'error',
				"title" => 'Access Denied',
				"massage" => 'Please contact appsowl support team for active or cancel this '.$getSubscribe['subscribe_type'],
				));
				
			}
			
		}
		public function AddTicketData($data,$user_id)
		{
			$document=null;
			if(isset($data['ticket_document_name'])){
				$document = $data['ticket_document_name'];
			}
			$this->root->insert('as_ticket',array(
			'user_id'				=>	$user_id,
			'ticket_title'           =>  $data['ticket_title'],
			'ticket_details'        => $data['ticket_details'],
			'ticket_type'          => 'ticket',
			'priority'          => $data['priority_level'],
			'ticket_document'              => $document,
			'created_at'              => date("Y-m-d H:i:s"),
			
			));
			respond(array(
			"status" => 'success',
			"message" => trans('ticket_successfully_submitted')
			));
		}
		public function AddTicketChat($data,$user_id){
			$document = null;
			if(isset($data['chat_document_name'])){
				$document = $data['chat_document_name'];
			}
			$this->root->insert('as_ticket',array(
			'user_id'				=>	$user_id,
			'ticket_type'           =>  'chat',
			'ticket_document'        => $document,
			'reply_by'          => 'user',
			'ticket_message'          => $data['chat_text'],
			'ticket_for'              => $data['ticket_no'],
			'created_at'              => date("Y-m-d H:i:s"),
			
			));
			
			respond(array(
			"status" => 'success',
			"message" => trans('ticket_successfully_submitted')
			));
		}
		
		public function GetChangeApiStatus($data)
		{
			$this->root->update("as_pos_api", array('api_status' => $data['api_status']), "id = :el", array( "el" => $data['api_id'] ));
			respond(array(
			"status" => "success",
			));
		}
		public function GetTutorialByPageName($data){
			$status='error';
			$message="We Haven't Video For This Page Yet.";
			$tutorial =  app("root")->table('as_tutorials')->where('page',$data['page'])->get('link');
			
			if($tutorial){
				$status='success';
				$message=$tutorial;
			}
			
			respond(array(
			"message" => $message,
			"status" => $status,
			));
		}
		public function ChangeStoreByStoreId($data,$user_id)
		{
			$this->root->update("as_user_details" , array('store_id' => $data['id']), "`user_id`= :id", array('id' => $user_id));
			
			respond(array(
			"status" => "success",
			));
		}
	}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																												