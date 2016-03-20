<?php
/**
 * Class MST_Mailer
 * @property PHPMailer_Core $_mailer
 * @package MST
 */
class MST_Mailer {
	
	const LOCAL = 'localhost';
	const FLAG = 'mailer';
	
	const FROM_MAIL = 'fromMail';
	const FROM_NAME = 'fromName';
	const REPLY_MAIL = 'replyMail';
	const REPLY_NAME = 'replyName';
	const IS_SMTP_DEBUG = 'debug';
	const IS_HTML = 'isHtml';
	const IS_SMTP = 'isSMTP';
	const SMTP_AUTH = 'SMTPAuth';
	const SMTP_SECURE = 'SMTPSecure';
	
	const SSL = 'ssl';
	const TLS = 'tls';
	
	const R_TARGET = 'target';
	const R_CC = 'cc';
	const R_BCC = 'bcc';
	
	private static
		$_register = array();
	
	protected static
		$_defaultOptions = array(
			self::IS_SMTP_DEBUG => 0,
			'charset' => PROJECT_ENCODE,
			'language' => PROJECT_LANG,
			'host' => 'localhost',
			'port' => 25,
			'user' => null,
			'password' => null,
			'wrap' => 80,
			self::IS_HTML => true,
			self::IS_SMTP => true,
			self::SMTP_AUTH => true,
			self::SMTP_SECURE => null,
			self::FROM_MAIL => 'root@localhost',
			self::FROM_NAME => 'root',
		);

	private $_mailer = null;
	
	private
		$_error = 0,
		$_reply = array(),
		$_title = null,
		$_body = null,
		$_alt = null,
		// 收件人
		$_recipients = array(
			self::R_TARGET => array(),
			self::R_CC => array(),
			self::R_BCC => array(),
		),
		$_options = null;
	
	public function __construct($remote = self::LOCAL) {
		$m = new PHPMailer_Core(true);
        $config = MST_Core::getConfig(self::FLAG, $remote);
        $o = array_merge(self::$_defaultOptions, (array)$config);

        if ($o[self::FROM_MAIL] == null)
            $o[self::FROM_MAIL] = ($o['user'] == null ? 'root' : $o['user']) . '@' . $o['host'];
        if ($o[self::FROM_NAME] == null)
            $o[self::FROM_NAME] = $o[self::FROM_MAIL];

        if ($o[self::FROM_MAIL] != null)
            $m->AddReplyTo($o[self::FROM_MAIL], $o[self::FROM_NAME]);

        $m->SetLanguage($o['language']);
        $m->CharSet			= $o['charset'];
		$m->SMTPDebug		= $o['debug'];
		
		if ($o[self::IS_SMTP]) {
			$m->isSMTP();
			$m->SMTPAuth	= $o[self::SMTP_AUTH];
			if ($o[self::SMTP_SECURE] == self::SSL ||
				$o[self::SMTP_SECURE] == self::TLS)
			{
				$m->SMTPSecure = $o[self::SMTP_SECURE];
			}
		}
		
		$m->Port			= $o['port'];
		$m->Host			= $o['host'];
		if ($o['user'] != null)
			$m->Username	= $o['user'];
		if ($o['password'] != null)
			$m->Password	= $o['password'];
			
		$m->From			= $o[self::FROM_MAIL];
		$m->FromName		= $o[self::FROM_NAME];
		
		$m->IsHTML(true);
		
		$this->_mailer = $m;
		$this->_options = $o;
	}

    public function setOptions(array $options) {
        $o = array_merge($this->_options, $options);
//        $m = $this->_mailer;

        if ($o[self::FROM_MAIL] == null)
            $o[self::FROM_MAIL] = ($o['user'] == null ? 'root' : $o['user']) . '@' . $o['host'];
        if ($o[self::FROM_NAME] == null)
            $o[self::FROM_NAME] = $o[self::FROM_MAIL];

        if ($o[self::FROM_MAIL] != null) {
            $this->_mailer->ClearReplyTos();
            $this->_mailer->AddReplyTo($o[self::FROM_MAIL], $o[self::FROM_NAME]);
        }

        $this->_mailer->SetLanguage($o['language']);
        $this->_mailer->CharSet			= $o['charset'];
        $this->_mailer->SMTPDebug		= $o['debug'];

        if ($o[self::IS_SMTP]) {
            $this->_mailer->isSMTP();
            $this->_mailer->SMTPAuth	= $o[self::SMTP_AUTH];
            if ($o[self::SMTP_SECURE] == self::SSL ||
                $o[self::SMTP_SECURE] == self::TLS)
            {
                $this->_mailer->SMTPSecure = $o[self::SMTP_SECURE];
            }
        }

        $this->_mailer->Port			= $o['port'];
        $this->_mailer->Host			= $o['host'];
        if ($o['user'] != null)
            $this->_mailer->Username	= $o['user'];
        if ($o['password'] != null)
            $this->_mailer->Password	= $o['password'];

        $this->_mailer->From			= $o[self::FROM_MAIL];
        $this->_mailer->FromName		= $o[self::FROM_NAME];

        $this->_mailer->IsHTML(true);

        if (!empty($o['headers'])) {
            foreach($o['headers'] as $header) {
                $this->_mailer->AddCustomHeader($header);
            }
        }

        $this->_options = $o;
    }
	
