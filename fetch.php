<?php

require 'vendor/autoload.php';
require_once './Subscriber.php';
require_once './MailClient.php';

$link = 'https://cloud.culture.tw/frontsite/trans/SearchShowAction.do?method=doFindTypeJ&category=6';
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $link,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 90,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => [
        'cache-control: no-cache',
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo 'CURL error #'.$err;
} else {
    $info = json_decode($response, true);
    $index = 0;
    $data = [];
    $content = '';
    $currContent = '';
    $contentString = '
    <tr>
    <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
      <br>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">{title}</div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
{description}
<br><br>

開始日期：{start-date}
<br>
結束日期：{end-date}
<br>
展覽地址：{location}
<br>
展覽地點：{locationName}
<br><br>
</div>
    </td>
  </tr>';
    for(;$index<count($info);$index++) {
        $description = $info[$index]['descriptionFilterHtml'] == '' ? $info[$index]['sourceWebPromote'] : $info[$index]['descriptionFilterHtml'];
        $description = str_replace('\r\n', '', $description);
        $content = str_replace('{title}', $info[$index]['title'], $contentString);
        $content = str_replace('{description}', $description, $content);
        $content = str_replace('{start-date}', $info[$index]['startDate'], $content);
        $content = str_replace('{end-date}', $info[$index]['endDate'], $content);
        $content = str_replace('{location}', isset($info[$index]['location']) ? $info[$index]['location']:'unavailable', $content);
        $content = str_replace('{locationName}', isset($info[$index]['locationName']) ? $info[$index]['location']:'unavailable', $content);
        $currContent .= $content;
        $content = '';
    }

    $toMailAddress = file('./mail_lists.txt');
    $mailjetKey = parse_ini_file('./mailjet_key.ini');
    $mailClient = new MailClient();
    $mailClient->setPublicKey($mailjetKey['public_key']);
    $mailClient->setPrivateKey($mailjetKey['private_key']);
    $mailClient->setSubject('展覽電子報');
    $mailClient->setTextPart('Ooops!Your mail client cannot support the HTML-based message.');

    $subscriber = new Subscriber();
    $subscriber->setTemplateFile('./template/template.html');
    $subscriber->setNewsLetterTitle('展覽資訊');
    $subscriber->setFooterTitle('copyright © 2017 <a href="https://github.com/peter279k">peter279k</a>. all rights reserved');
    $subscriber->setServiceName('<a href="https://github.com/peter279k/exhibition-script">exhibition-script</a>');
    $subscriber->setNewsLetterContent($currContent);
    $mailClient->setHtmlPart($subscriber->getNewsLetterContent());

    $mailClient->setFromAddress([
        'Email' => 'peter279k@gmail.com',
        'Name' => 'peter279k'
    ]);

    foreach($toMailAddress as $valueString) {
        $mailAddress = [
            'Email' => $valueString,
            'Name' => explode('@', $valueString)[0]
        ];
        $mailClient->setToAddress($mailAddress);

        $response = $subscriber->sendMail($mailClient);

        if(!$response->success()) {
            echo 'The error happens'.PHP_EOL;
        } else {
            echo 'Send the newsletter to the subscribers is completed!'.PHP_EOL;
        }
    }
}
