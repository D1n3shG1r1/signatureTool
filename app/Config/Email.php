<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    
    /*
    $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'sandbox.smtp.mailtrap.io',
        'smtp_port' => 2525,
        'smtp_user' => 'ac44a1c545b60c',
        'smtp_pass' => '4f5d97cf02a66a',
        'crlf' => "\r\n",
        'newline' => "\r\n"
      );
      */ 
    
    
    public string $fromEmail;
    public string $fromName;
    public string $recipients;

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Address
     */
    public string $SMTPHost = "sandbox.smtp.mailtrap.io";

    /**
     * SMTP Username
     */
    public string $SMTPUser = "70d21b39da1add"; //"ac44a1c545b60c";

    /**
     * SMTP Password
     */
    public string $SMTPPass = "ad3d3e551b71e3"; //"4f5d97cf02a66a";

    /**
     * SMTP Port
     */
    //public int $SMTPPort = 25;
    public int $SMTPPort = 2525;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption. Either tls or ssl
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
