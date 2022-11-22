 <?php
/*
This call sends a message based on a template.
*/
// require 'vendor/autoload.php';
// use Mailjet\Client;
// use \Mailjet\Resources;


// class Mail

// {

//     private $api_key="f4e64f781dc8108143bdf35d5cf0f000";
//     private  $api_key_private="8cecb1b27fc664c2dee6b7d24446b88e";

//     public function send($mailTo, $name, $subject, $template)
//     {
//         $mj = new Client($this->api_key, $this->api_key_private, true, ['version' => 'v3.1']);
        
        
//         $body = [
//             'Messages' => [
//                 [
//                     'From' => [
//                         'Email' => "guotaam@gmail.com",
//                         'Name' => "laveranda"
//                     ],
//                     'To' => [
//                         [
//                             'Email' => $mailTo,                             
//                             'Name' => $name
//                             ]
//                         ],
//                         'TemplateID' => 4368378,
//                         'TemplateLanguage' => true,
//                      'Subject' => $subject,

//                         ]
//                         ]
//                     ];
//                     $response = $mj->post(Resources::$Email, ['body' => $body]);
//                     $response->success() && var_dump($response->getData());
                    
//     }
// } 