	public function setTitle($context) {
		$this->_title = $context;
		return $this;
	}
	
	public function setBody($context) {
		$this->_body = $context;
		return $this;
	}
	
	public function setAlt($context) {
		$this->_alt = $context;
		return $this;
	}
	
	public function setContent($title, $body, $alt = null) {
		if ($alt == null)
			$alt = strip_tags($body);
		return $this->setTitle($title)->setBody($body)->setAlt($title);
	}

	public function setFrom($name, $email) {
		$this->_mailer->From = $email;
		$this->_mailer->FromName = $name;
		return $this;
	}
	
	public function addRecipient($recipients, $flag = self::R_TARGET) {
		if (empty($recipients)) return $this;
		if (is_array($recipients)) {
			if (isset($recipients[self::R_TARGET]) || 
				isset($recipients[self::R_CC]) || 
				isset($recipients[self::R_BCC])) 
			{
				foreach ($recipients as $field => $list) {
					$this->addRecipient($list, $field);
				}
			}
			else {
				if (isset($this->_recipients[$flag])) {
					foreach ($recipients as $mail => $name) {
						if (is_numeric($mail)) $mail = $name;
						$this->_recipients[$flag][$mail] = $name;
					}
				}
			}
		}
		else if (is_string($recipients)) {
			if (isset($this->_recipients[$flag])) {
				$this->_recipients[$flag][$recipients] = $recipients;
			}
		}
	}
	
	public function isSuccess() {
		return $this->_error === 0;
	}
	
	public function getError() {
		return $this->_error;
	}
	
