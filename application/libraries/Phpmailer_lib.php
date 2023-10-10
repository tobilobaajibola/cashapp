<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter PHPMailer Class
 *
 * This class enables SMTP email with PHPMailer
 *
 * @category    Libraries
 * @author      CodexWorld
 * @link        https://www.codexworld.com
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class PHPMailer_Lib
{
    public function __construct(){
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load(){
        // Include PHPMailer library files
        require_once APPPATH.'third_party/PHPMailer/Exception.php';
        require_once APPPATH.'third_party/PHPMailer/PHPMailer.php';
        require_once APPPATH.'third_party/PHPMailer/SMTP.php';
        
        $mail = new PHPMailer;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->isSMTP();
        $mail->Host     = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tajenterprisesuit@tajbank.com';
        $mail->Password = '@alertpr_321';
        $mail->SMTPSecure = 'tls';
        $mail->Port     = 587;
        $mail->SMTPDebug = 0;
        
        return $mail;
    }
}