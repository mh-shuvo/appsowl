<?php
	
	/**
		* Class ASDatabase
	*/
	class ASDatabase extends PDO
	{
		protected $debug = false;
		
		private $uniqueId = '';
		private $col = [];
		private $colid = [];
		private $sum = [];
		private $sumid = [];
		private $offset = [];
		private $offsetid = [];
		private $limit = [];
		private $limitid = [];
		private $order = [];
		private $orderid = [];
		private $findSet = [];
		private $findSetid = [];
		private $search = [];
		private $searchid = [];
		private $bstart = [];
		private $bend = [];
		private $bdate =[];
		private $bdateid =[];
		private $where = [];
		private $whereid = [];
		private $whereClauses = [];
		private $whereJoint = [];
		private $whereJointid = [];
		private $whereClausesJoint = [];
		private $columns = [];
		private $setTable = [];
		private $setTableid = [];
		
		/**
			* Class constructor
			* Parameters defined as constants in ASConfig.php file
			* @param $type string Database type
			* @param $host string Database host
			* @param $databaseName string Database username
			* @param $username string User's username
			* @param $password string Users's password
		*/
		public function __construct($type, $host, $databaseName, $username, $password)
		{
			parent::__construct($type.':host='.$host.';dbname='.$databaseName.';charset=utf8', $username, $password);
			$this->exec('SET CHARACTER SET utf8');
		}
		
		/**
			* Enable/disable debug for database queries.
			* @param $debug boolean TRUE to enable debug, FALSE otherwise.
		*/
		public function debug($debug)
		{
			$this->debug = $debug;
			
			if ($debug) {
				$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} else {
				$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			}
		}
		
		/**
			* Get current debug status
			* @return bool TRUE if debug mode is enabled, FALSE otherwise.
		*/
		public function getUniqueId()
		{
			$this->uniqueId = gettoken();
			
			return $this;
		}
		
		/**
			* Select
			* @param $sql string An SQL string.
			* @param $array array Parameters to bind.
			* @param $fetchMode int A PDO Fetch mode.
			* @return array
		*/
		
		public function getDebug()
		{
			return $this->debug;
		}
		
		public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC)
		{
			$sth = $this->prepare($sql);
			
			foreach ($array as $key => $value) {
				$sth->bindValue(":$key", $value);
			}
			
			$sth->execute();
			
			return $sth->fetchAll($fetchMode);
		}
		
		
		/**
			* Insert data to database.
			* @param $table string A name of table to insert into
			* @param $data string An associative array
		*/
		public function insert($table, array $data)
		{
			ksort($data);
			
			$fieldNames = implode('`, `', array_keys($data));
			$fieldValues = ':' . implode(', :', array_keys($data));
			
			$sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");
			
			foreach ($data as $key => $value) {
				$sth->bindValue(":$key", $value);
			}
			
			$sth->execute();
		}
		
		/**
			* Update
			* @param $table string A name of table to insert into.
			* @param $data array An associative array where keys have the same name as database columns.
			* @param $where string the WHERE query part.
			* @param $whereBindArray array Parameters to bind to where part of query.
		*/
		public function update($table, $data, $where, $whereBindArray = array())
		{
			ksort($data);
			
			$fieldDetails = null;
			
			foreach ($data as $key => $value) {
				$fieldDetails .= "`$key`=:$key,";
			}
			
			$fieldDetails = rtrim($fieldDetails, ',');
			
			$sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
			
			foreach ($data as $key => $value) {
				$sth->bindValue(":$key", $value);
			}
			
			foreach ($whereBindArray as $key => $value) {
				$sth->bindValue(":$key", $value);
			}
			
			$sth->execute();
		}
		
		/**
			* Delete
			*
			* IF YOU USE PREPARED STATEMENTS, DON'T FORGET TO UPDATE $bind ARRAY!
			*
			* @param $table
			* @param $where
			* @param array $bind
			* @param int $limit
		*/
		public function delete($table, $where, $bind = array(), $limit = null)
		{
			$query = "DELETE FROM $table WHERE $where";
			
			if ($limit) {
				$query .= " LIMIT $limit";
			}
			
			$sth = $this->prepare($query);
			
			foreach ($bind as $key => $value) {
				$sth->bindValue(":$key", $value);
			}
			
			$sth->execute();
		}
		
		public function GetFilterDataJoint($data,$col = array(),$selected = array(),$search = array(),$WhereAnd = array(),$WhereJoint = array(),$WhereNot = array()) {
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
			
			$sql = "SELECT $SelectedRows  FROM $ColQuery WHERE 1 ".$WhereAndDetails.$WhereNotDetails.$WhereJointDetails.$searchQuery.$BetweenQuery.$OrderBy.$LimitPage;
			$sth = $this->prepare($sql);
			
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
			
			$iTotalDisplayRecords = count($this->select("SELECT $SelectedRows  FROM $ColQuery WHERE 1 ".$WhereAndDisplayuDetails.$WhereNotDisplayuDetails.$WhereJointDetails.$searchCountQuery.$BetweenQuery));
			$iTotalRecords = count($this->select("SELECT $SelectedRows  FROM $ColQuery WHERE 1".$WhereAndDisplayuDetails.$WhereNotDisplayuDetails.$WhereJointDetails));
			
			return array("draw" => $draw, "iTotalRecords" => $iTotalRecords, "iTotalDisplayRecords" => $iTotalDisplayRecords, "Data" => $data);
		}
		
		public function table($value){
			$this->getUniqueId();
			if(is_array($value)){
				foreach($value AS $val){
					$this->setTable[] = $val;
					$this->setTableid[] = $this->uniqueId;
				}
				}else{
				$this->setTable[] = $value;
				$this->setTableid[] = $this->uniqueId;
			}
			return $this;
		}
		
		public function where($key, $value, $valueExtend = null){
			if($valueExtend){
				$this->where[$key] = $valueExtend;
				$this->whereid[$key] = $this->uniqueId;
				$this->whereClauses[$key] = ' '.$value.' ';
				}else{
				$this->where[$key] = $value;
				$this->whereid[$key] = $this->uniqueId;
				$this->whereClauses[$key] = ' = ';
			}
			return $this;
		}
		
		public function whereJoint($key, $value, $valueExtend = null){
			if($valueExtend){
				$this->whereJoint[$key] = $valueExtend;
				$this->whereJointid[$key] = $this->uniqueId;
				$this->whereClausesJoint[$key] = ' '.$value.' ';
				}else{
				$this->whereJoint[$key] = $value;
				$this->whereJointid[$key] = $this->uniqueId;
				$this->whereClausesJoint[$key] = ' = ';
			}
			return $this;
		}
		
		public function between($betweenSelect, $start, $end, $betweenDate = null){
			$this->bstart[$betweenSelect] = $start;
			$this->bend[$betweenSelect] = $end;
			$this->bdate[$betweenSelect] = $betweenDate;
			$this->bdateid[$betweenSelect] = $this->uniqueId;
			return $this;
		}
		
		public function search($key, $value){
			$this->search[$key] = $value;
			$this->searchid[$key] = $this->uniqueId;
			return $this;
		}
		
		public function find($key, $value){
			$this->findSet[$key] = $value;
			$this->findSetid[$key] = $this->uniqueId;
			return $this;
		}
		
		public function order($key, $value = 'DESC'){
			$this->order[$key] = $value;
			$this->orderid[$key] = $this->uniqueId;
			return $this;
		}
		
		public function sum($key, $value = null){
			if($value){
				$this->sum[$key] = $value;
				$this->sumid[$key] = $this->uniqueId;
				}else{
				$this->sum[$key] = $key;
				$this->sumid[$key] = $this->uniqueId;
			}
			
			return $this;
		}
		
		public function col($value){
			if(is_array($value)){
				foreach($value AS $val){
					$this->col[] = $val;
					$this->colid[] = $this->uniqueId;
				}
				}else{
				$this->col[] = $value;
				$this->colid[] = $this->uniqueId;
			}
			return $this;
		}
		
		public function limit($value){
			$this->limit[] = $value;
			$this->limitid[] = $this->uniqueId;
			return $this;
		}
		
		public function offset($value){
			$this->offset[] = $value;
			$this->offsetid[] = $this->uniqueId;
			return $this;
		}
		
		public function get($AllData = null,$rawSelect = '',$rawWhere = null){
			
			$collFirst=true;
			$colCheck=true;
			$column = '';
			foreach($this->sum AS $key => $val){
				if($this->uniqueId == $this->sumid[$key]){
					if(!$collFirst) $column .= ', ';
					$collFirst = false;
					$colCheck=false;
					$column .= '  COALESCE(SUM('.$key.'), 0) AS '.$val.' ';
				}
			}
			
			foreach($this->col AS $key => $val){
				if($this->uniqueId == $this->colid[$key]){
					if(!$collFirst) $column .= ', ';
					$collFirst = false;
					$colCheck=false;
					$column .= ' '.$val.' ';
				}
			}
			
			$tablelFirst=true;
			$table = '';
			foreach($this->setTable AS $key => $val){
				if($this->uniqueId == $this->setTableid[$key]){
					if(!$tablelFirst) $table .= ', ';
					$tablelFirst = false;
					$table .= ' '.$val.' ';
				}
			}
			
			if($colCheck){
				$column .= ' * ';
			} 
			
			$sql= 'SELECT '.$column.$rawSelect.' FROM '.$table;
			
			if($rawWhere){
				$sql.=' '.$rawWhere.' ';
			}
			
			$whereFirst=true;
			foreach($this->where AS $key => $val){
				if($this->uniqueId == $this->whereid[$key]){
					if($whereFirst){
						if($rawWhere){
							$sql.=' AND ';
							}else{
							$sql.=' WHERE ';
						}
						}else{
						$sql.=' AND ';
					} 
					$whereFirst = false;
					$keyV = $key;
					if(strpos($key, '.') !== false){
						$keyV = str_replace(".", "", $key);
					}
					$sql .= $key.$this->whereClauses[$key].':'.$keyV;
					
				}
			}
			
			foreach($this->whereJoint AS $key => $val){
				if($this->uniqueId == $this->whereJointid[$key]){
					if($whereFirst){
						$sql.=' WHERE ';
						}else{
						$sql.=' AND ';
					} 
					$whereFirst = false;
					$sql .= $key.$this->whereClausesJoint[$key].$val;
				}
			}
			
			foreach($this->bdate AS $key => $val){
				if($this->uniqueId == $this->bdateid[$key]){
					if($whereFirst){
						$sql.=' WHERE ';
						}else{
						$sql.=' AND ';
					}
					$whereFirst = false;
					$keyV = $key;
					if(strpos($key, '.') !== false){
						$keyV = str_replace(".", "", $key);
					}
					if($val){
						$sql.= " date(".$key.") BETWEEN :start".$keyV." AND :end".$keyV." ";
						}else{
						$sql.= " ".$key." BETWEEN :start".$keyV." AND :end".$keyV." ";
					}
				}
			}
			
			foreach($this->findSet AS $key => $val){
				if($this->uniqueId == $this->findSetid[$key]){
					if($whereFirst){
						$sql.=' WHERE ';
						}else{
						$sql.=' AND ';
					} 
					$whereFirst = false;
					$sql .= ' FIND_IN_SET('.$val.','.$key.') ';
				}
			}
			
			foreach($this->order AS $key => $val){
				if($this->uniqueId == $this->orderid[$key]){
					$sql .= " ORDER BY ".$key." ".$val." ";
				}
			}
			
			foreach($this->limit AS $val){
				if($this->uniqueId == $this->limitid[$key]){
					$sql .= ' LIMIT :limits ';
				}
			}
			
			foreach($this->offset AS $val){
				if($this->uniqueId == $this->offsetid[$key]){
					$sql .= ' OFFSET :offsets ';
				}
			}
			
			$searchFirst = true;
			$searchQuery = '';
			foreach($this->search AS $key => $val){
				if($this->uniqueId == $this->searchid[$key]){
					if($searchFirst){
						$searchQuery.=' ';
						}else{
						$searchQuery.=' OR ';
					}
					
					$searchFirst = false;
					
					$keyV = $key;
					if(strpos($key, '.') !== false){
						$keyV = str_replace(".", "", $key);
					}
					
					$searchQuery .= " `".$key."` LIKE :".$keyV;
				}
			}
			
			if(count($this->search) > 0){
				if($this->uniqueId == $this->searchid[$key]){
					if($whereFirst){
						$sql.=' WHERE ';
						}else{
						$sql.=' AND ';
					}
					$whereFirst = false;
					$sql.= " ( ".$searchQuery." ) ";
				}
			}
			
			
			$query = $this->prepare($sql);
			
			foreach($this->search AS $key => $val){
				if($this->uniqueId == $this->searchid[$key]){
					$keyV = $key;
					if(strpos($key, '.') !== false){
						$keyV = str_replace(".", "", $key);
					}
					$query->bindValue(":".$keyV, "%".$val."%");
				}
			}
			
			foreach($this->where AS $key => $val){
				if($this->uniqueId == $this->whereid[$key]){
					$keyV = $key;
					if(strpos($key, '.') !== false){
						$keyV = str_replace(".", "", $key);
					}
					$query->bindValue(":".$keyV, $val);
				}
			}
			
			foreach($this->limit AS $val){
				if($this->uniqueId == $this->limitid[$key]){
					$query->bindValue(":limits", (int) $val, PDO::PARAM_INT);
				}
			}
			
			foreach($this->offset AS $val){
				if($this->uniqueId == $this->offsetid[$key]){
					$query->bindValue(":offsets", (int) $val, PDO::PARAM_INT);
				}
			}
			
			foreach($this->bdate AS $key => $val){
				if($this->uniqueId == $this->bdateid[$key]){
					$keyV = $key;
					if(strpos($key, '.') !== false){
						$keyV = str_replace(".", "", $key);
					}
					$query->bindValue(":start".$keyV, $this->bstart[$key]);
					$query->bindValue(":end".$keyV, $this->bend[$key]);
				}
			}

			try{ 
				$query->execute();
				if($AllData == 1){
					return $query->fetch(PDO::FETCH_ASSOC);
					}elseif($AllData == 'count'){
					return count($query->fetch(PDO::FETCH_ASSOC));
					}elseif($AllData){
					$result = $query->fetch(PDO::FETCH_ASSOC);
					return $result[$AllData];
					}else{
					return $query->fetchAll(PDO::FETCH_ASSOC);
				}
				} catch (PDOException $e){
				die($e->getMessage());
			}  
		}
		
	}