	// 发送之前才检查
	public function send($recipients, array $options = null) {
		if ($options != null)
			// $this->setOptions($options);
		$mail = $this->_mailer;
        // var_dump($this->_mailer);
		$this->addRecipient($recipients);
        // $this->_mailer->AddCustomHeader('');
		if (empty($this->_recipients[self::R_TARGET]))
			$this->_error = '收件人不得为空！';
		else {
			try {
                $this->_mailer->Subject = $this->_title;
                $this->_mailer->AltBody		= $this->_alt;
                $this->_mailer->WordWrap		= $this->_options['wrap'];
                $this->_mailer->MsgHTML($this->_body);
				// 添加收件人
				foreach ($this->_recipients[self::R_TARGET] as $to => $name) {
                    $this->_mailer->AddAddress($to, $name);
				}
				// 添加抄送
				if (!empty($this->_recipients[self::R_CC])) {
					foreach ($this->_recipients[self::R_CC] as $to => $name) {
                        $this->_mailer->AddCC($to, $name);
					}
				}
				// 添加秘密抄送
				if (!empty($this->_recipients[self::R_BCC])) {
					foreach ($this->_recipients[self::R_BCC] as $to => $name) {
                        $this->_mailer->AddBCC($to, $name);
					}
				}
				if (!empty($options['attachs'])) {
					$attachs = $options['attachs'];
					foreach ($attachs as $item) {
                        $this->_mailer->AddAttachment($item);
					}
				}
                $this->_mailer->Send();
			}
			catch (phpmailerException $e) {
				$this->_error = $e->getMessage();
			}
		}
		return $this;
	}
/*
	public static function instance($remote = self::LOCAL) {
		$config = MST_Core::getConfig(self::FLAG, $remote);
		if (empty($config))
			return false;
		else {
			if (!isset(self::$_register[$remote])) {
				self::$_register[$remote] = new static($config);
			}
			return self::$_register[$remote];
		}
	}
		
	private function __construct(array $options) {
		$this->setOptions($options);
	}
	
	public function getOption($key) {
		if (isset($this->_options[$key]))
			return $this->_options[$key];
	}
	
	public function setOptions(array $options = null) {
		if ($this->_options == null)
			$this->_options = self::$_defaultOptions;
		if ($options != null) {
			foreach ($options as $key => $val)
				$this->setOption($key, $val);
		}
		return $this;
	}
	
	public function setOption($key, $val = null) {
		$this->_options[$key] = $val;
		return $this;
	}
	
	private function initMailerOptions() {
		$m = new PHPMailer_Core(true);
		$o = & $this->_options;
		
		if ($o[self::FROM_MAIL] == null)
			$o[self::FROM_MAIL] = ($o['user'] == null ? 'root' : $o['user']) . '@' . $o['host'];
		if ($o[self::FROM_NAME] == null)
			$o[self::FROM_NAME] = $o[self::FROM_MAIL];
			
		if ($o[self::FROM_MAIL] != null)
			$this->addReply($o[self::FROM_MAIL], $o[self::FROM_NAME]);
		
		$m->SetLanguage($o['language']);
		$m->CharSet			= $o['charset'];
		$m->SMTPDebug		= $o['debug'];
		
		if ($o[self::IS_SMTP]) {
			$m->isSMTP();
			$m->SMTPAuth	= $o[self::SMTP_AUTH];
			if ($o[self::SMTP_SECURE] == self::SSL ||
				$o[self::SMTP_SECURE] == self::TLS)
			{
				$m->SMTPSecure = $o[self::SMTP_SECURE];
			}
		}
		
		$m->Port			= $o['port'];
		$m->Host			= $o['host'];
		if ($o['user'] != null)
			$m->Username	= $o['user'];
		if ($o['password'] != null)
			$m->Password	= $o['password'];
			
		$m->From			= $o[self::FROM_MAIL];
		$m->FromName		= $o[self::FROM_NAME];
		
		$m->IsHTML(true);
		
		if (!empty($this->_reply)) {
			foreach ($this->_reply as $mail => $name) {
				$m->AddReplyTo($mail, $name);
			}
		}
		
		$this->_mailer = $m;
		return $m;
	}
	
	public function addReply($mail, $name = null) {
		if ($name == null)
			$name = $mail;
		$this->_reply[$mail] = $name;
		return $this;
	}
	
	public function setTitle($context) {
		$this->_title = $context;
		return $this;
	}
	
	public function setBody($context) {
		$this->_body = $context;
		return $this;
	}
	
	public function setAlt($context) {
		$this->_alt = $context;
		return $this;
	}
	
	public function setContent($title, $body, $alt = null) {
		if ($alt == null)
			$alt = strip_tags($body);
		return $this->setTitle($title)->setBody($body)->setAlt($alt);
	}
	
	public function addRecipient($recipients, $flag = self::R_TARGET) {
		if (empty($recipients)) return $this;
		if (is_array($recipients)) {
			if (isset($recipients[self::R_TARGET]) || 
				isset($recipients[self::R_CC]) || 
				isset($recipients[self::R_BCC])) 
			{
				foreach ($recipients as $field => $list) {
					$this->addRecipient($list, $field);
				}
			}
			else {
				if (isset($this->_recipients[$flag])) {
					foreach ($recipients as $mail => $name) {
						if (is_numeric($mail)) $mail = $name;
						$this->_recipients[$flag][$mail] = $name;
					}
				}
			}
		}
		else if (is_string($recipients)) {
			if (isset($this->_recipients[$flag])) {
				$this->_recipients[$flag][$recipients] = $recipients;
			}
		}
	}
	
	public function isSuccess() {
		return $this->_error === 0;
	}
	
	// 发送之前才检查
	public function send($recipients, array $options = null) {
		if ($options != null)
			$this->setOptions($options);
		$mail = $this->initMailerOptions();
		$mail->ClearAddresses();
		$mail->ClearBCCs();
		$mail->ClearCCs();
		$this->addRecipient($recipients);
		if (empty($this->_recipients[self::R_TARGET]))
			$this->_error = '收件人不得为空！';
		else {
			try {
				$mail->Subject = $this->_title;
				$mail->AltBody		= $this->_alt;
				$mail->WordWrap		= $this->_options['wrap'];
				$mail->MsgHTML($this->_body);
				// 添加收件人
				foreach ($this->_recipients[self::R_TARGET] as $to => $name) {
					$mail->AddAddress($to, $name);
				}
				// 添加抄送
				if (!empty($this->_recipients[self::R_CC])) {
					foreach ($this->_recipients[self::R_CC] as $to => $name) {
						$mail->AddCC($to, $name);
					}
				}
				// 添加秘密抄送
				if (!empty($this->_recipients[self::R_BCC])) {
					foreach ($this->_recipients[self::R_BCC] as $to => $name) {
						$mail->AddBCC($to, $name);
					}
				}
				$mail->Send();
			}
			catch (phpmailerException $e) {
				$this->_error = $e->getMessage();
			}
		}
		return $this;
	}
*/
}