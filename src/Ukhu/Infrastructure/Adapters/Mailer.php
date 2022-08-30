<?php

namespace App\Ukhu\Infrastructure\Adapters;

use App\Ukhu\Application\Ports\EnvInterface;
use App\Ukhu\Application\Ports\MailInterface;
use App\Ukhu\Application\Ports\TemplateInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer implements MailInterface
{
    private $client;

    // sender
    private $fromEmail = 'example@ukhu.demo';
    private $fromName = 'Sent from Ukhu';

    // receiver
    private $toEmail = 'example@ukhu.demo';
    private $toName = 'Sent to Ukhu';

    // user submitted data
    private $replyToUserEmail = 'no-reply@ukhu.demo';
    private $replyToUserName = 'No Reply Ukhu';
    private $subject;
    private $altBody;

    // smtp user
    private $mailHost = 'smtp.mailtrap.io';
    private $mailUsername = 'yourmailtrapusername';
    private $mailPassword = 'yourmailtrappassword';
    private $mailPort = 2525;
    private $env;

    // template
    private $templateClient;
    private $templateFile;
    private $templateData;

    // config
    private $updatableProperties = array(
        'replyToUserEmail',
        'replyToUserName',
        'toEmail',
        'toName',
        'subject',
        'altBody',
    );

    public function __construct(EnvInterface $env, TemplateInterface $template)
    {
        $this->client = new PHPMailer();
        $this->env = $env;
        $this->templateClient = $template;
    }

    /**
     * Updates this class properties defined in $this->updatableProperties
     *
     * @param array $data
     * @return void
     */
    public function configUserData(array $data) : void
    {
        foreach ($data as $key => $value) {
            if(is_null($data[$key])){
                continue;
            }
            if(!property_exists($this, $key)){
                continue;
            }
            if(!in_array($key, $this->updatableProperties)){
                continue;
            }
            $this->$key = $value;
        }
    }

    public function configTemplate($template, $templateData)
    {
        $this->templateFile = $template;
        $this->templateData = $this->appendReservedTemplateVariables($templateData);
    }

    /**
     * Filters our keys found in $this->reservedTemplateVariables
     *
     * @param array $templateData
     * @return void
     */
    public function appendReservedTemplateVariables(array $templateData)
    {
        $reservedTemplateVariables = array(
            '_appEmail' => 'example@ukhu.demo',
            '_appUrl' => 'https://ukhu.demo/',
            '_logoPath' => 'https://ukhu.demo/images/logo-circle.png',
            '_companyName' => 'Ukhu',
            '_companyAddress' => '123 Testing Jr., Suite 100, Parrot Park, CA 12345',
            '_year' => \date('Y'),
            '_emailPreferencesUrl' => 'https://ukhu.demo/',
            '_emailUnsuscribeUrl' => 'https://ukhu.demo/',
        );

        return $templateData + $reservedTemplateVariables;
    }

    public function setup(): void
    {
        //Tell PHPMailer to use SMTP
        $this->client->isSMTP();

        //Enable SMTP debugging
        //SMTP::DEBUG_OFF = off (for production use)
        //SMTP::DEBUG_CLIENT = client messages
        //SMTP::DEBUG_SERVER = client and server messages
        $this->client->SMTPDebug = $this->env->get('APP_DEBUG') ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;

        //Set the hostname of the mail server
        $this->client->Host = $this->mailHost;

        //Set the SMTP port number:
        // - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
        // - 587 for SMTP+STARTTLS
        $this->client->Port = $this->mailPort;

        //Set the encryption mechanism to use:
        // - SMTPS (implicit TLS on port 465) or
        // - STARTTLS (explicit TLS on port 587)
        $this->client->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Whether to use SMTP authentication
        $this->client->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $this->client->Username = $this->mailUsername;

        //Password to use for SMTP authentication
        $this->client->Password = $this->mailPassword;

        //Set who the message is to be sent from
        //Note that with gmail you can only use your account address (same as `Username`)
        //or predefined aliases that you have configured within your account.
        //Do not use user-submitted addresses in here
        $this->client->setFrom($this->fromEmail, $this->fromName);

        //Set an alternative reply-to address
        //This is a good place to put user-submitted addresses
        $this->client->addReplyTo($this->replyToUserEmail, $this->replyToUserName);

        //Set who the message is to be sent to
        $this->client->addAddress($this->toEmail, $this->toName);

        //Set the subject line
        $this->client->Subject = $this->subject;

        $this->client->Body = $this->templateClient->render(
            $this->templateFile,
            $this->templateData
        );

        //Replace the plain text body with one created manually
        $this->client->AltBody = $this->altBody;
    }

    public function send(): bool
    {
        $this->setup();
        if (!$this->client->send()) {
            // echo 'Mailer Error: ' . $this->client->ErrorInfo;
            // inspect $this->ErrorInfo
            return false;
        }
        return true;
    }
}
