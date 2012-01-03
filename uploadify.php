<?php
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
define('BASEPATH', true);
define('ENVIRONMENT', 'production');

//$myFile = "testFile.txt";
//$fh = fopen($myFile, 'w') or die("can't open file");
//fwrite($fh, "checking file" . "\n\r"); //put as much copies of this line as you need

if (!empty($_FILES)) {
	require('application/config/database.php');

	$success = mysql_pconnect ($db['default']['hostname'], $db['default']['username'], $db['default']['password']);

	$success = mysql_select_db ($db['default']['database']);
	mysql_query('SET NAMES utf8 COLLATE utf8_general_ci');
	$id = intval($_POST['modelId']);
	$result = mysql_query('SELECT model FROM phones WHERE id = ' . $id . ' LIMIT 1');
	$row = mysql_fetch_array($result);
	$prefix = $_POST['img'] == 'image' ? '' : $_POST['img'];
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';

	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];


	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);

	// if (in_array($fileParts['extension'],$typesArray)) {
		// Uncomment the following line if you want to make the directory if it doesn't exist
		mkdir(str_replace('//','/',$targetPath), 0755, true);

		if(move_uploaded_file($tempFile,$targetFile)){
			mysql_query('UPDATE phones SET ' . $prefix . 'image = "' . $row['model'] . '/' . $_FILES['Filedata']['name'] . '"' . ' WHERE id = ' . $id . ' LIMIT 1');
		}
		echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	// } else {
	// 	echo 'Invalid file type.';
	// }
}

//fclose($fh); //text is written here