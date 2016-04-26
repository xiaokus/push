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
    protected $aappkey = NULL;
    protected $aappMasterSecret = NULL;
    function __construct($key, $secret,$akey,$asecret, $debug) {
        $this->aappkey = $akey;
        $this->aappMasterSecret = $asecret;
        $this->appkey = $key;
        $this->appMasterSecret = $secret;
        $this->timestamp = strval(time());
        $this->debug = $debug;
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
   public  function sendMessage($user_id, $option,$alert = '', $badge = 0, $available = 1) {
       $info= $this->sendAndroidCustomizedcast($user_id,$option,$alert,$available);
       $message=$this->sendIOSCustomizedcast($user_id,$option,$alert,$available);
       $result['android']=$info;
       $result['ios']=$message;
      return $result;
    }
    /**
    *精准推送
    */
   public function sendAndroidCustomizedcast($user_id, $option=[],$alert = '',$available = 1) {
        try {
            $customizedcast = new AndroidCustomizedcast();
            $customizedcast->setAppMasterSecret($this->aappMasterSecret);
            $customizedcast->setPredefinedKeyValue("appkey", $this->aappkey);
            $customizedcast->setPredefinedKeyValue("timestamp", $this->timestamp);
            $customizedcast->setPredefinedKeyValue("alias", $user_id);
            if($available){
              $customizedcast->setPredefinedKeyValue("display_type", "message");  
            }
            $customizedcast->setPredefinedKeyValue("alias_type",       "android");
            $customizedcast->setPredefinedKeyValue("ticker",$alert);
            $customizedcast->setPredefinedKeyValue("title", $alert);
            $customizedcast->setPredefinedKeyValue("text", $alert);
            $customizedcast->setPredefinedKeyValue("custom","");
            $customizedcast->setPredefinedKeyValue("production_mode", $this->debug);
            $customizedcast->setPredefinedKeyValue("after_open", "go_app");
            foreach ($option as $key => $value) {
                    $customizedcast->setExtraField($key, $value); 
            }
            return $customizedcast->send();
        } catch (\Exception $e) {
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
    public function sendIOSCustomizedcast($user_id, $option=[], $alert = '', $available = 1,$badge = 0,$sound='') {
        try {
            $customizedcast = new IOSCustomizedcast();
            $customizedcast->setAppMasterSecret($this->appMasterSecret);
            $customizedcast->setPredefinedKeyValue("appkey", $this->appkey);
            $customizedcast->setPredefinedKeyValue("timestamp", $this->timestamp);
            $customizedcast->setPredefinedKeyValue("alias", $user_id);
            if($available){
               $customizedcast->setPredefinedKeyValue("content-available", 1);  
            }
            $customizedcast->setPredefinedKeyValue("alert", $alert);
            $customizedcast->setPredefinedKeyValue("badge", $badge);
            $customizedcast->setPredefinedKeyValue("sound", $sound);
            // Set 'production_mode' to 'true' if your app is under production mode
            $customizedcast->setPredefinedKeyValue("production_mode", $this->debug);
            foreach ($option as $key => $value) {
                    $customizedcast->setCustomizedField($key, $value); 
            }
       
            //print("Sending customizedcast notification, please wait...\r\n");
            return $customizedcast->send();
            //print("Sent SUCCESS\r\n");
        } catch (\Exception $e) {
            // print("Caught exception: " . $e->getMessage());
            return $e->getMessage();
        }
    }

}
