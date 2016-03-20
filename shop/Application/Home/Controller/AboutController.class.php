<?php
namespace Home\Controller;
class AboutController extends HomeController {
    const
        DEEP_SUMMARY = 'deep_summary',
        DEEP_CONTENT = 'deep_content';
    public function  indexAction()
    {
        $options = D('options');
        $deep_summary = $options->get_option(self::DEEP_SUMMARY);
        $deep_content = $options->get_option(self::DEEP_CONTENT);
        $this->assign('deep_summary',$deep_summary);
        $this->assign('deep_content',$deep_content);

        $this->display();
    }
}