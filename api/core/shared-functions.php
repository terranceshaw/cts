<?php
include_once "core.php";

// Easier mail helper dealie doodler.
function sendMail($to, $subject, $message, $asHTML = false) {
    ini_set("SMTP", "ex01.cvn77.navy.mil");
    ini_set("smtp_port", 25);
    $headers = 'From: noreply@cvn77.navy.mil' . "\r\n" .
               'Reply-To: noreply@cvn77.navy.mil' . "\r\n" .
               'X-Mailer: PHP/' . phpversion() . "\r\n" .
               'Content-type: text/html;charset=iso-8859-1' . "\r\n" . 
               'MIME-Version: 1.0' . "\r\n";
    if ($asHTML == true) {
        $message = "
            <!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" 
            \"http://www.w3.org/TR/html4/loose.dtd\">
            <html>
            <body style=\"font-family: Calibri, Tahoma, Arial, sans-serif\">
                $message
            </body>
            </html>
        ";
    }
    if (mail($to, $subject, $message, $headers)) {
        return "Send successful.";
    } else {
        return "Unable to send.";
    }
}

// Allows us to access the APIs we got.
function curl($url,$postVars=null) {
    $json_url = $url;  
    $crl = curl_init();
    curl_setopt($crl, CURLOPT_URL, $json_url);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($crl, CURLOPT_ENCODING, '');
    curl_setopt($crl, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
    if ($postVars) {
        // Used only if we're sending information to be processed.
        // Default action (no post vars) is to just fetch data.
        curl_setopt($crl, CURLOPT_POST, 1);
        curl_setopt($crl, CURLOPT_POSTFIELDS, curl_postfields_flatten($postVars));
    }
    $json = curl_exec($crl);
    curl_close($crl);
    return json_decode($json, TRUE);
}

function curl_postfields_flatten($data, $prefix = '') {
    // Used to flatten the POST vars with cURL if it's a multidimensional array.
    // https://stackoverflow.com/questions/3772096/posting-multidimensional-array-with-php-and-curl
    if (!is_array($data)) {
        return $data; // in case someone sends an url-encoded string by mistake
    }

    $output = array();
    foreach($data as $key => $value) {
        $final_key = $prefix ? "{$prefix}[{$key}]" : $key;
        if (is_array($value)) {
        // @todo: handle name collision here if needed
        $output += curl_postfields_flatten($value, $final_key);
        }
        else {
        $output[$final_key] = $value;
        }
    }
    return $output;
}

// Used to streamline select list item generation.
// Made this because it's annoying to change the functionality across various lists.
function listGenerator($postURL, $postVars=null, $selectedValue=null) {
    $results = curl($postURL, $postVars);
    if ($results['status'] == 500) {
        // There was an error with the fetch, or there are no list items.
        $message = $results['message'];
        echo "<option>$message</option>\n";
    } else {
        $listItems = $results['message'];
        foreach ($listItems as $listItem) {
            $listItemID = $listItem['id'];
            $listItemName = $listItem['name'];
            if (!empty($selectedValue) && $listItemID == $selectedValue) {
                // If we've got some key/value pairs, let's select the right thing!
                echo "<option value=\"$listItemID\" selected>$listItemName</option>\n";
            } else {
                echo "<option value=\"$listItemID\">$listItemName</option>\n";
            }
        }
    }
}

// Used to streamline generating checklists.
function checklistGenerator($checklistName, $postURL, $postVars=null) {
    $results = curl($postURL, $postVars);
    if ($results['status'] == 500) {
        // There was an error with the fetch, or there are no list items.
        $message = $results['message'];
        echo "<span><strong>Error generating list: </strong>$message</span>\n";
    } else {
        $listItems = $results['message'];
        foreach ($listItems as $listItem) {
            $listItemID = $listItem['id'];
            $listItemName = $listItem['name'];
            echo "<label for=\"user$listItemID\" class=\"checklist\"><input type=\"checkbox\" name=\"$checklistName\" id=\"user$listItemID\" value=\"$listItemID\"> $listItemName</label>\n";
        }
    }
}

// Sanitize input

function sanitize($value) {
    global $connection;
    if (is_array($value)) {
        foreach ($value as &$item) {
            $item = htmlspecialchars($item);
        }
    } else {
        $value = htmlspecialchars($value);
    }
    return $value;
}

function pre_array($array) {
    echo "<pre>\n";
    print_r($array);
    echo "</pre>\n";
}

// Convert provided date to MySQL-friendly date.
function mySQLDate($date) {
    return date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date)));
}

// Helper methods to return success or failures.

function success($message) {
    // The message can be either a string or an array.
    echo json_encode(array("status"=>200, "message"=>$message));
}

function error($message) {
    // Message can be either a string or an array, but should prolly just be a string.
    echo json_encode(array("status"=>500, "message"=>$message));
}

// CORS Nonsense

function cors() {
    // https://stackoverflow.com/questions/8719276/cross-origin-request-headerscors-with-php-headers
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
    
    echo "You have CORS!";
}

?>