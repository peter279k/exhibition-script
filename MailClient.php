<?php
/**
 * This call sends an email to one recipient, using a validated sender address
 * Do not forget to update the sender address used in the sample
*/

use \Mailjet\Resources;
use \Mailjet\Response;
use \Mailjet\Client;

class MailClient {

    private $mailClient;
    private $publicKey;
    private $privateKey;
    private $subject;
    private $body;
    private $response = '';
    private $fromAddress = [];
    private $toAddress = [];
    private $htmlPart = '';
    private $textPart = '';

    public function __construct() {
    }

    public function getMailClient() {

        return $this->mailClient = new Client($this->publicKey, $this->privateKey);
    }

    public function setFromAddress(array $fromAddress) {

        $this->fromAddress = $fromAddress;
    }

    public function getFromAddress() {

        return $this->fromAddress;
    }

    public function setToAddress(array $toAddress) {

        $this->toAddress = $toAddress;
    }

    public function getToAddress() {

        return $this->toAddress;
    }

    public function setPublicKey($publicKey) {

        $this->publicKey = $publicKey;
    }

    public function getPublicKey() {

        return $this->publicKey;
    }

    public function setPrivateKey($privateKey) {

        $this->privateKey = $privateKey;
    }

    public function getPrivateKey() {

        return $this->privateKey;
    }

    public function setSubject($subject) {

        $this->subject = $subject;
    }

    public function getSubject() {

        return $this->subject;
    }

    public function setHtmlPart($htmlPart) {

        $this->htmlPart = $htmlPart;
    }

    public function getHtmlPart() {

        return $this->htmlPart;
    }

    public function setTextPart($textPart) {

        $this->textPart = $textPart;
    }

    public function getTextPart() {

        return $this->textPart;
    }

    public function getBody() {

        $this->body = [
            'FromEmail' => $this->fromAddress['Email'],
            'FromName' => $this->fromAddress['Name'],
            'Subject' => $this->subject,
            'Text-part' => $this->textPart,
            'Html-part' => $this->htmlPart,
            'Recipients' => [
                [
                    'Email' => $this->toAddress['Email'],
                    'Name' => $this->toAddress['Name']
                ]
            ]
        ];

        return $this->body;
    }

    public function setResp(Response $response) {

        $this->response = $response;
    }

    public function getResp() {

        return $this->response;
    }

    public function sendMail() {

        $response = $this->getMailClient()->post(Resources::$Email, ['body' => $this->getBody()]);
        $this->setResp($response);
    }
}
