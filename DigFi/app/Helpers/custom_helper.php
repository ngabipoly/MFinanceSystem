<?php
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

helper('filesystem');
/**
 * Writes a log entry to a file.
 *
 * @param string $content The content to be written to the log.
 * @throws Exception If there is an error writing to the log file.
 */
    function writeLog(string $content){
        $log_file = APPLOG.'log_'.date('Ymd').'.log';

        if (!file_exists($log_file)) {
            //log  File doesn't exist, create a new file and write to it
            write_file($log_file, date('Y-m-d h:i:s')." - $content");
        } else {
            //append the text content to it
            file_put_contents($log_file, "\r\n".date('Y-m-d h:i:s')." - $content", FILE_APPEND);
        }
    }

/**
 * Checks if a substring of a string matches a given pattern and optionally a specified length.
 *
 * @param string $input_string The input string to be checked.
 * @param int $begin_pos The starting position of the substring.
 * @param int $end_pos The ending position of the substring.
 * @param string $pattern The pattern to match.
 * @param int|null $length The optional length to check against.
 * @return bool Returns true if the substring matches the pattern and the specified length (if provided), false otherwise.
 */
    function checkNumber($input_string,$begin_pos,$end_pos,$pattern,$length=null) {

        if($length){
            if (substr($input_string, $begin_pos, $end_pos) === $pattern && strlen($input_string) === $length) {
                return true;
            }                   
        }

        /*** 
         * when no length is specified
         * only Check if the contains a specific pattern
         ***/
        if (substr($input_string, $begin_pos, $end_pos) === $pattern) {
            return true;
        } 
            return false;              

    }

function generatePassword($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        $charactersLength = strlen($characters);
        $randomPassword = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }    
        return $randomPassword;
    }  

function logout() {
    $session = session();
    $session->destroy();
    return redirect()->to(base_url());
}

function showError($message)
{
   $message= date("Y-m-d H:i:s")." ERROR:: ".$message;
    CLI::write($message);
}

function showMessage($message)
{
    $message= date("Y-m-d H:i:s")." INFO:: ".$message;
    CLI::write($message);
}

    /**
     * @param string $actionPerformed
     * @param Model $model
     * @param array $data
     * @return string
     */
    function saveData($actionPerformed, $model, $data): string
    {
        try {
            $saveDetails = $model->save($data);

            if (!$saveDetails) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Action ' . $actionPerformed. ' failed',
                    'data' => ["Error" => $model->errors(), "Data" => $data]
                ]);
            }

            return json_encode([
                'status' => 'success',
                'message' => 'Action ' . $actionPerformed . ' successful',
                'data' => ["ID" => $model->insertID()]
            ]);

        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Action ' . $actionPerformed. ' failed',
                'data' => ["Error" => $e->getMessage(), "Data" => $data]
            ]);
        }
    }
    

