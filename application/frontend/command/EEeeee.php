<?php
class Order_Info
{
    public function perform()
    {
        require 'PHPMailer/PHPMailerAutoload.php';
        $mail = new \PHPMailer;     // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = TRUE;                               // Enable SMTP authentication
        $mail->Username = 'xxx@163.com';                // SMTP username
        $mail->Password = 'xxx';                           // SMTP password
        $mail->SMTPSecure = 'tls';          // Enable TLS encryption, `ssl` also accepted
        $mail->FromName = '跳蚤街';
        $mail->CharSet = "utf-8";
        $mail->IsHTML(TRUE);        //
        $mail->Port = 465;                // TCP port to connect to
        $mail->setFrom('xxx@163.com', '跳蚤街');
        $mail->AddAddress('139xxx@qq.com');    // Add a recipient
        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $this->args['subject'];
        $body = $this->args['body'];
        date_default_timezone_set('Asia/Shanghai');
        $date = date("Y年m月d日，H:i:s", $body['time']);
        $content = <<<html
            您好！<p></p>
            感谢您在Tinywan世界注册帐户！<p></p>
			帐户需要激活才能使用，赶紧激活成为Tinywan家园的正式一员吧:)<p></p>
            点击下面的链接立即激活帐户(或将网址复制到浏览器中打开):<p></p>
            $date
html;

        $mail->Body = $content;
        $mail->AltBody = 'send test';
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo "send success";
        }
    }
}