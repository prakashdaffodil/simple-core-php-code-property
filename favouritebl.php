<?php

include($_SERVER['DOCUMENT_ROOT']."/makeupdefineddev/connectionManager/connectionManager.php");
require_once($_SERVER['DOCUMENT_ROOT']."/makeupdefineddev/dao/favouritedao.php");
require_once($_SERVER['DOCUMENT_ROOT']."/makeupdefineddev/bo/favouritebo.php");

class FavouriteBL {

    /**
     * 
     * @param type $favouriteBO
     * @return boolean
     * @throws Exception
     */
    public function save($favouriteBO) {
        try {
            $favouriteDAO = new FavouriteDAO();
            $success = $favouriteDAO->saveFavourite($favouriteBO);
            if ($success == true) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("MESSAGE:" . $e->getMessage());
        }
    }
    /**
     * 
     * @param type $favouriteBO
     * @return boolean
     * @throws Exception
     */
    public function delete($favouriteBO) {

        try {
            $favouriteDAO = new FavouriteDAO();
            $success = $favouriteDAO->deleteFavourite($favouriteBO);
            
            if ($success == true) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("MESSAGE:" . $e->getMessage());
        }
    }
    
    /**
	 * Method for getting favourite
	 *
	 *
	 * @param string $favouriteBO
	 */

	public function getallfavourite($favouriteBO){
			
		try{
			$favouriteDAO = new FavouriteDAO();
			$success=$favouriteDAO->getallfavourite($favouriteBO);
                        return $success;
                        
                    }catch(Exception $e){
			throw new Exception("MESSAGE:".$e->getMessage());
                   }



	}
        /**
	 * Method for getting favourite
	 *
	 *
	 * @param string $favouriteBO
	 */

	public function checkEmailExistence($favouriteBO){
			
		try{
			$favouriteDAO = new FavouriteDAO();
			$success=$favouriteDAO->checkEmailExistence($favouriteBO);

			if ($success)
			{
					
				return $success;

			}

			else
			{
				return NULL;
			}


		}catch(Exception $e){
			throw new Exception("MESSAGE:".$e->getMessage());
		}



	}

}

?>