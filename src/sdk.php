<?php

namespace upush;

use upush\ios\IOSCustomizedcast;
use upush\android\AndroidCustomizedcast;

class sdk {

    protected $appkey = NULL;
    protected $appMasterSecret = NULL;
    protected $timestamp = NULL;
    protected $validation_token = NULL;
    private $debug = true;

    function __construct($key, $secret, $debug) {
        $this->appkey = $key;
        $this->appMasterSecret = $secret;
        $this->timestamp = strval(time());
        $this->debug = $debug;
    }
    function sendMessage($user_id, $alert = '', $badge = 0, $available = 0,$sound='chime') {
        $this->sendAndroidCustomizedcast($user_id,'',0,0,'');
        $this->sendIOSCustomizedcast($user_id,'',0,0,'');
        return true;
    }

    function sendAndroidCustomizedcast($user_id, $alert = '', $badge = 0, $available = 0,$sound='chime') {
        try {
            $customizedcast = new AndroidCustomizedcast();
            $customizedcast->setAppMasterSecret($this->appMasterSecret);
            $customizedcast->setPredefinedKeyValue("appkey", $this->appkey);
            $customizedcast->setPredefinedKeyValue("timestamp", $this->timestamp);
            // Set your alias here, and use comma to split them if there are multiple alias.
            // And if you have many alias, you can also upload a file containing these alias, then 
            // use file_id to send customized notification.
            $customizedcast->setPredefinedKeyValue("alias", $user_id);
            // Set your alias_type here
           // $customizedcast->setPredefinedKeyValue("alias_type", "xx");
            $customizedcast->setPredefinedKeyValue("ticker",$alert);
            $customizedcast->setPredefinedKeyValue("title", $alert);
            $customizedcast->setPredefinedKeyValue("text", $alert);
            $customizedcast->setPredefinedKeyValue("after_open", "go_app");
            // print("Sending customizedcast notification, please wait...\r\n");
            return $customizedcast->send();
            //  print("Sent SUCCESS\r\n");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * 精准推送
     * @param type $user_id  用户id
     * @param type $alert  消息
     * @param type $badge 
     * @param type $available 是否静默
     * @param type $sound  是否有声音
     * @return type
     */
    public function sendIOSCustomizedcast($user_id, $alert = '', $badge = 0, $available = 0,$sound='chime') {
        try {
            $customizedcast = new IOSCustomizedcast();
            $customizedcast->setAppMasterSecret($this->appMasterSecret);
            $customizedcast->setPredefinedKeyValue("appkey", $this->appkey);
            $customizedcast->setPredefinedKeyValue("timestamp", $this->timestamp);

            // Set your alias here, and use comma to split them if there are multiple alias.
            // And if you have many alias, you can also upload a file containing these alias, then 
            // use file_id to send customized notification.
            $customizedcast->setPredefinedKeyValue("alias", $user_id);
            $customizedcast->setPredefinedKeyValue("content-available", $available);
            // Set your alias_type here
            // $customizedcast->setPredefinedKeyValue("alias_type", "xx");
            $customizedcast->setPredefinedKeyValue("alert", $alert);
            $customizedcast->setPredefinedKeyValue("badge", $badge);
            $customizedcast->setPredefinedKeyValue("sound", $sound);
            // Set 'production_mode' to 'true' if your app is under production mode
            $customizedcast->setPredefinedKeyValue("production_mode", $this->debug);
            //print("Sending customizedcast notification, please wait...\r\n");
            return $customizedcast->send();
            //print("Sent SUCCESS\r\n");
        } catch (Exception $e) {
            // print("Caught exception: " . $e->getMessage());
            return $e->getMessage();
        }
    }

}
