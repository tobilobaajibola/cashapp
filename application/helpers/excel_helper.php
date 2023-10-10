<?php 
// namespace Application\Controllers;
// use CodeIgniter\Controller;
// use CodeIgniter\HTTP\RequestInterface;
// use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

 function export_to_excel($transaction_histories, $transaction_event, $opening_balance, $account_balance, 
    $total_transaction,$customer_detail, $account_number, $transaction_start_date, $transaction_end_date){
    $ci = & get_instance();
    
          $fileName = $account_number.''.date('ymdhis').'.xlsx'; 
      $spreadsheet = new Spreadsheet();
  
  
      $sheet = $spreadsheet->getActiveSheet();
      // to disable edit of cell
      $sheet->getProtection()->setSheet(true);
      // $sheet->getColumnDimension('B:F')->setAutoSize(true);
    

      // Account Summary
      // $sheet->getStyle('B3:B7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
$styleAccountSummary = [
    'font' => [
        'bold' => true,
    ],
   
   'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => [
                    'rgb' => 'e41f13'
                 ]
        ]],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'E8E8E8',
        ],
        'endColor' => [
            'argb' => 'E8E8E8',
        ],
    ],
];


$styleStatementHeader = [
    'font' => [
        'bold' => true,
        'color' => ['rgb'=>'ffffff']
    ],
   
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'e41f13',
        ],
        'endColor' => [
            'argb' => 'e41f13',
        ],
    ],
];

