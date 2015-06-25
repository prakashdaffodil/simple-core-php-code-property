<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . "/makeupdefineddev/bl/favouritebl.php");
require_once( $_SERVER['DOCUMENT_ROOT'] . "/makeupdefineddev/bo/favouritebo.php");

class FavouriteController {

    /**
     * constructor for FavouriteController 
     * managing function call on basis of defined task
     */
    public function FavouriteController() {

        if (!empty($_REQUEST["task"])) {
            $task = $_REQUEST["task"];
            $data = $_REQUEST["data"];
        }
        $this->$task($data);
    }
    
     /**
     * function to check that whether email is valid or not
     * @param type $email
     * @return type
     */
   public function isvalidemail($email) 
    {
         return preg_match('/^[\w.-]+@([\w.-]+\.)+[a-z]{2,6}$/is', $email);
    }

    /**
     *  Function to manage function call on basis of requested parameter
     * @throws Exception
     */
    public function addFavourite() {
        try {
            $data_json = $_REQUEST['data'];
            $data = json_decode($data_json, true);
            
            $validArray = array('email_id' => '', 'tutorial_id' => '', 'operation' => '');
            if (empty($data)) {
                $error = array('status_code' => ERR_DATA_CODE, 'result_object' => '', 'status_message' => MSG_DATA_NOT_PROVIDED);
                echo json_encode($error);
                exit;
            } else {
                $this->checkData($validArray, $data);
                $validemail = $this->isvalidemail(trim($data['email_id']));
                //var_dump($validemail);exit;
                if (isset($data['operation']) && is_numeric(trim($data['operation'])) && $data['operation'] == 1 && is_numeric(trim($data['tutorial_id'])) && $validemail == 1 ) {
                    $this->addFavouriteData($data);
                } else if (isset($data['operation']) && is_numeric(trim($data['operation'])) && $data['operation'] == 0 && is_numeric(trim($data['tutorial_id'])) && $validemail == 1) {
                    $this->deleteFavourite($data);
                } else {
                    $error = array('status_code' => ERRCODE_INVALID_VALUE, 'result_object' => 'failure', 'status_message' => MSG_RESULT_INVALID_VALUE);
                    echo json_encode($error);
                    exit;
                }
            }
        } catch (Exception $e) {
            throw new Exception("MESSAGE:" . $e->getMessage());
        }
    }

    /**
     * function to add favourites
     * @throws Exception
     */
    public function addFavouriteData($data) {

        try {
            $favouriteBL = new FavouriteBL();
            $emailId = $data['email_id'];
            $tutorialId = $data['tutorial_id'];
            $favouriteBO = new FavouriteBO();
            $favouriteBO->setEmailId($emailId);
            $favouriteBO->setTutorialId($tutorialId);
            $success = $favouriteBL->save($favouriteBO);
            if ($success == true) {
                $error = array('status_code' => ERRCODE_RESULT_SUCCESS, 'result_object' => 'success', 'status_message' => MSG_RESULT_SUCCESS);
                echo json_encode($error);
                exit;
            } else {
                $error = array('status_code' => ERRCODE_RESULT_UNSUCCESS, 'result_object' => 'failure', 'status_message' => MSG_RESULT_UNSUCCESS);
                echo json_encode($error);
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("MESSAGE:" . $e->getMessage());
        }
    }

    /*     * function to delete the Favourite
     * Delete the favourites by email_id and tutorial_id
     * @throws Exception
     */

    public function deleteFavourite($data) {

        try {
            $favouriteBL = new FavouriteBL();
            $emailId = $data['email_id'];
            $tutorialId = $data['tutorial_id'];
            $favouriteBO = new FavouriteBO();
            $favouriteBO->setEmailId($emailId);
            $favouriteBO->setTutorialId($tutorialId);

            $success = $favouriteBL->delete($favouriteBO);
            if ($success == true) {
                $error = array('status_code' => ERRCODE_RESULT_SUCCESS, 'result_object' => 'success', 'status_message' => MSG_RESULT_SUCCESS);
                echo json_encode($error);
                exit;
            } else {
                $error = array('status_code' => ERRCODE_RESULT_INVALID, 'result_object' => 'failure', 'status_message' => MSG_RESULT_INVALID);
                echo json_encode($error);
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("MESSAGE:" . $e->getMessage());
        }
    }

    /**
     * Method for fetching favourites
     *
     * @param email
     * @return boolean
     * @throws Exception
     */
    public function getallfavourite() {

        try {

            $data = json_decode($_REQUEST['data'], 'true');
            $validArray = array('email_id' => '');
            if (empty($data)) {
                $error = array('status_code' => ERR_DATA_CODE, 'result_object' => '', 'status_message' => MSG_DATA_NOT_PROVIDED);
                echo json_encode($error);
                exit;
            } else {
                $this->checkData($validArray, $data);
                $favouriteBL = new FavouriteBL();
                $favouriteBO = new FavouriteBO();
                $emailId = $data['email_id'];
                $favouriteBO = new FavouriteBO();
                $favouriteBO->setEmailId($emailId);
                $success = $favouriteBL->getallfavourite($favouriteBO);
                if(is_array($success) && count($success)>0){

                    foreach ($success as $favObject) {
                        $response['favourites'][]['tutorial_id'] = $favObject->getTutorialId();

                    }

                    $error = array('status_code' => ERRCODE_RESULT_SUCCESS, 'result_object' => $response, 'status_message' => MSG_RESULT_SUCCESS);
                    echo json_encode($error);
                    exit;

                    }
                    else {
                        $response['favourites'] = $success;
                        $error = array('status_code' => ERRCODE_RESULT_SUCCESS, 'result_object' => $response, 'status_message' => MSG_RESULT_SUCCESS);
                        echo json_encode($error);
                        exit;
                    }
                   
              
            }
            
           }  catch (Exception $e) {
                     throw new Exception($e->getMessage());
        }
    }

    /**
     * function to check that requested fields are valid or not
     * @param type $validArray
     * @param type $data
     */
    function checkData($validArray, $data) {
        foreach ($validArray as $key => $value) {
            if (!array_key_exists($key, $data)) {
                $error = array('status_code' => ERR_DATA_CODE, 'result_object' => '', 'status_message' => MSG_DATA_NOT_PROVIDED . $key);
                echo json_encode($error);
                exit;
            } elseif (trim($data[$key]) == '') {
                $error = array('status_code' => ERR_DATA_CODE, 'result_object' => '', 'status_message' => MSG_VALUE_NOT_PROVIDED . $key);
                echo json_encode($error);
                exit;
            }
        }
    }
    

}

?>