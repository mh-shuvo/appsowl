<?php
	class ASInstaller
	{
		private $newdb;
		
		
		private $stubsPath;
		
		private $asEnginePath;
		
		private $asSqlPath;
		
		public function __construct(
        ASDatabase $newdb,
        $stubsPath,
        $asEnginePath,
		$asSqlPath
		) {
			$this->newdb = $newdb;
			$this->stubsPath = $stubsPath;
			$this->asEnginePath = $asEnginePath;
			$this->asSqlPath = $asSqlPath;
		}
		
		public function install(array $params)
		{
			$this->createConfigFile($params);
			$this->createDatabaseTables($params);
			return $this->updateDemoData($params);
		}
		
		private function createConfigFile($params)
		{
			$config = file_get_contents($this->stubsPath . "/config.stub");
			
			foreach ($params as $key => $param) {
				$config = str_replace("{{" . $key . "}}", $param, $config);
			}
			$domainName = str_replace(".", "", $params['sub_domain']);
			file_put_contents($this->asEnginePath . "/".$domainName.'.php', $config);
		}
		
		private function createDatabaseTables(array $params)
		{
				$this->newdb->query(
				"ALTER DATABASE `" . $params['db_name'] . "` 
				DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci"
				);
				$sql = file_get_contents($this->asSqlPath."/as.sql");
				$this->newdb->query($sql);
		}
		
		private function posInstallDBConnect($host,$name,$user,$pass){
			try {
				$db = new ASDatabase(ROOT_DB_TYPE, $host, $name, $user, $pass);
				$db->debug(DEBUG);
				return $db;
				} catch (PDOException $e) {
				die('Connection failed: ' . $e->getMessage());
			}
		}
		
		private function updateDemoData(array $params)
		{
		    
		    $db = new ASDatabase(ROOT_DB_TYPE, $params['db_host'], $params['db_name'], $params['db_user'], $params['db_pass']);
		    
                $db->insert("pos_warehouse",array(
    			'warehouse_name'		=> "Default",
    			'warehouse_status'		=> "active",
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'updated_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->insert("pos_variations_category",array(
    			'variation_category_name'		=> "Size",
    			'variation_category_value'		=> "S,M,L,XL,XXL",
    			'store_id'		=> 1,
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'update_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->insert("pos_unit",array(
    			'unit_name'		=> "KG",
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'updated_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->insert("pos_store",array(
    			'store_name'		=> "Default",
    			'store_location'		=> "Default",
    			'warehouse_id'		=> "1",
    			'store_status'		=> "active",
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'updated_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->insert("pos_category",array(
    			'category_name'		=> "Uncategorized",
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'updated_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->insert("pos_payment_method",array(
    			'payment_method_name'		=> "Cash",
    			'payment_method_value'		=> "cash",
    			'minimum_amount'		=> "1",
    			'payment_method_status'		=> "active",
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'updated_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->insert("pos_contact",array(
    			'contact_id'		=> "CUS00000000",
    			'contact_type'		=> "customer",
    			'store_id'		=> "1",
    			'name'		=> "Walk-In Customer",
    			'contact_status'		=> "active",
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'updated_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->insert("pos_contact",array(
    			'contact_id'		=> "SU00000000",
    			'contact_type'		=> "supplier",
    			'store_id'		=> "1",
    			'name'		=> "Walk-In Supplier",
    			'contact_status'		=> "active",
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'updated_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->insert("pos_contact",array(
    			'contact_id'		=> "AU00000000",
    			'contact_type'		=> "account",
    			'store_id'		=> "1",
    			'name'		=> "Default User",
    			'contact_status'		=> "active",
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'updated_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->insert("pos_brands",array(
    			'brand_name'		=> "Default",
    			'user_id'		=> $params['user_id'],
    			'created_at'		=> date("Y-m-d H:i:s"),
    			'update_at'		=> date("Y-m-d H:i:s")
    			));
    			
    			$db->update("pos_user_permission" ,  array( 
    			'permission_view'		=> $params['user_id'],
    			'permission_edit'		=> $params['user_id'],
    			'permission_delete'		=> $params['user_id'],
    			'permission_off'		=> $params['user_id']
    			), "`permission_status` = :id ", array("id" => 'active'));
    			
    			$db->update("accounts_chart" ,  array(
        			"user_id"=> $params['user_id'],
        			),
        			"`is_delete` = :id ",
        			array("id"=> 'false')
        			);
    			
    			return $db;
		}
		
	}
