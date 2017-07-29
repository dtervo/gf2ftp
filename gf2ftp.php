<?php
/*
Plugin Name: Gravity Forms FTP Export
Plugin URI: https://tervosystems.com
Description: Exports submitted entries and sends them as a .csv to an FTP Location.
Version: 1.2
License: GPL
Author: Dan Tervo
Author URI: dantervo.com
*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
     die;
}

/* Below is the function for the Gravity Forms Webhook */

add_action( 'gform_after_submission_1', 'post_to_ftp', 10, 2 );

function post_to_ftp( $entry, $form ) {
   
     $data_meta = array (
          '1.2' => 'Contact Name (Prefix)',
          '1.3' => 'Contact Name (First)',
          '1.4' => 'Contact Name (Middle)',
          '1.6' => 'Contact Name (Last)',
          '1.8' => 'Contact Name (Suffix)',
          '2' => 'Company',
          '4' => 'Email (Enter Email)',
          '4.2' => 'Email (Confirm Email)',
          '5' => 'Phone',
          '3.1' => 'Address (Street Address)',
          '3.2' => 'Address (Address Line 2)',
          '3.3' => 'Address (City)',
          '3.4' => 'Address (State / Province)',
          '3.5' => 'Address (ZIP / Postal Code)',
          '3.6' => 'Address (Country)',
          '6.1' => '1000-01-101 (Name)',
          '6.2' => '1000-01-101 (Price)',
          '6.3' => '1000-01-101 (Quantity)',
          'id' => 'Entry Id',
          'date_created' => 'Entry Date',
          'ip' => 'User IP',
     );

     $conn = array(
          'server' => 'FTP_SERVER',
          'username' => 'FTP_USERNAME',
          'password' => 'FTP_PASSWORD'
     );

     //DO NOT EDIT BELOW HERE.


     $data = array();
     $id = rgar( $entry, 'id' );


     $filename = 'quote-request-' . date('Y-m-d') . '-' . $id . '.csv';
     $ftp_path = "ftp://{$conn['username']}:{$conn['password']}@{$conn['server']}/{$filename}";

     $stream_options = array('ftp' => array('overwrite' => true));
     $stream_context = stream_context_create($stream_options);

     $fp = fopen($ftp_path, 'w', 0, $stream_context);

     foreach ($data_meta as $fid => $flabel) {
          $data[] = rgar( $entry, $fid );
     }

     fputcsv($fp, $data_meta);
     fputcsv($fp, $data);

     fclose($fp);

}
