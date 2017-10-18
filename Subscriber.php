<?php

class Subscriber {

    private $templateFile = 'template/template.html';
    private $newsLetterTitle = '';
    private $newsLetterContent = '';
    private $footerTitle = '';
    private $serviceName = '';
    private $mailListFile = '';

    public function __construct() {
    }

    public function sendMail(\MailClient $mailClient) {

        $mailClient->sendMail();

        return $mailClient->getResp();
    }

    public function setTemplateFile($templateFile) {

        $this->templateFile = $templateFile;
    }

    public function getTemplateFile() {

        return $this->templateFile;
    }

    public function setNewsLetterTitle($newsLetterTitle) {

        $this->newsLetterTitle = $newsLetterTitle;
    }

    public function getNewsLetterTitle() {

        return $this->newsLetterTitle;
    }

    public function setNewsLetterContent($newsLetterContent) {

        $htmlString = file_get_contents($this->getTemplateFile());
        $htmlString = str_replace('{newsletter-title}', $this->newsLetterTitle, $htmlString);
        $htmlString = str_replace('{newsletter-content}', $newsLetterContent, $htmlString);
        $htmlString = str_replace('{footer-title}', $this->footerTitle, $htmlString);
        $htmlString = str_replace('{service-name}', $this->serviceName, $htmlString);
        $this->newsLetterContent = $htmlString;
    }

    public function getNewsLetterContent() {

        return $this->newsLetterContent;
    }

    public function setFooterTitle($footerTitle) {

        $this->footerTitle = $footerTitle;
    }

    public function getFooterTitle() {

        return $this->footerTitle;
    }

    public function setServiceName($serviceName) {

        $this->serviceName = $serviceName;
    }

    public function getServiceName() {

        return $this->serviceName;
    }
}
