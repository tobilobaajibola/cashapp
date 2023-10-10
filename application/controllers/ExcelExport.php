<?php namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 

 
class ExcelExport extends Controller
{
 
 
  public function index() {
 
      $db      = \Config\Database::connect();
      $builder = $db->table('users');   
 
      $query = $builder->query("SELECT * FROM users");
 
      $users = $query->getResult();
       
      $fileName = 'users.xlsx';  
      $spreadsheet = new Spreadsheet();
 
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setCellValue('A1', 'Id');
      $sheet->setCellValue('B1', 'Name');
      $sheet->setCellValue('C1', 'Skills');
      $sheet->setCellValue('D1', 'Address');
      $sheet->setCellValue('E1', 'Age');
      $sheet->setCellValue('F1', 'Designation');       
      $rows = 2;
 
      foreach ($users as $val){
          $sheet->setCellValue('A' . $rows, $val['id']);
          $sheet->setCellValue('B' . $rows, $val['name']);
          $sheet->setCellValue('C' . $rows, $val['skills']);
          $sheet->setCellValue('D' . $rows, $val['address']);
          $sheet->setCellValue('E' . $rows, $val['age']);
          $sheet->setCellValue('F' . $rows, $val['designation']);
          $rows++;
      } 
      $writer = new Xlsx($spreadsheet);
      $writer->save("upload/".$fileName);
      header("Content-Type: application/vnd.ms-excel");
      redirect(base_url()."/upload/".$fileName); 
  }
 
}