<?php

require_once($_SERVER['DOCUMENT_ROOT']."/xxx/connectionManager/connectionManager.php");
class FavouriteDAO {
    
    /**
     * constructor
     */
     public function FavouriteDAO(){
        //contructor
    }

    /**
     * 
     * @param type $favouriteBO
     * @return boolean
     * @throws Exception
     */
   
    public function saveFavourite($favouriteBO) {
        try {
             $valid = $this->validateFavouriteData($favouriteBO);
            if($valid['total'] == 0){
                $connectionManager = new ConnectionManager();
                $connection = $connectionManager->getConnection();

                // Save Query for favourite table
                $sql = "INSERT INTO favourite " .
                        "(email_id,tutorial_id)" .
                        "VALUES ('" . addslashes(trim($favouriteBO->getEmailId())) . "','"
                        . addslashes(trim($favouriteBO->getTutorialId())) . "')";
                 
                // Execute the Query if connection exist
                if ($connection) {
                    $success = mysql_query($sql);
                }
                if ($success) {
                    return true;
                } else {
                    return false;
                }
            }else {
                
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("MESSAGE:" . $e->getMessage());
        }
    }

// End of function

    /**
     * 
     * @param type $favouriteBO
     * @return boolean
     * @throws Exception
     */
    public function deleteFavourite($favouriteBO) {
        try {
            $valid = $this->validateFavouriteData($favouriteBO);
            if($valid['total'] != 0){
                $connectionManager = new ConnectionManager();
                $connection = $connectionManager->getConnection();

                // delete query for favourite table
                $sql = "DELETE FROM favourite " .
                        "WHERE email_id = '" . addslashes(trim($favouriteBO->getEmailId())) . "' AND tutorial_id = '"
                        . addslashes(trim($favouriteBO->getTutorialId())) . "'";
                 
                // Execute the Query if connection exist
                if ($connection) {
                    $success = mysql_query($sql);
                }
                
                if ($success) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("MESSAGE:" . $e->getMessage());
        }
    }
    
        /**
	 * Method to fetch favorite  record
	 *
	 * @param $favouriteBO
	 */
	public function getallfavourite($favouriteBO)
	{

		try
		{
			$connection=new ConnectionManager();
			$link=$connection->getConnection();

			// select query for favourite table
			
			$email_id = $favouriteBO->getEmailId();

			$query="SELECT tutorial_id FROM favourite WHERE email_id='$email_id' ORDER BY id ASC";
			$result = mysql_query($query,$link)or die(mysql_error());
			$favouritesArray = array();
			  		
			while($success = mysql_fetch_object($result)){  
                            $favourites = new FavouriteBO();
				
                        $favourites->setTutorialId($success->tutorial_id); 
                        $favourites->getTutorialId();
                        //storing the objects in an array
                        $favouritesArray[] = $favourites; 
			}
                        
                        return $favouritesArray;
//			
		}catch(Exception $e){
			throw new Exception("MESSAGE:".$e->getMessage());
		}
			
	}	// End of function

    
    
    /**
     * 
     * @param type $favouriteBO
     * @return type
     * @throws Exception
     */
    private function validateFavouriteData($favouriteBO){
        
        try{
            $connectionManager = new ConnectionManager();
            $connection = $connectionManager->getConnection();
            
            $sql = "SELECT count(id) as total from favourite where email_id = '".addslashes(trim($favouriteBO->getEmailId()))."' AND tutorial_id = '"
                    . addslashes(trim($favouriteBO->getTutorialId()))."'";
                   
            if($connection){
                $success = mysql_query($sql);
                $result = mysql_fetch_assoc($success);
                return $result;
            }
        }catch (Exception $e) {
            throw new Exception("MESSAGE:" . $e->getMessage());
        }
        
        
        
    }
    /**
	 * Method to check email already exists or not
	 *
	 * @param $email
	 */
	public function checkEmailExistence($favouriteBO)
	{
		try
		{
			$connection=new ConnectionManager();
			$link=$connection->getConnection();
			 
				
			$email_id = $favouriteBO->getEmailId();
			// Fetch Query for debtorData table

			$query="SELECT id FROM favourite WHERE email_id='$email_id'";
			$result = mysql_query($query,$link);
			$success = mysql_fetch_assoc($result);

			$xid = intval($success['id']);
			if($xid)
			{
				return $xid;
			}
			else
			{
				return NULL;
			}
		}catch(Exception $e){
			throw new Exception("[MESSAGE:".$e->getMessage() ."]<br />[CLASS:debtorDAO][FUNCTION:saveDebtor]" );
		}
			
	}	// End of function


}

?>
