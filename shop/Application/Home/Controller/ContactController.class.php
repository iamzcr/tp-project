<?php
namespace Home\Controller;
class ContactController extends HomeController {
    const
        DEEP_EMAIL = 'deep_email',
        DEEP_PHONE = 'deep_phone',
        DEEP_FAX = 'deep_fax',
        DEEP_NAME = 'deep_name',
        DEEP_ADDRESS = 'deep_address';

    public function indexAction()
    {
        $options = D('options');
        $deep_name = $options->get_option(self::DEEP_NAME);
        $deep_phone = $options->get_option(self::DEEP_PHONE);
        $deep_fax = $options->get_option(self::DEEP_FAX);
        $deep_email = $options->get_option(self::DEEP_EMAIL);
        $deep_address = $options->get_option(self::DEEP_ADDRESS);

        $this->assign('deep_name',$deep_name);
        $this->assign('deep_phone',$deep_phone);
        $this->assign('deep_fax',$deep_fax);
        $this->assign('deep_email',$deep_email);
        $this->assign('deep_address',$deep_address);

        $this->display();
    }

    public  function sendAction()
    {
        if(IS_AJAX){
            $error = array(
                'type'=>0,
                'message'=>'提交失败'
            );
            $ok = array(
                'type'=>1,
                'message'=>'提交成功，请等待我们的回复 '
            );
            $data = array();

            $data['name']       = @trim(stripslashes($_POST['name']));
            $data['email']      = @trim(stripslashes($_POST['email']));
            $data['title']    = @trim(stripslashes($_POST['title']));
            $data['content']    = @trim(stripslashes($_POST['content']));

            $email_from = $data['email'];
            $email_to = 'zhuchengru1314@163.com';

            $body = '昵称: ' . $data['name']  . "\n\n" . '邮箱: ' . $data['email'] . "\n\n" . '标题: ' . $data['title'] . "\n\n" . '内容: ' .  $data['content'];

            $success = @mail($email_to,  $data['title'], $body, 'From: <'.$email_from.'>');

            if($success){
                $contact = D('contact');
                $contact->create($data);
                $contact->add();
                echo json_encode($ok);
                die;
            }else{
                echo json_encode($error);
                die;
            }
        }
    }
}