$styleStatementList = [
    'font' => [
        // 'bold' => true,
        'color' => ['rgb'=>'000000']
    ],

     'borders' => [
        'bottom' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => [
                    'rgb' => 'e41f13'
                 ]
        ],
        'color' => [
                    'rgb' => 'e41f13'
                 ]],
  
];

      $sheet->setCellValue('B5', $customer_detail['NOMREST']);
      $sheet->setCellValue('B6', $customer_detail['CPRO'].' - '. $customer_detail['INTI']);
      $sheet->setCellValue('B7', $customer_detail['CUSTOMER_ADDRESS']);

      $sheet->getStyle('G5:G11')->applyFromArray($styleAccountSummary);
      $sheet->getColumnDimension('E')->setWidth(30, 'pt');
      $sheet->setCellValue('G5', 'Account Number:');
      $sheet->setCellValue('G6', 'Currency:');
      $sheet->setCellValue('G7', 'Opening Balance:');
      $sheet->setCellValue('G8', 'Total Credit');
      $sheet->setCellValue('G9', 'Total Debit:');
      $sheet->setCellValue('G10', 'Transaction Balance');
      $sheet->setCellValue('G11', 'Available Balance');


      $sheet->setCellValue('H5',  $account_balance['ACCTNO']);
      $sheet->setCellValue('H6',  $account_balance['CURRENCY']);
      $sheet->setCellValue('H7',  number_format($opening_balance['OPENING_BAL'], 2 ));
      $sheet->setCellValue('H8',  number_format($total_transaction['TOTAL_CREDITS'], 2 ));
      $sheet->setCellValue('H9',  number_format($total_transaction['TOTAL_DEBIT'], 2 ));
      $sheet->setCellValue('H10', number_format($account_balance['TRANS_BAL'], 2 ));
      $sheet->setCellValue('H11', number_format($account_balance['AVAIL_BAL'], 2 ));

      $sheet->setCellValue('F14', 'Statement Cycle');
      $sheet->setCellValue('G14', $transaction_start_date);
      $sheet->setCellValue('H14', 'to'.$transaction_end_date);
      // statement header

      $sheet->getColumnDimension('B')->setWidth(20, 'pt');
      $sheet->getColumnDimension('C')->setWidth(20, 'pt');
      $sheet->getColumnDimension('D')->setWidth(15, 'pt');
      $sheet->getColumnDimension('E')->setWidth(15, 'pt');
      $sheet->getColumnDimension('F')->setWidth(50, 'pt');
      $sheet->getColumnDimension('G')->setWidth(15, 'pt');
      $sheet->getColumnDimension('H')->setWidth(15, 'pt');
      $sheet->getRowDimension('15')->setRowHeight(30, 'pt');
      $sheet->getStyle('B15:I15')->applyFromArray($styleStatementHeader);
      $sheet->setCellValue('B15', 'Transaction Date');
      $sheet->setCellValue('C15', 'Value Date');
      $sheet->setCellValue('D15', 'Branch');
      $sheet->setCellValue('E15', 'Reference');
      $sheet->setCellValue('F15', 'Description');
      $sheet->setCellValue('G15', 'Deposit');
      $sheet->setCellValue('H15', 'Withdrawal');
      $sheet->setCellValue('I15', 'Balance');


      $rows = 16;
      
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Taj Bank Logo');
    $drawing->setDescription('TajBank');
    $drawing->setPath('assets/images/tajbg.png'); /* put your path and image here */
    $drawing->setCoordinates('B2');
    // $drawing->setOffsetX(110);
    // $drawing->setRotation(25);
    // $drawing->getShadow()->setVisible(true);
    // $drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet($sheet);
      foreach ($transaction_histories as $transaction_history){
    if($transaction_history['TYPE']== 'deposit') {$deposit_amount = $transaction_history['AMOUNT'];} else{$deposit_amount = '0.00';}; 
    if($transaction_history['TYPE']== 'withdrawal') {$withdraw_amount = $transaction_history['AMOUNT'];} else{$withdraw_amount = '0.00';}; 

     // remove transaction with zero naira or other currency
            if ($transaction_history['MON1'] == 0  and $transaction_history['NCP1']==$account_balance['ACCTNO']){
                    // show nothing
            }
                else{
                //remove breakedown of bulk multiple transaction from debitted account
            if ($transaction_history['TYP']== 150 and $transaction_history['NAT'] <> 'VIRMUL' and $transaction_history['NCP1'] ==  $account_balance['ACCTNO']) {
                    // show nothing
                }
                else{

// if ($transaction_history['MON1'] != 0 and $transaction_history['SOL1'] != 0){
      $sheet->getStyle('B'.$rows.':F'.$rows)->applyFromArray($styleStatementList);
      $sheet->getRowDimension($rows)->setRowHeight(20, 'pt');
          $sheet->setCellValue('B' . $rows, $transaction_history['DSAI']);
          $sheet->setCellValue('C' . $rows, $transaction_history['DATEH']);
          $sheet->setCellValue('D' . $rows, $transaction_history['AGE']);
          $sheet->setCellValue('E' . $rows, '');
          $sheet->setCellValue('F' . $rows, $transaction_history['NARRATION']);
          $sheet->setCellValue('G' . $rows, $deposit_amount);
          $sheet->setCellValue('H' . $rows, $withdraw_amount);
          $sheet->setCellValue('I' . $rows, $transaction_history['AVAILABLE_BALANCE']);
          $rows++;
      }
      }
      } 
      // load for current transaction
      foreach ($transaction_event as $transaction_events){
   if ($transaction_events['MON1'] != 0 and $transaction_events['SOL1'] != 0){
    if($transaction_events['TYPE']== 'deposit') {$deposit_amount = $transaction_events['AMOUNT'];} else{$deposit_amount = '0.00';}; 
    if($transaction_events['TYPE']== 'withdrawal') {$withdraw_amount = $transaction_events['AMOUNT'];} else{$withdraw_amount = '0.00';}; 
    
      $sheet->getStyle('B'.$rows.':F'.$rows)->applyFromArray($styleStatementList);
      $sheet->getRowDimension($rows)->setRowHeight(20, 'pt');
          $sheet->setCellValue('B' . $rows, $transaction_events['DSAI']);
          $sheet->setCellValue('C' . $rows, $transaction_events['DATEH']);
          $sheet->setCellValue('D' . $rows, $transaction_events['AGE']);
          $sheet->setCellValue('E' . $rows, '');
          $sheet->setCellValue('F' . $rows, $transaction_events['NARRATION']);
          $sheet->setCellValue('G' . $rows, $deposit_amount);
          $sheet->setCellValue('H' . $rows, $withdraw_amount);
          $sheet->setCellValue('I' . $rows, $transaction_events['AVAILABLE_BALANCE']);
         $rows++;
      }
      } 
      // $sheet->getStyle('B15:F15')->applyFromArray($styleStatementHeader);

      $writer = new Xlsx($spreadsheet);
      $writer->save("statement/".$fileName);
      header("Content-Type: application/vnd.ms-excel");

            $statement_file_data = array('no_of_pages' => '',
                                      'amount_to_charge' => '',
                                      'account_to_charge' => $customer_detail['NCP'],
                                      'format' => 'excel',
                                      'filename'=>'',
                                     'download_link' =>'<a target="_blank" class="alert text-success" href='.base_url().'viewstatement/'.$fileName.'>Download</a>',
                                     'response' => 'Message has been sent' );

    echo json_encode(['success'=>  $statement_file_data]);

  }

?>