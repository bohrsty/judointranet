<?php
/* ********************************************************************************************
 * Copyright (c) 2011 Nils Bohrs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 * 
 * Thirdparty licenses see LICENSE
 * 
 * ********************************************************************************************/

// secure against direct execution
if(!defined("JUDOINTRANET")) {die("Cannot be executed directly! Please use index.php.");}

/**
 * class WebserviceJob implements the representation of an object to handle webservice calls
 */
class WebserviceJob extends Object {
	
	/*
	 * class-variables
	 */
	private $config;
	private $type;
	private $jobConfig;
	
	/*
	 * getter/setter
	 */
	public function getConfig() {
		return $this->config;
	}
	public function setConfig($config) {
		$this->config = $config;
	}
	public function getType() {
		return $this->type;
	}
	public function setType($type) {
		$this->type = $type;
	}
	public function getJobConfig() {
		return $this->jobConfig;
	}
	public function setJobConfig($jobConfig) {
		$this->jobConfig = $jobConfig;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($type) {
		
		// parent constructor
		parent::__construct();
		
		// check type
		if(!is_null($type)) {
			
			// get config
			$config = $this->getGc()->get_config('webservice.'.strtolower($type));
			if($config !== false) {
				$this->setConfig(json_decode($config, true));
			} else {
				$this->setConfig(array());
			}
			
			// set class variables
			$this->setType($type);
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * factory($type) creates and returns a new WebserviceJob* object according
	 * to $type
	 * 
	 * @param string $type type of the webservice object
	 * @return object WebserviceJob* object with the given data
	 */
	public static function factory($type) {
		
		// check $type to decide which object to create
		// prepare type
		$type = ucfirst(strtolower($type));
		$class = 'WebserviceJob'.$type;

		if($type != '' && (class_exists($class, false)) || @class_exists($class)) {
			$webservice = new $class($type);
		} else {
			$webservice = new WebserviceJob($type);
		}
		
		// return object
		return $webservice;
	}
	
	
	/**
	 * loadJobConfig($jobConfig) loads the job config into the object
	 * 
	 * @param array $jobConfig the array containing the job config
	 */
	public function loadJobConfig($jobConfig) {
		
		// set config
		$this->setJobConfig($jobConfig);
	}
	
	
	/**
	 * runJobs() checks if there are jobs to run and run them
	 * 
	 * @return array array containing the data used in the api response
	 */
	public final function runJobs() {
		
		// check if runnable jobs exists
		if($this->runnableJobsExist() === false) {
			return array(
					'result' => 'SKIPPED',
					'data' => array(),
				);
		} else {
			
			// lock job and get job id
			$jobId = $this->lockJob();
			
			// get job config
			$this->getJobConfigFromDb($jobId);
			$jobConfig = $this->getJobConfig();
			
			// create object
			$webserviceJob = WebserviceJob::factory($jobConfig['type']);
			// load job config
			$webserviceJob->loadJobConfig($jobConfig['config']);
			// run job and return result
			return $webserviceJob->runJob();
		}
	}
	
	
	/**
	 * runnableJobsExist() checks if there are jobs to run within this session
	 * 
	 * @return bool true if there are runnable jobs, false otherwise
	 */
	private function runnableJobsExist() {
		
		// get number of runnable jobs
		$result = Db::singleValue('
			SELECT COUNT(*)
			FROM `webservice_jobs`
			WHERE `session`=\'#?\'
				AND `locked`=0
		',
			array(md5(session_id()),));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return $result > 0;
		}
	}
	
	
	/**
	 * lockJob() locks the oldest job and returns its id
	 * 
	 * @return int the id of the locked job
	 */
	private function lockJob() {
		
		if(!Db::executeQuery('
			UPDATE `webservice_jobs` AS `wsj1`
				INNER JOIN
					(
					SELECT LAST_INSERT_ID(`id`) AS `id`
					FROM `webservice_jobs`
					WHERE `locked`=0
						AND `session`=\'#?\'
					ORDER BY `created` ASC
					LIMIT 1
					) AS `wsj2`
				ON `wsj1`.`id`=`wsj2`.`id`
			SET `wsj1`.`locked`=1
			WHERE `wsj1`.`id`=`wsj2`.`id`
		',
		array(md5(session_id()),))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			return Db::$insertId;
		}
	}
	
	
	/**
	 * getJobConfigFromDb($jobId) gets the job information for $jobId from database
	 * 
	 * @return array the job config from database
	 */
	private function getJobConfigFromDb($jobId) {
		
		// get the config field for given $jobId
		$result = Db::singleValue('
			SELECT `config`
			FROM `webservice_jobs`
			WHERE `id`=#?
		',
			array($jobId,));
		if($result === false) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		} else {
			$config = json_decode($result, true);
			$config['jobId'] = $jobId;
			$this->setJobConfig($config);
		}
	}
	
	
	/**
	 * insertJob() inserts the job given by $config
	 * 
	 * @param array $config config of the job to insert in database
	 */
	protected function insertJob($config) {
		
		if(!Db::executeQuery('
			INSERT INTO `webservice_jobs`
				(`id`, `session`, `locked`, `config`, `log`, `created`, `created_by`)
			VALUES
				(NULL, \'#?\', 0, \'#?\', NULL, CURRENT_TIMESTAMP, #?)
		',
		array(md5(session_id()), json_encode($config), $this->getUser()->get_id(),))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * saveJobResult($result) saves the given $result
	 * 
	 * @param array $result the result to be inserted in table
	 * @return void
	 */
	protected function saveJobResult($result) {
		
		// execute query
		if(!Db::executeQuery('
			INSERT INTO `webservice_results`
				(`id`, `webservice`, `table`, `table_id`, `value`, `created`, `created_by`)
			VALUES
				(NULL, \'#?\', \'calendar\', #?, \'#?\', CURRENT_TIMESTAMP, #?)
		',
		array(
				strtolower(substr($this->__toString(), 13)),
				$this->getJobConfig()['calendarId'],
				json_encode($result),
				$this->getUser()->get_id(),))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * deleteJobResult() deletes any result from job config
	 * 
	 * @return void
	 */
	protected function deleteJobResult() {
		
		// execute query
		if(!Db::executeQuery('
			DELETE FROM `webservice_results`
			WHERE `webservice`=#? 
				AND `table`=\'calendar\'
				AND `table_id`=#?
		',
		array(
				strtolower(substr($this->__toString(), 13)),
				$this->getJobConfig()['calendarId'],))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
	
	
	/**
	 * logJob($type, $message) logs the given $type and message to the job
	 * 
	 * @param string $type type of the log entry (ERROR, OK, ...)
	 * @param string $message the message to log
	 * @return void
	 */
	protected function logJob($type, $message) {
		
		// prepare log
		$log = array(
				'type' => $type,
				'date' => date('Y-m-d H:i:s'),
				'message' => $message,
			);
		
		// execute query
		if(!Db::executeQuery('
			UPDATE `webservice_results`
			SET `log`=\'#?\'
			WHERE `id`=#?
		',
		array(
				json_encode($log),
				$this->getJobConfig()['jobId'],))) {
			$n = null;
			throw new MysqlErrorException($n, '[Message: "'.Db::$error.'"][Statement: '.Db::$statement.']');
		}
	}
}