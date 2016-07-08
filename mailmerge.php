<?php
require_once 'lib/swift_required.php';

// Create the Transport
$transport = Swift_SmtpTransport::newInstance("relay-hosting.secureserver.net", 25);


// Create the Mailer using your created Transport
$mailer = Swift_Mailer::newInstance($transport);

// Create a message
$message = Swift_Message::newInstance('FARE Walk Press Release')
  ->setFrom(array('srosssd@alvasb.com' => 'Kathleen Dempsey'))
  ->setReplyTo('ksdempsey@verizon.net')
  ->setTo(array($_POST["email"]))
  ->setBody('Dear '.$_POST["name"].",\n\nPlease see the attached press release.")
  ->attach(Swift_Attachment::fromPath('NOVAPressRelease.pdf'))
  ;

// Send the message
$result = $mailer->send($message);
echo $result." - ".$_POST["name"];
?>