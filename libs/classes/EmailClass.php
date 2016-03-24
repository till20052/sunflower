<?php

class EmailClass
{
	protected $recipients = array();
	protected $sender = array();
	protected $subject;
	protected $message;
	protected $contentType = "text/html";
	protected $charset = "utf-8"; // iso-8859-1
	protected $values = array();
	
	public function varTemplates($left, $right)
	{
		$this->varTemplates = array(
			"left" => $left,
			"right" => $right
		);
	}
	
	public function addRecipient($email, $name = "")
	{
		$this->recipients[] = array(
			"email" => $email,
			"name" => $name
		);
	}
	
	public function clearRecipients()
	{
		$this->recipients = array();
	}
	
	public function setSender($email, $name = "")
	{
		$this->sender = array(
			"email" => $email,
			"name" => $name
		);
	}
	
	public function subject($subject = null)
	{
		if( ! is_null($subject))
			$this->subject = $subject;
		
		return $this->subject;
	}
	
	public function message($message = null)
	{
		if( ! is_null($message))
			$this->message = $message;
		
		return $this->message;
	}
	
	public function assign($values)
	{
		$this->values = $values;
	}
	
	public function send()
	{
		$to = array();
		foreach($this->recipients as $recipint)
		{
			$to[] = ($recipint["name"] != "")
				? $recipint["name"]." <".$recipint["email"].">"
				: $recipint["email"];
		}
		
		$from = ($this->sender["name"] != "")
			? "\"".$this->sender["name"]."\" <".$this->sender["email"].">"
			: $this->sender["email"];
		
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: ".$this->contentType."; charset=".$this->charset;
		
		if($from != "")
			$headers[] = "From: ".$from;
		
//		$headers[] = "Bcc: JJ Chong <bcc@domain2.com>";
//		$headers[] = "Reply-To: Recipient Name <receiver@domain3.com>";
//		$headers[] = "Subject: ".$this->subject;
		$headers[] = "X-Mailer: PHP/".phpversion();
		
		foreach($this->values as $var => $value)
		{
			$this->subject = str_replace($this->varTemplates["left"].$var.$this->varTemplates["right"], $value, $this->subject);
			$this->message = str_replace($this->varTemplates["left"].$var.$this->varTemplates["right"], $value, $this->message);
		}
		
		return mail(implode(", ", $to), $this->subject, $this->message, implode("\r\n", $headers));
	}
}
